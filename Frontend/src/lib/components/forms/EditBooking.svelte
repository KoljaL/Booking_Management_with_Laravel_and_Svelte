<script lang="ts">
	import type { ModelBooking } from '$lib/types';
	import { request } from '$lib/request';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import Modal from '$lib/components/Modal.svelte';

	export let id = '';
	export let closeModal: () => void;

	let showModal: boolean = false;
	let form: HTMLFormElement;
	let formMessage: string = '';
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

	//
	// Functions
	//

	/**
	 * Get booking data from database.
	 */
	async function getBookingData() {
		try {
			const { status, message, data } = await request('GET', 'booking/' + id);
			if (status === 200) {
				booking = data as ModelBooking;
				showModal = true;
				console.log('booking', booking);
			} else {
				console.error('Booking data loading failed', message);
				formMessage = 'Error: ' + message;
				showModal = true;
			}
		} catch (error: any) {
			console.error('Error:', error.message);
		}
	}

	/**
	 * Store form data in database.
	 */
	async function storeBookingData() {
		if (!form) return;
		console.log('storeBookingData', booking);
		const formData = new FormData(form)!;
		const { status, message, data } = await request('PUT', 'booking/' + id, formData);
		if (status === 201) {
			booking = data as ModelBooking;
			// console.log('booking', data);
			onClose();
		} else {
			console.error('TableData loading failed', message);
		}
	}

	/**
	 * Close modal and call parent callback, which will close the modal there.
	 */
	function onClose() {
		showModal = false;
		closeModal();
	}
</script>

<Modal isOpen={showModal} {onClose}>
	<span slot="header">Booking: {booking.id}</span>
	{#if formMessage}
		<p>{formMessage}</p>
	{/if}
	<form bind:this={form}></form>
	<div slot="footer">
		<button type="button" on:click={storeBookingData}>Save</button>
	</div>

	<div class="jsonWrapper">
		<JsonView json={booking} />
	</div>
</Modal>

<style>
</style>
