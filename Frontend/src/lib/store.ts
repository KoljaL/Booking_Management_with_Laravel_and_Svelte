// cereate a store for the token
import { writable } from 'svelte/store';
import type { Writable } from 'svelte/store';
import type { UserStore } from '$types';

export const tokenST: Writable<string> = writable('');
export const userST: UserStore = writable({});
