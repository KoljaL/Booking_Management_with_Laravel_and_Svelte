<script lang="ts">
	import Rocket from '$lib/icons/AnimatedRocket.svelte';
	import { userST } from '$lib/store';
	import { browser } from '$app/environment';

	function logout() {
		userST.set({});
		localStorage.removeItem('RB_user');
		window.location.href = 'http://localhost:5173/login/';
	}
</script>

<header>
	<div class="left">
		<Rocket />
		<!-- <h2>Rocket Booking</h2> -->
	</div>
	<div class="center">
		{#if browser}
			<!-- <p>{$userST.role}</p> -->
			<!-- <p>{$userST.is_admin}</p> -->
			<!-- <p>Location: {$userST.location.city}</p> -->
		{/if}
	</div>
	<div class="right">
		{#if browser}
			<h3>{$userST.name}</h3>
		{/if}
		<button on:click={logout}>Logout</button>
	</div>
</header>

<style>
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: var(--header-height);
		margin-bottom: 0.5rem;
	}
	.left {
		display: flex;
		align-items: end;
		flex-direction: row;
		gap: 0.5rem;
	}

	.center {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		margin-top: 0.4rem;
	}
	.right {
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}
</style>
