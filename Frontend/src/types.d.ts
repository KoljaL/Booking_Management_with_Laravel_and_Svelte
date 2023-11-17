declare module '$env/static/public' {
	export const PUBLIC_PATH: string;
}

export type Endpoint = 'location' | 'booking' | 'staff' | 'member';

export interface Response {
	message: string;
	data: unknown;
	debug: unknown;
	member?: unknown;
	staff?: unknown;
	booking?: unknown;
	location?: unknown;
}

export interface UserStore {
	id: number;
	is_admin: boolean;
	location: unknown;
	name: string;
	role: string;
}
