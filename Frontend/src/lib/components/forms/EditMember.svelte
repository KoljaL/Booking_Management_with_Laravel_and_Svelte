<script lang="ts">
	import type { ModelMember } from '$lib/types';
	import { request } from '$lib/request';
	import { goto } from '$app/navigation';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import Modal from '$lib/components/Modal.svelte';

	export let id: string;
	export let callback: () => void;

	let showModal: boolean = false;
	let form: HTMLFormElement;
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

	const getMemberData = async () => {
		const { status, message, data } = await request('GET', 'member/' + id);
		if (status === 200) {
			setTimeout(() => {
				showModal = true;
			}, 0);
			return data as ModelMember;
		} else {
			showModal = true;
			console.error('Member data loading failed', message);
			return emptyMember as ModelMember;
		}
	};

	async function storeMemberData() {
		if (!form) return;
		console.log('storeMemberData', member);
		const formData = new FormData(form)!;
		const { status, message, data } = await request('PUT', 'member/' + id, formData);
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
		showModal = false;
		// callback();
		await goto('/staff/?bid=' + id);
	}

	// $: console.log('member', member, id);
	// $: console.log('showModal', showModal);
</script>

{#await getMemberData()}
	<p></p>
{:then member}
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
			{#if member.bookings === undefined || member.bookings.length === 0}
				<p>No bookings found</p>
			{:else}
				<ul>
					{#each member.bookings as booking}
						<li>
							<a href="/staff/?bid={booking.id}" on:click={() => (showModal = false)}>
								{booking.date} - {booking.time} - {booking.id}
							</a>
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
		cursor: pointer;
		color: var(--black);
		margin-bottom: 1rem;
		font-weight: bold;
	}
	summary:hover {
		color: var(--blue);
	}

	ul {
		padding-left: 0;
		list-style: none;
		max-height: 200px;
		overflow-y: scroll;
	}
	li {
		margin-bottom: 0.65rem;
	}

	a {
		text-decoration: none;
		color: var(--black);
		/* letter-spacing: 0.01em; */
		/* font-stretch: extra-condensed; */
	}
	a:hover {
		text-decoration: underline;
	}
</style>
