import { Alert, Api, Field, FormAlert, Submit } from '@jlbelanger/formosa';
import { useEffect, useState } from 'react';
import Auth from '../../../Utilities/Auth.js';
import { errorMessageText } from '../../../Utilities/Helpers.jsx';
import MyForm from '../../../Components/MyForm.jsx';
import PropTypes from 'prop-types';

export default function Trackables({ user }) {
	const api = Api.instance();
	const [row, setRow] = useState(user);
	const [trackablesError, setTrackablesError] = useState(false);
	const [allTrackables, setAllTrackables] = useState([]);

	useEffect(() => {
		api('trackables?fields[trackables]=name,slug&sort=slug')
			.catch((response) => {
				setTrackablesError(errorMessageText(response));
			})
			.then((response) => {
				if (!response) {
					return;
				}
				setAllTrackables(response);
			});
	}, []);

	if (trackablesError) {
		return (
			<Alert type="error">Error getting trackables.</Alert>
		);
	}

	const checkAll = () => {
		setRow({ ...row, trackables: [...allTrackables] });
	};

	const uncheckAll = () => {
		setRow({ ...row, trackables: [] });
	};

	const afterSubmitSuccess = () => {
		Auth.setTrackables(row.trackables.sort((a, b) => (a.id > b.id)));
	};

	return (
		<MyForm
			afterNoSubmit={afterSubmitSuccess}
			afterSubmitSuccess={afterSubmitSuccess}
			errorMessageText={errorMessageText}
			id={row.id}
			method="PUT"
			path="users"
			preventEmptyRequest
			relationshipNames={['trackables']}
			row={row}
			setRow={setRow}
			showMessage={false}
			successToastText="Tracking settings updated successfully."
		>
			<h2 className="flex">
				<span style={{ alignSelf: 'center', flex: '1 1 auto' }}>Tracking</span>
				<button className="formosa-button button--small button--secondary" onClick={checkAll} type="button">Check All</button>
				<button className="formosa-button button--small button--secondary" onClick={uncheckAll} type="button">Uncheck All</button>
			</h2>

			<FormAlert />

			<Field
				fieldsetClassName="radio-list"
				inputAttributes={(option) => ({ id: `trackable-${option.slug}` })}
				name="trackables"
				options={allTrackables}
				type="checkbox-list"
			/>

			<Submit id="save-tracking" label="Save" />
		</MyForm>
	);
}

Trackables.propTypes = {
	user: PropTypes.object.isRequired,
};
