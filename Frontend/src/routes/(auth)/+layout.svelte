<script lang="ts">
	import { tokenST, userST } from '$lib/store';
	import { goto } from '$app/navigation';

	if (!$tokenST) {
		console.log('no token');
		// goto('./login?noToken=true');
	}

	function logout() {
		tokenST.set('');
		userST.set({});
		window.location.href = './';
	}
	// console.log($tokenST);
</script>

<header>
	<h2>Hallo {$userST.name}</h2>
	<p>Role: {$userST.role}</p>
	<p>Admin: {$userST.is_admin}</p>
	<p>Location: {$userST.location.city}</p>
	<button on:click={logout}>Logout</button>
</header>

<main>
	<slot />
</main>

<style>
	:global(:root) {
		--header-height: 3rem;
		--menu-height: 3rem;
		--footer-height: 3rem;
		--main-height: calc(100vh - var(--header-height) - var(--footer-height));
		--page-width: 800px;
	}
	:global(body) {
		margin: 0;
		padding: 0;
		min-height: 100vh;
	}
	:global(#SvelteKit) {
		min-height: 100vh;
		max-width: var(--page-width);
		margin: 0 auto;
		/* display: grid; */
		/* grid-template-rows: auto 1fr auto; */
		display: flex;
		flex-direction: column;
	}

	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: var(--header-height);
	}

	main {
		/* flex: 1 1 auto; */
		/* max-height: 100%; */
		/* align-self: stretch; */
		/* overflow: auto; */
		height: var(--main-height);
	}
</style>
