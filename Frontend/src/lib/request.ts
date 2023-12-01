import { tokenST } from '$lib/store';
import { get } from 'svelte/store';
const URL = 'https://public.test/api/';
// const URL = 'https://dev.rasal.de/booking/API/public/login/';

export async function requestOLD(method, url, body = null) {
	return fetch(URL + url, {
		method,
		headers: {
			Authorization: `Bearer ${get(tokenST)}`,
			'Content-Type': 'application/json'
		},
		body
	})
		.then((response) => {
			if (response.status === 200) {
				return response.json();
			} else {
				return response.json();
			}
		})
		.catch((err) => {
			throw err;
		});
}

export const request = async (method, endpoint, body: unknown = null, externalToken = '') => {
	// strange bug where PATCH method doesn't work with FormData
	if (method === 'PATCH') {
		body = new URLSearchParams(body);
	}

	const token = externalToken ? externalToken : get(tokenST);
	// show token
	// console.log('endpoint', endpoint);
	// console.log('get(tokenST)', token);

	const url = URL + endpoint;
	const options = {
		method,
		headers: {
			Authorization: `Bearer ${token}`,
			Accept: 'application/json'
			// 'Content-Type': 'application/json'
			// 'Content-Type': 'multipart/form-data'
		},
		// credentials: 'include',
		body
	};

	try {
		// console.info('\nurl', url);
		// console.time('request');
		const response = await fetch(url, options);
		// console.timeEnd('request');
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
			error: error
		};
	}
};
