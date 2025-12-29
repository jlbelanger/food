import { colorsLight } from '../Utilities/Colors.js';
import PropTypes from 'prop-types';

export default function TrackableHead({ trackables = [] }) {
	return (
		<th className="column--trackables" scope="col">
			<div className="trackable-list">
				{trackables.map((trackable, i) => (
					<span
						className="trackable-item table-heading"
						key={trackable.id}
						style={{ backgroundColor: colorsLight[i + 1] }}
					>
						{trackable.name}
					</span>
				))}
			</div>
		</th>
	);
}

TrackableHead.propTypes = {
	trackables: PropTypes.array,
};
