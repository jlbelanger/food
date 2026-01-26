import { Alert, Api, Field } from '@jlbelanger/formosa';
import { errorMessageText, foodLabelFn, foodLabelLinkFn, pluralize } from '../../Utilities/Helpers.jsx';
import { Link, useParams } from 'react-router';
import { useEffect, useState } from 'react';
import Auth from '../../Utilities/Auth.js';
import Error from '../../Error.jsx';
import MetaTitle from '../../Components/MetaTitle.jsx';
import MyForm from '../../Components/MyForm.jsx';

export default function Edit() {
	const api = Api.instance();
	const { id } = useParams();
	const [row, setRow] = useState(null);
	const [error, setError] = useState(false);
	const [foodError, setFoodError] = useState(false);
	const [favouritesOnly, setFavouritesOnly] = useState(Auth.getValue('favourites_only', false));
	const [favouriteFood, setFavouriteFood] = useState([]);
	const [food, setFood] = useState([]);
	const foodFields = ['name', 'serving_units', 'is_verified'];

	useEffect(() => {
		api(`entries/${id}?include=food&fields[food]=${foodFields.join(',')}`)
			.catch((response) => {
				setError(response);
			})
			.then((response) => {
				if (!response) {
					return;
				}
				setRow(response);
			});

		api(`food?fields[food]=${foodFields.concat(['is_favourite']).join(',')}`)
			.catch((response) => {
				setFoodError(errorMessageText(response));
			})
			.then((response) => {
				if (!response) {
					return;
				}
				setFood(response);
				setFavouriteFood(response.filter((r) => r.is_favourite));
			});
	}, [id]);

	if (error) {
		return <Error error={error} />;
	}

	if (row === null) {
		return <MetaTitle title="Edit entry" />;
	}

	return (
		<>
			<MetaTitle title="Edit entry">
				<button className="formosa-button button--small" form="edit-form" type="submit">Save</button>
			</MetaTitle>

			{foodError ? <Alert type="error">Error getting food.</Alert> : null}

			<MyForm
				className="formosa-responsive"
				errorMessageText={errorMessageText}
				htmlId="edit-form"
				id={id}
				method="PUT"
				path="entries"
				preventEmptyRequest
				relationshipNames={['food']}
				row={row}
				setRow={setRow}
				successToastText="Entry saved successfully."
			>
				<Field
					className={row.date ? 'formosa-prefix' : ''}
					label="Date"
					maxLength={10}
					name="date"
					postfix={row.date
						? <Link className="formosa-button formosa-postfix button--secondary" to={`/?date=${row.date}`}>Go</Link>
						: null}
					required
					size={10}
					type="date"
				/>
				{!foodError && (
					<Field
						label="Food"
						labelFn={foodLabelLinkFn}
						max={1}
						name="food"
						optionLabelFn={foodLabelFn}
						options={favouritesOnly ? favouriteFood : food}
						placeholder="Search food"
						postfix={(
							<Field
								id="search-favourites"
								label="Search favourite foods only"
								labelPosition="after"
								setValue={(newValue) => {
									Auth.setValue('favourites_only', newValue);
									setFavouritesOnly(newValue);
								}}
								type="checkbox"
								value={favouritesOnly}
							/>
						)}
						required
						type="autocomplete"
					/>
				)}
				<Field
					inputMode="decimal"
					label="Serving size"
					name="user_serving_size"
					required
					size={6}
					suffix={pluralize(row.food.serving_units, row.user_serving_size)}
				/>
			</MyForm>
		</>
	);
}
