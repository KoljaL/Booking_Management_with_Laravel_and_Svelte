import { tokenST } from '$lib/store';
import { get } from 'svelte/store';
const URL = 'https://public.test/api/';
// const URL = 'https://dev.rasal.de/booking/API/public/login/';

export async function request(method, url, body = null) {
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
				return response.json().then((err) => {
					throw err;
				});
			}
		})
		.catch((err) => {
			throw err;
		});
}
