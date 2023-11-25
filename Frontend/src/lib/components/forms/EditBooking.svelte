<script lang="ts">
	import type { ModelBooking } from '$lib/types';
	import { request } from '$lib/request';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import Modal from '$lib/components/Modal.svelte';
	import EditMember from '$lib/components/forms/EditMember.svelte';
	import { delay } from '$lib/utils';
	export let id: number;
	export let closeModal: () => void;

	$: console.log('EditBooking', id);
	let showModal: boolean = false;
	let showMemberModal: boolean = false;
	let memberId: number;
	let form: HTMLFormElement;
	let formMessage: string = '';
	let modalTitle: string = 'Add Booking';
	let booking: ModelBooking;
	const emptyBooking: ModelBooking = {
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

	//
	// Functions
	//

	/**
	 * Get booking data from database.
	 */
	const getBookingData = async (id: number) => {
		if (id === 0) {
			showModal = true;
			return emptyBooking as ModelBooking;
		}
		const { status, message, data } = await request('GET', 'booking/' + id);
		if (status === 200) {
			setTimeout(() => {
				showModal = true;
			}, 0);
			console.log('data', data);
			modalTitle = 'Edit Booking: ' + (data as ModelBooking).date;
			return data as ModelBooking;
		} else {
			showModal = true;
			formMessage = 'Error: ' + message;
			console.error('Booking data loading failed', message);
			return emptyBooking as ModelBooking;
		}
	};

	/**
	 * Store form data in database.
	 */
	async function storeBookingData(id: number) {
		const formData = new FormData(form);
		let method = 'PATCH';
		let url = 'booking/' + id;
		console.log('storeBookingData', formData);
		if (id === 0) {
			method = 'POST';
			url = 'booking';
		}
		const { status, message, data } = await request(method, url, formData);
		if (status === 201) {
			booking = data as ModelBooking;
			// console.log('booking', data);
			onClose();
		} else {
			console.error('TableData loading failed', message);
		}
	}

	/**
	 * Delete booking data from database.
	 */
	async function deleteBookingData(id: number) {
		const formData = new FormData(form)!;
		const { status, message, data } = await request('DELETE', 'booking/' + id, formData);
		if (status === 200) {
			booking = data as ModelBooking;
			console.log('deleted booking', message);
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

	function closeMemberModal() {
		delay(300);
		showMemberModal = false;
	}

	async function openMemberModal(id: number) {
		// console.log('openMemberModal', id);
		showMemberModal = true;
		memberId = id;
	}
</script>

{#await getBookingData(id) then booking}
	<Modal isOpen={showModal} {onClose}>
		<span slot="header">{modalTitle}</span>
		<label class="memberLabel">
			Member
			<button type="button" class="openMember" on:click={() => openMemberModal(booking.member_id)}>
				{booking.member_name}
			</button>
		</label>
		<form bind:this={form}>
			<label>
				Date
				<input type="text" name="date" value={booking.date} />
			</label>
			<label>
				Time
				<input type="text" name="time" value={booking.time} />
			</label>
			<label>
				Slots
				<input type="text" name="slots" value={booking.slots} />
			</label>
			<label>
				Started At
				<input type="text" name="started_at" value={booking.started_at} />
			</label>
			<label>
				Ended At
				<input type="text" name="ended_at" value={booking.ended_at} />
			</label>
			<label>
				Comment Staff
				<textarea name="comment_staff">{booking.comment_staff}</textarea>
			</label>
			<label>
				<nobr>
					Comment Member <small>(read only)</small>
				</nobr>
				<textarea name="comment_member" disabled>{booking.comment_member}</textarea>
			</label>
			<input type="hidden" name="id" value={booking.id} />
		</form>

		<!-- <div slot="footer"> -->
		<svelte:fragment slot="footer">
			<JsonView json={booking} />
			<button type="button" on:click={() => storeBookingData(booking.id)}>Submit</button>
			<button type="button" on:click={() => deleteBookingData(booking.id)}>Delete</button>
			<!-- </div> -->
		</svelte:fragment>
		<div slot="beyondFooter">
			{#if formMessage}
				<p>{formMessage}</p>
			{/if}
		</div>
	</Modal>
{:catch error}
	<p>{error.message}</p>
{/await}

{#if showMemberModal}
	<Modal isOpen={showMemberModal} onClose={() => (showMemberModal = false)}>
		<EditMember id={memberId} closeModal={closeMemberModal} />
	</Modal>
{/if}

<style>
	button.openMember {
		color: var(--black);
		text-decoration: none;
		font-size: 1rem;
		cursor: pointer;
		padding: 0;
		transition: color 0.2s ease-in-out;
		background-color: transparent;
		border: none;
	}
	button.openMember:hover {
		color: var(--blue);
	}

	form {
		display: flex;
		flex-direction: column;
	}
	label {
		display: flex;
		font-size: 1rem;
		flex-direction: column;
	}
	input {
		font-size: 1rem;
		margin-bottom: 1rem;
	}
	textarea {
		font-size: 1rem;
		margin-bottom: 1rem;
	}

	.memberLabel {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: start;
		gap: 1rem;
		margin-bottom: 1rem;
	}
</style>
