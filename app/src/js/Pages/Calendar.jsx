import { colors, colorsLight } from '../Utilities/Colors.js';
import { Link, useNavigate, useSearchParams } from 'react-router';
import { useEffect, useState } from 'react';
import { Api } from '@jlbelanger/formosa';
import Auth from '../Utilities/Auth.js';
import ChevronIcon from '../../svg/chevron.svg?react'; // eslint-disable-line import/no-unresolved
import Error from '../Error.jsx';
import { mapTrackables } from '../Utilities/Helpers.jsx';
import MetaTitle from '../Components/MetaTitle.jsx';

export default function Calendar() {
	const api = Api.instance();
	const navigate = useNavigate();
	const thisYear = new Date().getFullYear();
	const [urlSearchParams] = useSearchParams();
	const currentYear = parseInt(urlSearchParams.get('year') || thisYear, 10);

	const [months, setMonths] = useState([]);
	const [error, setError] = useState(false);
	const [trackables, setTrackables] = useState([]);

	const getEntries = (date) => {
		if (!date) {
			return;
		}

		api(`calendar/${date}`)
			.catch((response) => {
				setError(response);
			})
			.then((response) => {
				if (!response) {
					return;
				}
				setMonths(response);
			});
	};

	useEffect(() => {
		if (Auth.hasTrackables()) {
			api(`trackables?fields[trackables]=name,slug,units&filter[slug][in]=${Auth.trackables().join(',')}`)
				.then((response) => {
					setTrackables(mapTrackables(response));
				});
		}

		getEntries(currentYear);
	}, []);

	if (error) {
		return <Error error={error} />;
	}

	const prettyMonth = (ym) => new Date(`${ym}-01T12:00:00Z`).toLocaleString('en-CA', {
		year: 'numeric',
		month: 'long',
	});

	const prettyDay = (date) => parseInt(date.substr(8, 2), 10);

	const changeYear = (modifier = 1) => {
		const newYear = currentYear + modifier;
		navigate(`?year=${newYear}`);
		return newYear;
	};

	const previousYear = () => {
		getEntries(changeYear(-1));
	};

	const nextYear = () => {
		getEntries(changeYear(1));
	};

	// eslint-disable-next-line @stylistic/no-extra-parens
	const withUnits = (value, units) => (value === null ? '' : `${value.toLocaleString()} ${units || ''}`.trim());

	const calculateWeekAverage = (week, key, units) => {
		if (!week) {
			return 0;
		}

		const num = week.length;
		let total = 0;
		let i;
		let count = 0;

		for (i = 0; i < num; i += 1) {
			if (week[i].trackables && Object.hasOwn(week[i].trackables, key) && week[i].trackables[key]) {
				total += week[i].trackables[key];
				count += 1;
			}
		}

		count = count ? Math.round(total / count) : null;

		return withUnits(count, units);
	};

	return (
		<>
			<MetaTitle
				before={(
					<button className="button--icon" id="previous" onClick={previousYear} type="button">
						Previous Year
						<ChevronIcon height={16} width={16} />
					</button>
				)}
				title={currentYear.toString() || 'Calendar'}
			>
				<button className="button--icon" disabled={currentYear === thisYear} id="next" onClick={nextYear} type="button">
					Next Year
					<ChevronIcon height={16} width={16} />
				</button>
			</MetaTitle>

			<ul className="calendar__legend">
				<li className="calendar__legend__item" style={{ backgroundColor: colors[0] }}>
					Weight
				</li>
				{trackables.map((trackable, i) => (
					<li className="calendar__legend__item" key={trackable.id} style={{ backgroundColor: colors[i + 1] }}>
						{trackable.name}
					</li>
				))}
			</ul>

			<div className="calendar__legend-spacer" />

			{months.map((month) => (
				<table className={`calendar${month.data ? '' : ' calendar--hide'}`} key={month.month}>
					<caption className="calendar__caption">{prettyMonth(month.month)}</caption>
					<thead>
						<tr>
							<th aria-label="Sunday" className="calendar__th" scope="col">S</th>
							<th aria-label="Monday" className="calendar__th" scope="col">M</th>
							<th aria-label="Tuesday" className="calendar__th" scope="col">T</th>
							<th aria-label="Wednesday" className="calendar__th" scope="col">W</th>
							<th aria-label="Thursday" className="calendar__th" scope="col">T</th>
							<th aria-label="Friday" className="calendar__th" scope="col">F</th>
							<th aria-label="Saturday" className="calendar__th" scope="col">S</th>
							<th className="calendar__th calendar__th--avg" />
						</tr>
					</thead>
					<tbody>
						{month.weeks.map((week) => (
							<tr key={`${month.month}-${week.week}`}>
								{week.days.map((day) => (
									<td className="calendar__day" key={day.date || day.i}>
										{day.date ? (
											<>
												{day.trackables
													? <Link className="calendar__link" to={`/?date=${day.date}`}>{prettyDay(day.date)}</Link>
													: <span>{prettyDay(day.date)}</span>}
												{day.trackables ? (
													<div className="calendar__item" style={{ backgroundColor: colors[0] }}>
														{day.trackables.weight ? `${day.trackables.weight} ${Auth.weightUnits()}` : '-'}
													</div>
												) : null}
												{trackables.map((trackable, i) => {
													if (!day.trackables) {
														return null;
													}
													let val = '-';
													if (day.trackables[trackable.slug]) {
														val = withUnits(day.trackables[trackable.slug], trackable.units);
													}
													return (
														<div className="calendar__item" key={trackable.id} style={{ backgroundColor: colors[i + 1] }}>
															{val}
														</div>
													);
												})}
											</>
										) : null}
									</td>
								))}
								<td className={`calendar__day${week.data ? ' calendar__day--avg' : ''}`}>
									{week.data ? (
										<>
											<div className="calendar__item" style={{ backgroundColor: colorsLight[0] }}>
												{calculateWeekAverage(week.days, 'weight', Auth.weightUnits()) || '-'}
											</div>
											{trackables.map((trackable, i) => (
												<div className="calendar__item" key={trackable.slug} style={{ backgroundColor: colorsLight[i + 1] }}>
													{calculateWeekAverage(week.days, trackable.slug, trackable.units) || '-'}
												</div>
											))}
										</>
									) : null}
								</td>
							</tr>
						))}
					</tbody>
				</table>
			))}
		</>
	);
}
