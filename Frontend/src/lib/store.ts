import { writable } from 'svelte/store';
import { asyncable } from './asyncStore';
import { goto } from '$app/navigation';
import { browser } from '$app/environment';
import { base as baseUrl } from '$app/paths';
export const userST: UserStore = writable({});
export const tokenStore: UserStore = writable({});
// export const tokenStore = writable('');

const URL = 'https://public.test/api/';

interface ResponseData<T> {
	status: number;
	message: string;
	data: T;
}

/**
 * USER store
 *
 * @description The user store is an asyncable store that stores the user data.
 * It is used to store the user data in the local storage.
 *
 */

export function userStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

/**
 * BOOKING store
 *
 * @description The booking store is an asyncable store that stores the booking data.
 * It is used to store the booking data in the local storage.
 *
 */
export function bookingStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

/**
 * MEMBER store
 *
 * @description The member store is an asyncable store that stores the member data.
 * It is used to store the member data in the local storage.
 *
 */
export function memberStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

/**
 * STAFF store
 *
 * @description The staff store is an asyncable store that stores the staff data.
 * It is used to store the staff data in the local storage.
 *
 */
export function staffStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

/**
 * LOCATION store
 *
 * @description The location store is an asyncable store that stores the location data.
 * It is used to store the location data in the local storage.
 *
 */
export function locationStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

/**
 * LOCATION LIST store
 *
 * @description The location list store is an asyncable store that stores the location data. list
 * It is used to store the location data in the local storage. list
 *
 */
export function locationListStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

/**
 * MEMBER LIST store
 *
 * @description The member list store is an asyncable store that stores the member data. list
 * It is used to store the member data in the local storage. list
 *
 */
export function memberListStore(method, endpoint, body: unknown = null) {
	return asyncable(async () => {
		return await request(method, endpoint, body);
	});
}

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
// export const request = async (method, endpoint, body = null | FormData, asyncStore) => {

export const request = async <T>(
	method: string,
	endpoint: string,
	body: object | FormData | null = null
): Promise<ResponseData<T>> => {
	// console.log('\nREQUEST', { endpoint, method, body });

	// endpoints no auth required
	const noAuthEndpoints = ['login', 'register', 'password/email', 'password/reset'];

	// Strange bug where PATCH method doesn't work with FormData
	if (method === 'PATCH') {
		body = new URLSearchParams(body);
	}

	// const token = tokenStore.get();
	let token;
	tokenStore.subscribe((value) => {
		token = value.token;
		// console.log('Current value of tokenStore:', token);
	});

	if (!noAuthEndpoints.includes(endpoint) && !token && browser) {
		goto(`${baseUrl}/login`);
		return {
			status: 404,
			message: 'Auth required',
			data: { data: 'no data' }
		};
	}

	const options = {
		method,
		headers: {
			Authorization: `Bearer ${token}`,
			Accept: 'application/json'
		},
		body
	};

	try {
		const response = await fetch(URL + endpoint, options);
		const responseData = await response.json();
		if (!response.ok) {
			throw new Error(responseData.message || 'Something went wrong');
		}

		// Set the data to the provided store
		// dataStore.set(responseData.data);

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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

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
