import { Api, Field, Form, FormosaContext } from '@jlbelanger/formosa';
import { Link, useHistory } from 'react-router-dom';
import React, { useContext, useEffect, useState } from 'react';
import Auth from '../Utilities/Auth';
import { ReactComponent as ChevronIcon } from '../../svg/chevron.svg';
import { colorsLight } from '../Utilities/Colors';
import DiaryAddExtra from '../Components/DiaryAddExtra';
import DiaryAddFood from '../Components/DiaryAddFood';
import DiaryAddMeal from '../Components/DiaryAddMeal';
import DiaryWeight from '../Components/DiaryWeight';
import MetaTitle from '../Components/MetaTitle';
import { pluralize } from '../Utilities/Helpers';
import TrackableBody from '../Components/TrackableBody';
import TrackableFoot from '../Components/TrackableFoot';
import TrackableHead from '../Components/TrackableHead';
import { ReactComponent as XIcon } from '../../svg/x.svg';

export default function Diary() {
	const history = useHistory();
	const ymd = (date) => date.toLocaleString('en-CA').substring(0, 10);
	const today = ymd(new Date());
	const urlSearchParams = new URLSearchParams(history.location.search);
	const currentDate = urlSearchParams.get('date') || today;
	const foodFields = ['name', 'serving_size', 'serving_units'].concat(Auth.trackables());

	const { addToast } = useContext(FormosaContext);
	const [errorExtras, setErrorExtras] = useState(false);
	const [errorEntries, setErrorEntries] = useState(false);
	const [diary, setDiary] = useState({ entries: [], extras: [] });
	const [trackables, setTrackables] = useState([]);

	const getEntries = (date) => {
		if (!date) {
			return;
		}

		Api.get(`entries?filter[date][eq]=${date}&fields[entries]=user_serving_size&fields[food]=${foodFields.join(',')}&include=food`)
			.then((entriesResponse) => {
				setDiary({ ...diary, entries: entriesResponse });

				Api.get(`extras?filter[date][eq]=${date}&fields[extras]=${['note'].concat(Auth.trackables()).join(',')}`)
					.then((extrasResponse) => {
						setDiary({ entries: entriesResponse, extras: extrasResponse });
					})
					.catch(() => {
						setErrorExtras(true);
					});
			})
			.catch(() => {
				setErrorEntries(true);
			});
	};

	useEffect(() => {
		if (Auth.hasTrackables()) {
			Api.get(`trackables?fields[trackables]=name,slug,units&filter[slug][in]=${Auth.trackables().join(',')}`)
				.then((response) => {
					setTrackables(response);
				});
		}

		getEntries(currentDate);
	}, []);

	const prettyDate = new Date(`${currentDate}T12:00:00Z`).toLocaleString('en-CA', {
		weekday: 'short',
		year: 'numeric',
		month: 'short',
		day: 'numeric',
	});

	const changeDay = (modifier = 1) => {
		let newDate = new Date(`${currentDate}T12:00:00Z`);
		newDate.setDate(newDate.getDate() + (1 * modifier));
		newDate = ymd(newDate);
		urlSearchParams.set('date', newDate);
		history.replace({ search: urlSearchParams.toString() });
		return newDate;
	};

	const previousDay = () => {
		getEntries(changeDay(-1));
	};

	const nextDay = () => {
		getEntries(changeDay(1));
	};

	const deleteEntry = (e) => {
		Api.delete(`entries/${e.target.getAttribute('data-id')}`)
			.then(() => {
				addToast('Food removed successfully.', 'success');

				const newEntries = [...diary.entries];
				newEntries.splice(e.target.getAttribute('data-index'), 1);
				setDiary({ ...diary, entries: newEntries });
			})
			.catch((response) => {
				const text = response.message ? response.message : response.errors.map((err) => (err.title)).join(' ');
				addToast(text, 'error', 10000);
			});
	};

	const updateEntry = (entry) => {
		const body = {
			data: {
				id: entry.id,
				type: entry.type,
				attributes: {
					user_serving_size: entry.user_serving_size,
				},
			},
		};
		Api.put(`entries/${entry.id}`, JSON.stringify(body))
			.then(() => {
				addToast('Entry saved successfully.', 'success');
			})
			.catch((reponse) => {
				const text = reponse.message ? reponse.message : reponse.errors.map((err) => (err.title)).join(' ');
				addToast(text, 'error', 10000);
			});
	};

	const deleteExtra = (e) => {
		Api.delete(`extras/${e.target.getAttribute('data-id')}`)
			.then(() => {
				addToast('Extra removed successfully.', 'success');

				const newExtras = [...diary.extras];
				newExtras.splice(e.target.getAttribute('data-index'), 1);
				setDiary({ ...diary, extras: newExtras });
			})
			.catch((response) => {
				const text = response.message ? response.message : response.errors.map((err) => (err.title)).join(' ');
				addToast(text, 'error', 10000);
			});
	};

	return (
		<>
			<MetaTitle
				before={(
					<button className="button--icon" id="previous" onClick={previousDay} type="button">
						Previous Day
						<ChevronIcon height={16} width={16} />
					</button>
				)}
				title={currentDate ? prettyDate : 'Diary'}
			>
				<button className="button--icon" disabled={currentDate === today} id="next" onClick={nextDay} type="button">
					Next Day
					<ChevronIcon height={16} width={16} />
				</button>
			</MetaTitle>

			<div id="diary">
				<div id="diary-top">
					<DiaryAddFood date={currentDate} diary={diary} foodFields={foodFields} setDiary={setDiary} />
					<DiaryAddExtra date={currentDate} diary={diary} setDiary={setDiary} />
					<DiaryWeight date={currentDate} />
				</div>

				<DiaryAddMeal date={currentDate} diary={diary} foodFields={foodFields} setDiary={setDiary} />
			</div>

			{errorEntries && (<p className="formosa-message formosa-message--error">Error getting entries.</p>)}
			{errorExtras && (<p className="formosa-message formosa-message--error">Error getting extras.</p>)}

			{(diary.entries.length > 0 || diary.extras.length > 0) && (
				<table id="diary-table">
					<thead>
						<tr>
							<th scope="col"><span className="table-heading">Name</span></th>
							<th className="column--serving" scope="col"><span className="table-heading">Serving Size</span></th>
							<TrackableHead trackables={trackables} />
							<th className="column--button" scope="col"><span className="table-heading" /></th>
						</tr>
					</thead>
					<tbody>
						{diary.entries.map((entry, i) => (
							<tr key={entry.id}>
								<td>
									<Link className="table-link" to={`/food/${entry.food.id}`}>{entry.food.name}</Link>
								</td>
								<td className="column--serving">
									<Field
										onKeyUp={(e) => {
											if (e.key === 'Enter') {
												updateEntry(entry);
											}
										}}
										onBlur={() => {
											updateEntry(entry); // TODO: Only submit if dirty.
										}}
										name={`entries.${i}.user_serving_size`}
										size={6}
										suffix={pluralize(entry.food.serving_units, entry.user_serving_size)}
										setValue={(newValue) => {
											const e = [...diary.entries];
											e[i].user_serving_size = newValue;
											setDiary({
												...diary,
												entries: e,
											});
										}}
										value={diary.entries[i].user_serving_size}
									/>
								</td>
								<TrackableBody food={entry.food} servingSize={parseFloat(entry.user_serving_size)} trackables={trackables} />
								<td className="column--button">
									<button
										className="button--icon button--remove"
										data-id={entry.id}
										data-index={i}
										onClick={deleteEntry}
										type="button"
									>
										Remove
										<XIcon height={16} width={16} />
									</button>
								</td>
							</tr>
						))}

						{diary.extras.map((extra, i) => (
							<tr key={extra.id}>
								<td>
									<Form
										htmlId={`extra-${extra.id}`}
										id={extra.id}
										method="PUT"
										path="extras"
										preventEmptyRequest
										row={extra}
										setRow={(newNote) => {
											const e = [...diary.extras];
											e[i].note = newNote;
											setDiary({ ...diary, extras: e });
										}}
										successToastText="Extra saved successfully."
									>
										<Field id={`note-${extra.id}`} name="note" />
									</Form>
								</td>
								<td />
								{trackables.map((trackable, j) => (
									<td className="center" key={trackable.id} style={{ backgroundColor: colorsLight[j + 1] }}>
										<Field
											form={`extra-${extra.id}`}
											id={`${trackable.slug}-${extra.id}`}
											name={`extras.${i}.${trackable.slug}`}
											size={6}
										/>
									</td>
								))}
								<td className="column--button">
									<button
										className="button--icon button--remove"
										data-id={extra.id}
										data-index={i}
										onClick={deleteExtra}
										type="button"
									>
										Remove
										<XIcon height={16} width={16} />
									</button>
								</td>
							</tr>
						))}
					</tbody>
					<tfoot>
						<tr>
							<th />
							<th />
							<TrackableFoot extras={diary.extras} rows={diary.entries} trackables={trackables} />
							<th />
						</tr>
					</tfoot>
				</table>
			)}
		</>
	);
}
