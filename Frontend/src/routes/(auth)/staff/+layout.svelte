<script lang="ts">
	import MenuStaff from '$lib/components/MenuStaff.svelte';
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

<HeaderStaff />

<MenuStaff {endpoint} />

<slot />

<style>
</style>
