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

export const request = async (method, endpoint, body: unknown = null) => {
	const options = {
		method,
		headers: {
			Authorization: `Bearer ${get(tokenST)}`,
			'Content-Type': 'application/json'
		},
		body: body ? JSON.stringify(body) : null
	};

	try {
		const response = await fetch(URL + endpoint, options);
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
			data: null,
			error: error
		};
	}
};
