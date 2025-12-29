import PropTypes from 'prop-types';
import { useEffect } from 'react';

export default function MetaTitle({
	before = null,
	children = null,
	small = '',
	title = '',
}) {
	useEffect(() => {
		let metaTitle = title;
		if (import.meta.env.VITE_TITLE) {
			if (metaTitle) {
				metaTitle += ' | ';
			}
			metaTitle += import.meta.env.VITE_TITLE;
		}
		document.querySelector('title').innerText = metaTitle;
	}, [title]);

	return (
		<>
			<div id="heading">
				<div id="heading-inner">
					{before}
					<h1>
						{title}
						{small && <small className="heading-small">{small}</small>}
					</h1>
					{children}
				</div>
			</div>
			<div id="heading-spacer" />
		</>
	);
}

MetaTitle.propTypes = {
	before: PropTypes.node,
	children: PropTypes.node,
	small: PropTypes.string,
	title: PropTypes.string,
};
