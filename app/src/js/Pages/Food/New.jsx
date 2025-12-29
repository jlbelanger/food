import { errorMessageText } from '../../Utilities/Helpers.jsx';
import { Field } from '@jlbelanger/formosa';
import Fields from './Partials/Fields.jsx';
import MetaTitle from '../../Components/MetaTitle.jsx';
import MyForm from '../../Components/MyForm.jsx';
import { useNavigate } from 'react-router';
import { useState } from 'react';

export default function New() {
	const defaultRow = { meta: { is_favourite: true } };
	const [row, setRow] = useState(defaultRow);
	const [addAnother, setAddAnother] = useState(false);
	const navigate = useNavigate();

	const afterSubmitSuccess = (response) => {
		if (addAnother) {
			setRow(defaultRow);
		} else {
			navigate(`/food/${response.id}`);
		}
	};

	return (
		<>
			<MetaTitle title="Add food">
				<button className="formosa-button button--small" form="add-form" type="submit">Save</button>
				<Field
					id="add-another"
					label="Add another"
					labelPosition="after"
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
				path="food"
				row={row}
				setRow={setRow}
				successToastText="Food added successfully."
			>
				<Fields row={row} />
			</MyForm>
		</>
	);
}
