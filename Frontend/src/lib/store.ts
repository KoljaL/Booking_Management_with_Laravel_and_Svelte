// cereate a store for the token
import { writable } from 'svelte/store';

export const tokenST = writable('');
export const userST = writable({});
