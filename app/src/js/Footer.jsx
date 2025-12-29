import Auth from './Utilities/Auth.js';
import { NavLink } from 'react-router';

export default function Footer() {
	return (
		<footer id="footer">
			<nav className="contain" id="nav">
				{Auth.isLoggedIn() ? (
					<>
						<NavLink className="nav__link" to="/">Diary</NavLink>
						<NavLink className="nav__link" to="/calendar">Calendar</NavLink>
						<NavLink className="nav__link" to="/charts">Charts</NavLink>
						<NavLink className="nav__link" to="/food">Food</NavLink>
						<NavLink className="nav__link" to="/meals">Meals</NavLink>
					</>
				) : (
					<>
						<NavLink className="nav__link" to="/">Login</NavLink>
						<NavLink className="nav__link" to="/register">Register</NavLink>
					</>
				)}
			</nav>
		</footer>
	);
}
