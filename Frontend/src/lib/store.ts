// cereate a store for the token
// import { writable } from 'svelte/store';
import { derived, writable } from 'svelte/store';
import type { Writable } from 'svelte/store';
import type { UserStore } from '$types';

export const tokenST: Writable<string> = writable('');
export const userST: UserStore = writable({});

export function urlST(ssrUrl) {
	// Ideally a bundler constant so that it's tree-shakable
	if (typeof window === 'undefined') {
		const { subscribe } = writable(ssrUrl);
		return { subscribe };
	}

	const href = writable(window.location.href);

	const originalPushState = history.pushState;
	const originalReplaceState = history.replaceState;

	const updateHref = () => href.set(window.location.href);

	history.pushState = function () {
		originalPushState.apply(this, rgs);
		updateHref();
	};

	history.replaceState = function () {
		originalReplaceState.apply(this, rgs);
		updateHref();
	};

	window.addEventListener('popstate', updateHref);
	window.addEventListener('hashchange', updateHref);

	return {
		subscribe: derived(href, ($href) => new URL($href)).subscribe
	};
}
