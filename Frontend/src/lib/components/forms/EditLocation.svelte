<script lang="ts">
	import type { ModelBooking } from '$lib/types';
	import { request } from '$lib/request';
	import JsonView from '$lib/components/debug/JsonHighlight.svelte';
	import Modal from '$lib/components/Modal.svelte';

	export let id: number;
	export let callback: () => void;

	let showModal: boolean = false;
	let form: HTMLFormElement;
	let booking: ModelBooking = {
		id: 0,
		date: '',
		time: '',
		created_at: '',
		updated_at: '',
		deleted_at: '',
		location_id: 0,
		member_id: 0,
		slots: 0,
		comment_member: '',
		comment_staff: '',
		state: '',
		started_at: '',
		ended_at: '',
		member_name: '',
		location_city: ''
	};

	getBookingData();

	async function getBookingData() {
		try {
			const { status, message, data } = await request('GET', 'booking/' + id);
			if (status === 200) {
				booking = data as ModelBooking;
				showModal = true;
				console.log('booking', booking);
			} else {
				console.error('Booking data loading failed', message);
			}
		} catch (error: any) {
			console.error('Error:', error.message);
		}
	}

	async function storeBookingData() {
		if (!form) return;
		console.log('storeBookingData', booking);
		const formData = new FormData(form)!;
		const { status, message, data } = await request('PUT', 'booking/' + id, formData);
		if (status === 201) {
			booking = data as ModelBooking;
			console.log('booking', data);
			closeModal();
		} else {
			console.error('TableData loading failed', message);
		}
	}

	function closeModal() {
		showModal = false;
		callback();
	}
</script>

<Modal isOpen={showModal} onClose={closeModal}>
	<span slot="header">Booking: {booking.id}</span>
	<form bind:this={form}></form>
	<div slot="footer">
		<button type="button" on:click={storeBookingData}>Save</button>
	</div>

	<div class="jsonWrapper">
		<JsonView json={booking} />
	</div>
</Modal>

<style>
	.jsonWrapper {
		max-height: 200px;
		overflow-y: scroll;
	}
</style>
