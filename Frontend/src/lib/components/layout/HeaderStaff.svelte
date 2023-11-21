<script lang="ts">
	import Rocket from '$lib/icons/Rocket.svelte';
	import { tokenST, userST } from '$lib/store';
	import { browser } from '$app/environment';

	function logout() {
		tokenST.set('');
		userST.set({});
		window.location.href = 'http://localhost:5173/';
	}
</script>

<header>
	<div class="left">
		<Rocket />
		<h2>Rocket Booking</h2>
	</div>
	<div class="center">
		{#if browser}
			<h3>{$userST.name}</h3>
			<p>{$userST.role}</p>
			<p>{$userST.is_admin}</p>
			<!-- <p>Location: {$userST.location.city}</p> -->
		{/if}
	</div>
	<div class="right">
		<button on:click={logout}>Logout</button>
	</div>
</header>

<style>
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: var(--header-height);
	}
	.left {
		display: flex;
		align-items: end;
		flex-direction: row;
		gap: 0.5rem;
	}
	.left h2 {
		margin: 0;
	}
	.left :global(svg) {
		width: 2rem;
		height: 2rem;
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
