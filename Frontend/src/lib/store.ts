import type { Writable } from 'svelte/store';
import type { UserStore } from '$types';
import { derived, writable, get } from 'svelte/store';
import { request } from '$lib/request';

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

const createLocationListStore = () => {
	// const { subscribe, update } = writable({ data: null, timestamp: 0 });
	const store = writable({ data: null, timestamp: 0 });
	const { subscribe, update } = store;
	// console.log('store', store);
	console.log('subscribe', subscribe);
	console.log('get', get(tokenST));
	const fetchData = async () => {
		try {
			const { status, data, message } = await request('GET', 'location/list', null, get(tokenST));
			// const { status, message, data } = await request('GET', 'member/list');

			console.log('data', data);
			console.log('status', status);
			console.log('message', message);
			// Update the store with new data and current timestamp
			update((state) => ({ ...state, data, timestamp: Date.now() }));
		} catch (error) {
			console.error('Error fetching data:', error);
		}
	};

	const refreshDataIfNeeded = () => {
		const { timestamp } = get(store);
		// const { timestamp } = 1;
		const oneMinuteAgo = Date.now() - 60000;

		if (timestamp < oneMinuteAgo) {
			fetchData();
		}
	};

	// Initial fetch
	refreshDataIfNeeded();

	return {
		subscribe,
		refresh: refreshDataIfNeeded
	};
};

export const locationList = createLocationListStore();
