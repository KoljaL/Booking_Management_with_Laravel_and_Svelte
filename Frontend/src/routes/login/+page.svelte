<script lang="ts">
	import { goto } from '$app/navigation';
	import { request, userStore } from '$lib/store';
	// import { request } from '$lib/request';

	let form: HTMLFormElement;
	let valid = false;
	let email: string = '';
	let password: string = '';
	let errorMessage: string = '';

	async function login() {
		const formData = new FormData(form);
		const { status, data, message } = await request('POST', 'login', formData, userStore as any);
		// console.log('login', status, data, message);
		if (status === 201) {
			data.timestamp = Date.now();
			if (data.role === 'member') {
				goto('/member');
			} else if (data.role === 'staff') {
				goto('/staff');
			}
		} else {
			console.log('login failed', message);
			errorMessage = message;
		}
	}

	function check() {
		const isEmailValid = validateEmail(email);
		valid = isEmailValid && password.length > 5;
	}

	function validateEmail(email: string) {
		const re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	$: if (valid) {
		setTimeout(() => {
			login();
		}, 200);
	}

	function loginAs(user: string) {
		email = user + '@example.com';
		password = 'password';
		check();
	}
</script>

<div class="loginWrapper">
	<div class="buttons">
		<button on:click={() => loginAs('admin')}>Admin</button>
		<button on:click={() => loginAs('staff')}>Staff</button>
		<button on:click={() => loginAs('member')}>Member</button>
	</div>
	<form class="shadow" on:submit|preventDefault={login} bind:this={form}>
		<label>
			Email
			<input
				type="email"
				name="email"
				required
				on:input={check}
				bind:value={email}
				autocomplete="off"
			/>
		</label>

		<label>
			Password
			<input
				type="text"
				name="password"
				required
				on:input={check}
				bind:value={password}
				autocomplete="off"
			/>
		</label>

		{#if errorMessage}
			<p>{errorMessage}</p>
		{/if}

		<button type="submit" disabled={!valid}>Login</button>
	</form>
</div>

<style>
	.loginWrapper {
		display: flex;
		flex-direction: column;
		justify-content: start;
		align-items: center;
		margin-top: 5rem;
		height: 100vh;
	}

	form {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		padding: 1rem;
		margin-top: 3rem;
		border-radius: var(--border-radius-m);
		max-width: 235px;
	}

	label {
		display: flex;
		flex-direction: column;
		justify-content: center;
		/* align-items: center; */
	}

	input {
		margin-block: 0.5rem;
		padding: 0.5rem;
	}

	button {
		margin: 0.5rem;
		padding: 0.5rem;
	}
	button:disabled {
		filter: grayscale(1);
		opacity: 0.5;
	}
	p {
		color: var(--red);
	}
</style>
