import { defineConfig } from 'vite';
import postcssGlobalData from '@csstools/postcss-global-data';
import postcssPresetEnv from 'postcss-preset-env';
import react from '@vitejs/plugin-react'; // eslint-disable-line import/no-unresolved
import svgr from 'vite-plugin-svgr';

export default defineConfig(() => ({
	build: {
		outDir: 'build',
	},
	css: {
		postcss: {
			plugins: [
				postcssGlobalData({
					files: ['./src/css/utilities/breakpoints.css'],
				}),
				postcssPresetEnv,
			],
		},
	},
	plugins: [react(), svgr()],
	server: {
		open: true,
		port: 3000,
	},
}));
