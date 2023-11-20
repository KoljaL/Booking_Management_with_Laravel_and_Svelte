<script lang="ts">
	import type { ModelMember } from '$lib/types';
	import { request, requestNEW } from '$lib/request';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import Modal from '$lib/components/Modal.svelte';

	export let id = '';
	export let callback: () => void;

	let showModal: boolean = false;
	let showBookingModal: boolean = false;
	let bookingForm: any = null;
	let bookingId: number;
	let form: HTMLFormElement;
	let member: ModelMember = {
		id: 0,
		name: '',
		email: '',
		phone: '',
		created_at: '',
		updated_at: '',
		deleted_at: '',
		user_id: 0,
		location_id: 0,
		staff_id: 0,
		jc_number: '',
		max_booking: 0,
		active: 0,
		bookings: []
	};

	getMemberData();

	async function getMemberData() {
		try {
			const { status, message, data } = await requestNEW('GET', 'member/' + id);
			if (status === 200) {
				member = data as ModelMember;
				console.log('message', message);
				console.log('member', member);
				console.log('data', data);
				showModal = true;
			} else {
				console.error('Member data loading failed', message);
			}
		} catch (error: any) {
			console.error('Error:', error.message);
		}
	}

	async function storeMemberData() {
		if (!form) return;
		console.log('storeMemberData', member);
		const formData = new FormData(form)!;
		const { status, message, data } = await requestNEW('PUT', 'member/' + id, formData);
		if (status === 201) {
			member = data as ModelMember;
			console.log('member', data);
			closeModal();
		} else {
			console.error('TableData loading failed', message);
		}
	}

	function closeModal() {
		showModal = false;
		callback();
	}

	async function openBooking(id: number) {
		console.log('openBooking', id);
		const bookingForm = (await import('$lib/components/forms/EditBooking.svelte')).default;
		console.log('bookingForm', bookingForm);
		showBookingModal = true;
		bookingId = id;
	}

	$: console.log('showBookingModal', showBookingModal);
</script>

{#if showBookingModal}
	xx
	<svelte:component this={bookingForm} id={bookingId} callback={close}></svelte:component>
{/if}
<Modal isOpen={showModal} onClose={closeModal}>
	<span slot="header">Member: {member.name}</span>
	<form bind:this={form}>
		<label>
			Name:
			<input type="text" name="name" value={member.name} />
		</label>
		<label>
			Email:
			<input type="text" name="email" value={member.email} />
		</label>
		<label>
			Phone:
			<input type="text" name="phone" value={member.phone} />
		</label>
		<label>
			JC Number:
			<input type="text" name="jc_number" value={member.jc_number} />
		</label>
		<label>
			Max Booking:
			<input type="text" name="max_booking" value={member.max_booking} />
		</label>
		<label>
			Active:
			<input type="text" name="active" value={member.active} />
		</label>
	</form>
	<div slot="footer">
		<button type="button" on:click={storeMemberData}>Save</button>
	</div>

	<details open>
		<summary>Bookings </summary>
		<ul>
			{#each member.bookings as booking}
				<!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
				<li on:click={() => openBooking(booking.id)} on:keypress={() => openBooking(booking.id)}>
					{booking.date} - {booking.time}
				</li>
			{/each}
		</ul>
	</details>

	<!-- <div class="jsonWrapper">
		<JsonView json={member} />
	</div> -->
</Modal>

<style>
	form {
		display: flex;
		flex-direction: column;
	}
	label {
		display: flex;
		flex-direction: column;
	}
	input {
		margin-bottom: 1rem;
	}

	details {
		margin-top: 1rem;
	}
	summary {
		margin-bottom: 1rem;
		font-weight: bold;
	}
	ul {
		padding-left: 0;
		list-style: none;
	}
	li {
		margin-bottom: 0.5rem;
	}
	.jsonWrapper {
		max-height: 200px;
		overflow-y: scroll;
	}
</style>
