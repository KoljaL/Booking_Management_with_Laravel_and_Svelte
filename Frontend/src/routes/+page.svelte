<script lang="ts">
	import { tokenST, userST } from '$lib/store';
	import { goto } from '$app/navigation';

	/**
	 * Login with the selected user
	 */
	async function login(e: Event) {
		e.preventDefault();
		const target = e.target as HTMLButtonElement;
		const form = target.closest('form') as HTMLFormElement;
		const formData = new FormData(form);
		// console.log(formData);
		const URL = 'https://public.test/api/login/';
		// const URL = 'https://dev.rasal.de/booking/API/public/login/';
		const response = await fetch(URL, {
			method: 'POST',
			headers: {
				Accept: 'application/json'
			},
			body: formData
		});
		const data = await response.json();
		console.log(data);
		if (data.token) {
			tokenST.set(data.token);
			userST.set(data.user);
		}
		if (data.user.role === 'member') {
			goto('/member');
		} else if (data.user.role === 'staff') {
			goto('/staff');
		}
	}
</script>

<h1>Landingpage for Booking Management</h1>
<h2>You have to be logged in to manage your bookings.</h2>

<form action="login" method="post">
	<select name="email" on:change={(e) => login(e)}>
		<option value="">Select a user</option>
		<option value="admin@example.com">admin</option>
		<option value="staff@example.com">staff</option>
		<option value="member@example.com">member</option>
	</select>
	<input type="hidden" name="password" value="password" required />
</form>

<h2>Roles</h2>
<p>
	The admin can manage Bookings, Staff and Member. <br />
	The staff can manage Bookings and Member of the same location. <br />
	The member can manage his Bookings.
</p>

<style>
	form {
		display: flex;
		flex-direction: column;
		width: 300px;
	}
</style>
