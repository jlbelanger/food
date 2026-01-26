import { Alert, Api, Form, FormosaContext } from '@jlbelanger/formosa';
import DiaryWeightFieldset from './DiaryWeightFieldset.jsx';
import { errorMessageText } from '../Utilities/Helpers.jsx';
import PropTypes from 'prop-types';
import { useContext } from 'react';

export default function DiaryWeight({ date = null, error = null, setActionError, setWeight, weight = null }) {
	const { addToast } = useContext(FormosaContext);

	const afterSubmitFailure = (response) => {
		if (response.status !== 422) {
			setActionError(errorMessageText(response));
		}
	};

	if (error) {
		return <Alert className="form" type="error">Error getting weight.</Alert>;
	}

	if (weight === null) {
		return (
			<Form className="form" key="fake-form">
				<DiaryWeightFieldset disabled />
			</Form>
		);
	}

	return (
		<Form
			afterSubmitFailure={afterSubmitFailure}
			afterSubmitSuccess={(response, formState, setFormState) => {
				if (response.id && !weight.id) {
					setWeight({ ...weight, id: response.id });
					formState.setOriginalValue(formState, setFormState, 'id', response.id);
				}
			}}
			beforeSubmit={() => {
				setActionError(false);
				if (weight.id && weight.weight === '') {
					Api.delete(`weights/${weight.id}`)
						.catch((response) => {
							setActionError(errorMessageText(response));
						})
						.then((response) => {
							if (!response) {
								return;
							}
							addToast('Weight removed successfully.', 'success');
							setWeight({ date });
						});
					return false;
				}
				return true;
			}}
			className="form"
			htmlId="weight-form"
			id={weight && weight.id ? weight.id : null}
			key="real-form"
			method={weight && weight.id ? 'PUT' : 'POST'}
			path="weights"
			preventEmptyRequest
			row={weight}
			setRow={(value) => {
				setWeight(value);
			}}
			successToastText="Weight saved successfully."
		>
			<DiaryWeightFieldset />
		</Form>
	);
}

DiaryWeight.propTypes = {
	date: PropTypes.string,
	error: PropTypes.object,
	setActionError: PropTypes.func.isRequired,
	setWeight: PropTypes.func.isRequired,
	weight: PropTypes.object,
};
