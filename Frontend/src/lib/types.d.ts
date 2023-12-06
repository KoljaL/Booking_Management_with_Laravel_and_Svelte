declare module '$env/static/public' {
	export const PUBLIC_PATH: string;
}

export type Endpoint = 'location' | 'booking' | 'staff' | 'member' | '';

export type ModelMenu = {
	name: string;
	slug: Endpoint;
	is_admin: boolean;
	element: HTMLElement | null;
};

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
	timestamp: number;
}

export interface ModelMember {
	id: number;
	name: string;
	user_id: number;
	location_id: number;
	staff_id: number;
	phone: string;
	email: string;
	jc_number: string;
	max_booking: number;
	active: number;
	deleted_at: string;
	created_at: string;
	updated_at: string;
	bookings: ModelBooking[];
}

export interface ModelBooking {
	id: number;
	member_id: number;
	location_id: number;
	comment_member: string;
	comment_staff: string;
	date: string;
	time: string;
	slots: number;
	state: string;
	started_at: string;
	ended_at: string;
	created_at: string;
	updated_at: string;
	deleted_at: string;
	member_name: string;
	location_city: string;
}

export interface ModelLocation {
	id: number;
	city: string;
	address: string;
	opening_hour_from: number;
	opening_hour_to: number;
	opening_days: string;
	phone: string;
	email: string;
	slot_duration: number;
	max_booking: number;
	workspaces: number;
	imap_host: string;
	imap_pw: string;
	created_at: string;
	updated_at: string;
	deleted_at: string;
}

export interface ModelStaff {
	id: number;
	name: string;
	user_id: number;
	location_id: number;
	phone: string;
	is_admin: boolean;
	created_at: string;
	updated_at: string;
	deleted_at: string;
}

export type TableData = {
	id: string;
	[key: string]: string;
};

export type List = {
	[key: string]: string;
	[value: string]: string;
	// id: number;
};

export type TableColumns = {
	header: string;
	accessor: string;
	width: string;
};
