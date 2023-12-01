import type { Writable } from 'svelte/store';
import type { UserStore } from '$types';
import { writable, get, readable } from 'svelte/store';
import { request } from '$lib/request';

export const tokenST: Writable<string> = writable('');
export const userST: UserStore = writable({});

/**
 * @description create a store for Member List
 *
 * @param {string} token
 * @returns {object} store
 */
// const createLocationListStore = () => {
// 	// const { subscribe, update } = writable({ data: null, timestamp: 0 });
// 	const store = writable({ data: null, timestamp: 0 });
// 	const { subscribe, update } = store;
// 	// console.log('store', store);
// 	// console.log('subscribe', subscribe);
// 	// console.log('get', get(tokenST));
// 	const fetchData = async () => {
// 		try {
// 			const { status, data, message } = await request('GET', 'location/list', null, get(tokenST));
// 			// const { status, message, data } = await request('GET', 'member/list');

// 			// console.log('data', data);
// 			// console.log('status', status);
// 			// console.log('message', message);
// 			// Update the store with new data and current timestamp
// 			update((state) => ({ ...state, data, timestamp: Date.now() }));
// 		} catch (error) {
// 			console.error('Error fetching data:', error);
// 		}
// 	};

// 	const refreshDataIfNeeded = () => {
// 		const { timestamp } = get(store);
// 		// const { timestamp } = 1;
// 		const oneMinuteAgo = Date.now() - 60000;

// 		if (timestamp < oneMinuteAgo) {
// 			fetchData();
// 		}
// 	};

// 	// Initial fetch
// 	refreshDataIfNeeded();

// 	return {
// 		subscribe,
// 		refresh: refreshDataIfNeeded
// 	};
// };

// export const locationList = createLocationListStore();

//
//
// Managing fetch promises with a store • REPL • Svelte
// https://svelte.dev/repl/dcc69ccad6c341e8b75ee38c3eac1524?version=4.2.8

export function initialValue() {
	return [];
}

export function makeListStore(endpoint) {
	const initial = initialValue();
	const store = readable(initial, makeSubscribe(endpoint));
	return store;
}

function unsubscribe() {}

function makeSubscribe(endpoint) {
	// console.log('endpoint', endpoint);
	return (set) => {
		fetchListData(endpoint, set);
		return unsubscribe;
	};
}

async function fetchListData(endpoint, set) {
	try {
		const { status, data, message } = await request('GET', endpoint, null, get(tokenST));
		if (status === 200) {
			// console.log('data', data);
			set(data);
		} else {
			throw new Error(message);
		}
	} catch (error) {
		data.error = error;
		set(data);
	}
}
