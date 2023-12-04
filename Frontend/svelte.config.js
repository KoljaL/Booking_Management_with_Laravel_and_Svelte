import adapter from '@sveltejs/adapter-static';
import { vitePreprocess } from '@sveltejs/kit/vite';
// import path from 'path';

/** @type {import('@sveltejs/kit').Config} */
const config = {
	preprocess: vitePreprocess(),
	kit: {
		adapter: adapter({
			fallback: '404.html'
			// pages: 'build',
			// assets: 'build',
			// fallback: 'index.html'
			// // fallback: undefined,
			// precompress: false,
			// strict: true
		})
	},
	prerender: { default: true },
	// paths: {
	// 	base: process.env.APP_BASE || "",
	// },
	paths: { base: '' },
	alias: {
		$components: './src/components',
		$lib: './src/lib',
		$store: './src/lib/store.ts',
		$styles: './src/styles',
		$utils: './src/lib/utils',
		$types: './src/types.d.ts'
	}
};

export default config;
