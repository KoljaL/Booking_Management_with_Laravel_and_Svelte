<script lang="ts">
	import MenuStaff from '$lib/components/layout/MenuStaff.svelte';
	import { beforeNavigate, afterNavigate, onNavigate } from '$app/navigation';
	import { page } from '$app/stores';
	import HeaderStaff from '$lib/components/layout/HeaderStaff.svelte';
	import type { Endpoint } from '$lib/types';
	import { onMount } from 'svelte';
	let endpoint: Endpoint = '';

	onMount(() => {
		endpoint = $page?.route?.id
			? ($page.route.id.replace('/(auth)/staff', '').replace('/', '') as Endpoint)
			: '';
	});

	onNavigate((navigation) => {
		const route = navigation.to?.route?.id;
		endpoint = route?.replace('/(auth)/staff', '').replace('/', '') as Endpoint;
	});

	// $: console.log('endpoint', endpoint);
</script>

<!-- header -->
<HeaderStaff {endpoint} />

<!-- nav -->
<!-- <MenuStaff {endpoint} /> -->

<main>
	<slot />
</main>

<footer></footer>

<style>
	:global(:root) {
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
		height: 100vh;
		max-width: var(--page-width);
		margin: 0 auto;
		display: grid;
		grid-template-rows: auto auto 1fr auto;
	}

	main {
		overflow-y: auto;
		position: relative;
		padding-inline: 0.25rem;
	}

	footer {
		display: flex;
		justify-content: center;
		align-items: center;
		height: 1rem;
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
