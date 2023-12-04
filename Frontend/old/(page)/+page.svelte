<script lang="ts">
	// import {  userST } from '$lib/store';
	// import { goto } from '$app/navigation';

	// let store = true;
	// /**
	//  * Login with the selected user
	//  */
	// async function login(user: string) {
	// 	const formData = new FormData();
	// 	formData.append('email', user + '@example.com');
	// 	formData.append('password', 'password');
	// 	const URL = 'https://public.test/api/login/';
	// 	// const URL = 'https://dev.rasal.de/booking/API/public/login/';

	// 	const response = await fetch(URL, {
	// 		method: 'POST',
	// 		headers: {
	// 			Accept: 'application/json'
	// 		},
	// 		body: formData
	// 	});
	// 	const data = await response.json();
	// 	// console.log(data);
	// 	if (data.token) {
	// 		userST.set(data.user);
	// 		if (store) {
	// 			localStorage.setItem('RB_token', data.token);
	// 			localStorage.setItem('RB_user', JSON.stringify(data.user));
	// 		}
	// 	}
	// 	if (data.user.role === 'member') {
	// 		goto('/member');
	// 	} else if (data.user.role === 'staff') {
	// 		goto('/staff');
	// 	}
	// }
</script>

<h1>Landingpage for Booking Management</h1>

<h2>Roles</h2>
<p>
	Admin can manage Locations, Staff and Member. <br />
	Staff can manage Bookings and Member of the same location. <br />
	Member can manage his Bookings.
</p>

<!-- <div class="buttons">
	<button on:click={() => login('admin')}>Admin</button>
	<button on:click={() => login('staff')}>Staff</button>
	<button on:click={() => login('member')}>Member</button>
</div>
<div class="store">
	<label>
		Store in local storage
		<input type="checkbox" bind:checked={store} />
	</label>
</div> -->

<h2>
	<a href="login">Login Page</a>
</h2>

<style>
	.buttons {
		display: flex;
		gap: 1rem;
	}
	.store {
		margin-top: 1rem;
	}
	p {
		margin-inline: 0.5rem;
		line-height: 1.5rem;
	}
	h2 {
		margin: 0;
		padding-top: 2rem;
	}

	a {
		color: var(--blue);
		text-shadow: 0 0 1px 1px var(--colors-gray6);
		text-shadow: 0 0px 0.5px #000000d9;
		text-decoration: none;
		transition: text-shadow 0.2s ease-in-out;
	}

	a:hover {
		text-shadow: 0 0px 0.5px #00000099;
	}
</style>
