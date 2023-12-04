import { userST } from '$lib/store';
import { get } from 'svelte/store';
const URL = 'https://public.test/api/';
import { browser } from '$app/environment';
// const URL = 'https://dev.rasal.de/booking/API/public/login/';

export const request = async (method, endpoint, body: unknown = null, externalToken = null) => {
	// strange bug where PATCH method doesn't work with FormData
	if (method === 'PATCH') {
		body = new URLSearchParams(body);
	}

	let token = null;
	if (externalToken) {
		token = externalToken;
	} else if (browser && window.localStorage.getItem('RB_user')) {
		const user = JSON.parse(window.localStorage.getItem('RB_user'));
		token = user ? user.token : null;
	} else {
		const user = // here shoud be the async store
			(token = user ? user.token : null);
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
			error: error
		};
	}
};
