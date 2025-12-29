import { Api, FormContainer } from '@jlbelanger/formosa';
import Auth from './Utilities/Auth.js';
import Footer from './Footer.jsx';
import Header from './Header.jsx';
import { Outlet } from 'react-router';

export default function Layout() {
	if (Auth.isLoggedIn() && !Api.getToken()) {
		Api.setToken(Auth.token());
	}

	document.addEventListener('formosaApiRequest', () => {
		Auth.refresh();
	});

	// Accessibility: skip to content (https://www.bignerdranch.com/blog/web-accessibility-skip-navigation-links/).
	const onClick = (e) => {
		e.preventDefault();
		const id = e.target.getAttribute('href').split('#')[1];
		const elem = document.getElementById(id);
		elem.setAttribute('tabindex', -1);
		elem.addEventListener('blur', () => {
			elem.removeAttribute('tabindex');
		});
		elem.focus();
	};

	return (
		<>
			<a href="#article" id="skip" onClick={onClick}>Skip to content</a>
			<main id="main">
				<FormContainer>
					<Header />
					<Footer />
					<article className="contain" id="article">
						<Outlet />
					</article>
				</FormContainer>
			</main>
		</>
	);
}
