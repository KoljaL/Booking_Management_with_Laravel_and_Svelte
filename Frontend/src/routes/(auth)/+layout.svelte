<script lang="ts">
	import '$lib/style/app.css';
	import { tokenStore } from '$lib/store';
	import { goto } from '$app/navigation';
	import { browser } from '$app/environment';
	import { page } from '$app/stores';
	import { onMount } from 'svelte';

	const pathname = $page.url.pathname;
	const params = $page.url.search;

	onMount(() => {
		if (!$tokenStore.token && browser) {
			const user = localStorage.getItem('user');
			// console.log('tokenStore', user);
			if (user) {
				const userObj = JSON.parse(user);
				tokenStore.set(userObj);
				if (userObj.role === 'member') {
					goto('/member');
				} else if (userObj.role === 'staff') {
					goto(pathname + params);
				}
			} else {
				goto('./login');
			}
		}
	});
</script>

<main>
	<slot />
</main>

<style>
	:global(:root) {
		--header-height: 3rem;
		--menu-height: 3rem;
		--footer-height: 3rem;
		--main-height: calc(100vh - var(--header-height) - var(--footer-height));
		--page-width: 1000px;
	}
	:global(body) {
		margin: 0;
		padding: 0;
		min-height: 100vh;
		opacity: 0;
		scale: 0.9;
		animation: fadein 0.5s ease-in-out forwards;
	}
	:global(#SvelteKit) {
		min-height: 100vh;
		max-width: var(--page-width);
		margin: 0 auto;
		display: flex;
		flex-direction: column;
	}

	main {
		height: var(--main-height);
	}

	@keyframes fadein {
		from {
			opacity: 0;
			scale: 0.9;
		}
		to {
			opacity: 1;
			scale: 1;
		}
	}
</style>
