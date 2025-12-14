import React, { useState } from 'react';
import { errorMessageText } from '../../Utilities/Helpers';
import { Field } from '@jlbelanger/formosa';
import Fields from './Partials/Fields';
import MetaTitle from '../../Components/MetaTitle';
import MyForm from '../../Components/MyForm';
import { useNavigate } from 'react-router';

export default function New() {
	const defaultRow = { is_favourite: true };
	const [row, setRow] = useState(defaultRow);
	const [addAnother, setAddAnother] = useState(false);
	const navigate = useNavigate();

	const afterSubmitSuccess = (response) => {
		if (addAnother) {
			setRow(defaultRow);
		} else {
			navigate(`/meals/${response.id}`);
		}
	};

	return (
		<>
			<MetaTitle title="Add meal">
				<button className="formosa-button button--small" form="add-form" type="submit">Save</button>
				<Field
					label="Add another"
					labelPosition="after"
					id="add-another"
					setValue={setAddAnother}
					type="checkbox"
					value={addAnother}
				/>
			</MetaTitle>

			<MyForm
				afterSubmitSuccess={afterSubmitSuccess}
				errorMessageText={errorMessageText}
				htmlId="add-form"
				method="POST"
				path="meals"
				row={row}
				setRow={setRow}
				successToastText="Meal added successfully."
			>
				<Fields row={row} />
			</MyForm>
		</>
	);
}
