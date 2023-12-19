<script lang="ts">
	import '$lib/style/app.css';
	import { tokenStore } from '$lib/store';
	import { goto } from '$app/navigation';
	import { browser } from '$app/environment';
	import { page } from '$app/stores';
	import { onMount } from 'svelte';
	const pathname = $page.url.pathname;
	const params = $page.url.search;
	let user: any = {};
	onMount(() => {
		if ($tokenStore) {
			// console.log('$tokenStore', $tokenStore);
			user = $tokenStore;
		}
		if (!$tokenStore.token && browser) {
			// console.log('no tokenStore', tokenStore);
			user = localStorage.getItem('user');
			user = JSON.parse(user);
		}

		console.log('user', user);
		if (user) {
			tokenStore.set(user);
			if (user.role === 'member') {
				goto('/member');
			} else if (user.role === 'staff') {
				goto(pathname + params);
			}
		} else {
			// goto('./login');
		}
	});
</script>

<slot />
