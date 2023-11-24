<script lang="ts">
	import '$lib/style/app.css';
	import { tokenST, userST } from '$lib/store';
	import HeaderStaff from '$lib/components/layout/HeaderStaff.svelte';
	import { goto } from '$app/navigation';
	import { browser } from '$app/environment';

	if (!$tokenST && browser) {
		console.log('no token');
		// goto('./login?noToken=true');
		// try to get token from local storage
		const token = localStorage.getItem('RB_token');
		const user = localStorage.getItem('RB_user');

		if (token && user) {
			const userObj = JSON.parse(user);
			userST.set(userObj);
			tokenST.set(token);
			if (userObj.role === 'member') {
				goto('/member');
			} else if (userObj.role === 'staff') {
				goto('/staff');
			}
		} else {
			goto('./login?noToken=true');
		}
	}
</script>

<HeaderStaff />

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
</style>
