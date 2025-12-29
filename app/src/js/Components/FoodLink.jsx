import { Link } from 'react-router';
import PropTypes from 'prop-types';

export default function FoodLink({ food }) {
	return (
		<Link className={`table-link${food.is_verified ? ' verifiable' : ''}`} to={`/food/${food.id}`}>
			{food.is_verified ? (
				<>
					<span className="verified-text">{food.name}</span>
					{'\u00a0'}
					<span aria-label="Verified" className="verified-icon">{'\u2713'}</span>
				</>
			) : food.name}
		</Link>
	);
}

FoodLink.propTypes = {
	food: PropTypes.object.isRequired,
};
