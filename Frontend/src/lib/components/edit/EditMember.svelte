<script lang="ts">
	import type { ModelMember } from '$lib/types';
	import { request } from '$lib/request';
	import { delay } from '$lib/utils';
	import JsonView from '$lib/components/debug/JsonHighlight.svelte';
	import Modal from '$lib/components/Modal.svelte';
	import EditBooking from '$lib/components/edit/EditBooking.svelte';

	export let id: number = 0;
	export let closeModal: () => void;

	let showModal: boolean = false;
	let showBookingModal: boolean = false;
	let bookingId: number;
	let form: HTMLFormElement;
	let modalTitle: string = 'Add Member';
	let member: ModelMember;
	const emptyMember: ModelMember = {
		id: 0,
		user_id: 0,
		staff_id: 0,
		location_id: 0,
		name: '',
		email: '',
		phone: '',
		jc_number: '',
		max_booking: 0,
		active: 0,
		created_at: '',
		updated_at: '',
		deleted_at: '',
		bookings: []
	};

	const getMemberData = async (id: number) => {
		if (id === 0) {
			showModal = true;
			return emptyMember as ModelMember;
		}
		const { status, message, data } = await request('GET', 'member/' + id);
		if (status === 200) {
			setTimeout(() => {
				showModal = true;
			}, 0);
			modalTitle = 'Edit Member: ' + (data as ModelMember).name;
			return data as ModelMember;
		} else {
			showModal = true;
			console.error('Member data loading failed', message);
			return emptyMember as ModelMember;
		}
	};

	async function storeMemberData() {
		console.log('storeMemberData', member);
		const formData = new FormData(form);
		const { status, message, data } = await request('PUT', 'member/' + id, formData);
		if (status === 201) {
			member = data as ModelMember;
			console.log('member', data);
			closeModal();
		} else {
			console.error('TableData loading failed', message);
		}
	}

	function onClose() {
		showModal = false;
		closeModal();
	}

	function closeBookingModal() {
		delay(300);
		showBookingModal = false;
	}

	async function openBookingModal(id: number) {
		showBookingModal = true;
		bookingId = id;
	}

	// $: console.log('id', id);
	// $: console.log('member', member, id);
	// $: console.log('showModal', showModal);
</script>

{#await getMemberData(id) then member}
	<Modal isOpen={showModal} {onClose}>
		<span slot="header">{modalTitle}</span>
		<form bind:this={form}>
			<label>
				Name
				<input type="text" name="name" value={member.name} />
			</label>
			<label>
				Email
				<input type="text" name="email" value={member.email} />
			</label>
			<label>
				Phone
				<input type="text" name="phone" value={member.phone} />
			</label>
			<label>
				JC Number
				<input type="text" name="jc_number" value={member.jc_number} />
			</label>
			<label>
				Max Booking
				<input type="text" name="max_booking" value={member.max_booking} />
			</label>
			<label>
				Active
				<input type="text" name="active" value={member.active} />
			</label>
		</form>
		<div slot="footer">
			<button type="button" on:click={storeMemberData}>Submit</button>
		</div>

		<details open slot="beyondFooter">
			<!-- {#if id !== 0} -->
			<summary>Bookings </summary>
			<!-- {/if} -->

			{#if member.bookings === undefined || member.bookings.length === 0}
				<p>No bookings found</p>
			{:else}
				<ul>
					{#each member.bookings as booking}
						<li>
							<button
								type="button"
								class="openBooking"
								on:click={() => openBookingModal(booking.id)}
							>
								{booking.date}
								{booking.time}
							</button>
						</li>
					{/each}
				</ul>
			{/if}
		</details>

		<!-- <div class="jsonWrapper">
		<JsonView json={member} />
	</div> -->
	</Modal>
{/await}

{#if showBookingModal}
	<Modal isOpen={showBookingModal} onClose={() => (showBookingModal = false)}>
		<EditBooking id={bookingId} closeModal={closeBookingModal} />
	</Modal>
{/if}

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
		margin-bottom: 1rem;
		margin-top: 1rem;
	}

	ul {
		padding-left: 0;
		list-style: none;
		max-height: 200px;
		overflow-y: scroll;
		display: flex;
		flex-wrap: wrap;
	}
	li {
		margin-bottom: 0.65rem;
	}

	.openBooking {
		font-feature-settings: 'tnum';
		font-variant-numeric: tabular-nums;
		white-space: nowrap;
	}

	/* button.openBooking {
		color: var(--black);
		text-decoration: none;
		font-size: 1rem;
		cursor: pointer;
		padding: 0;
		transition: color 0.2s ease-in-out;
		background-color: transparent;
		border: none;
	}
	button.openBooking:hover {
		color: var(--blue);
	} */
</style>
