// import type { Writable } from 'svelte/store';
// import type { UserStore } from '$types';
import { writable } from 'svelte/store';
import { asyncable } from './asyncStore';
// import { get } from 'svelte/store';
const URL = 'https://public.test/api/';
// import { browser } from '$app/environment';

export const userST: UserStore = writable({});

const initialUserData = null;
export const userStore = asyncable(
	(userData) => userData,
	(newUserData) => localStorage.setItem('RB_user', JSON.stringify(newUserData)),
	[],
	initialUserData
);

const initialBookingData = null;
export const bookingStore = asyncable(
	(bookingData) => bookingData,
	(newBookingData) => localStorage.setItem('RB_booking', JSON.stringify(newBookingData)),
	[],
	initialBookingData
);
/**
 * Request wrapper for the fetch API
 *
 * @param {string} method - The HTTP method
 * @param {string} endpoint - The API endpoint
 * @param {object} body - The request body, optional
 * @param {object} asyncStore - The async store
 *
 * @returns {object} - The response object with status, message and data
 *
 * @example const { status, message, data } = await request('GET', 'location');
 *
 * @description The request function is a wrapper for the fetch API.
 * It takes the HTTP method, the API endpoint and the request body as arguments.
 * It returns an object with the status, message and data properties.
 */
export const request = async (method, endpoint, body = null | any, asyncStore) => {
	console.log('asyncStore', asyncStore);
	console.log('method', method);
	console.log('endpoint', endpoint);
	console.log('body', body);
	// Strange bug where PATCH method doesn't work with FormData
	if (method === 'PATCH') {
		body = new URLSearchParams(body);
	}

	let token = null;

	// Retrieve the user data from the async store
	const user = await asyncStore.get();
	console.log('user', user);
	if (user && user.token) {
		token = user.token;
	}

	const url = URL + endpoint;
	const options = {
		method,
		headers: {
			Authorization: `Bearer ${token}`,
			Accept: 'application/json'
		},
		body
	};

	try {
		const response = await fetch(url, options);
		const responseData = await response.json();
		if (!response.ok) {
			throw new Error(responseData.message || 'Something went wrong');
		}
		return {
			status: response.status,
			message: responseData.message,
			data: responseData.data
		};
	} catch (error) {
		return {
			status: 500,
			message: error.message,
			data: { data: 'no data' },
			error
		};
	}
};

//
//
// Managing fetch promises with a store • REPL • Svelte
// https://svelte.dev/repl/dcc69ccad6c341e8b75ee38c3eac1524?version=4.2.8

// export function initialValue() {
// 	return [];
// }

// export function makeListStore(endpoint) {
// 	const initial = initialValue();
// 	const store = readable(initial, makeSubscribe(endpoint));
// 	return store;
// }

// function unsubscribe() {}

// function makeSubscribe(endpoint) {
// 	// console.log('endpoint', endpoint);
// 	return (set) => {
// 		fetchListData(endpoint, set);
// 		return unsubscribe;
// 	};
// }

// async function fetchListData(endpoint, set) {
// 	try {
// 		const { status, data, message } = await request('GET', endpoint, null, get(userST).token);
// 		if (status === 200) {
// 			// console.log('data', data);
// 			set(data);
// 		} else {
// 			throw new Error(message);
// 		}
// 	} catch (error) {
// 		data.error = error;
// 		set(data);
// 	}
// }

// https://github.com/joshnuss/svelte-http-store
// export function httpStore(url, options = {}) {
// 	let set;
// 	let data, message;

// 	const initialValue = { loading: true };
// 	const store = readable(initialValue, (setter) => {
// 		set = setter;
// 	});

// 	get(store);

// 	store.fetch = async (url, options = {}) => {
// 		return new Promise((resolve, reject) => {
// 			set({ loading: true, ...data });
// 			request('GET', url, null, get(userST).token)
// 				.then((res) => {
// 					if (res.status === 200) {
// 						data = res.data;
// 						set({ loading: false, ...data });
// 						resolve(data);
// 					} else {
// 						throw new Error(message);
// 					}
// 				})
// 				.catch((error) => reject(error));
// 		});
// 	};

// 	store.refresh = () => {
// 		return new Promise((resolve, reject) => {
// 			store
// 				.fetch(url, options)
// 				.then(() => resolve(store.getData()))
// 				.catch((error) => reject(error));
// 		});
// 	};
// 	store.getData = () => data;

// 	store.refresh();

// 	return store;
// }
