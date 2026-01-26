import { Alert, Api } from '@jlbelanger/formosa';
import { useEffect, useState } from 'react';
import Auth from '../../Utilities/Auth.js';
import Bmi from './Partials/Bmi.jsx';
import ChangeEmail from './Partials/ChangeEmail.jsx';
import ChangePassword from './Partials/ChangePassword.jsx';
import ChangeUsername from './Partials/ChangeUsername.jsx';
import DeleteData from './Partials/DeleteData.jsx';
import Error from '../../Error.jsx';
import { errorMessageText } from '../../Utilities/Helpers.jsx';
import MetaTitle from '../../Components/MetaTitle.jsx';
import Modal from '../../Components/Modal.jsx';
import Trackables from './Partials/Trackables.jsx';

export default function Edit() {
	const api = Api.instance();
	const id = Auth.id();
	const [row, setRow] = useState(null);
	const [error, setError] = useState(false);
	const [deleteError, setDeleteError] = useState(false);
	const [showModal, setShowModal] = useState(false);

	const deleteRow = () => {
		setShowModal(false);
		Api.delete(`users/${row.id}`)
			.catch((response) => {
				setDeleteError(errorMessageText(response));
			})
			.then((response) => {
				if (!response) {
					return;
				}
				Auth.logout();
			});
	};

	useEffect(() => {
		api(`users/${id}?fields[trackables]=name,slug&include=trackables`)
			.catch((response) => {
				setError(response);
			})
			.then((response) => {
				if (!response) {
					return;
				}
				setRow(response);
			});
	}, [id]);

	if (error) {
		return <Error error={error} />;
	}

	if (row === null) {
		return <MetaTitle title="Profile" />;
	}

	return (
		<>
			<MetaTitle title="Profile" />

			<Bmi user={row} />

			<hr />

			<Trackables user={row} />

			<hr />

			<h2>Account</h2>
			<ChangeUsername id={row.id} username={row.username} />
			<hr />
			<ChangeEmail email={row.email} />
			<hr />
			<ChangePassword />

			<hr />

			<h3>Delete data</h3>
			{deleteError ? <Alert type="error">{deleteError}</Alert> : null}
			<DeleteData setDeleteError={setDeleteError} user={row} />

			<p>
				<button
					className="formosa-button formosa-button--danger"
					onClick={(e) => {
						setDeleteError(false);
						setShowModal(e);
					}}
					type="button"
				>
					Delete account
				</button>
			</p>

			{showModal ? (
				<Modal
					event={showModal}
					okButtonClass="formosa-button--danger"
					okButtonText="Delete"
					onClickCancel={() => {
						setShowModal(false);
					}}
					onClickOk={deleteRow}
					text="Are you sure you want to delete your account?"
				/>
			) : null}
		</>
	);
}
