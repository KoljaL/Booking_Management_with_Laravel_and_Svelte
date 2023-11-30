<script lang="ts">
	import MenuStaff from '$lib/components/MenuStaff.svelte';
	import { beforeNavigate, afterNavigate, onNavigate } from '$app/navigation';
	import type { Endpoint } from '$lib/types';
	let endpoint: Endpoint = '';
	// beforeNavigate(async (to) => {
	// 	const dataTable = document.querySelector('.tableWrapper');

	// 	if (dataTable) {
	// 		dataTable.classList.add('fadeOut');
	// 	}
	// 	// console.log('beforeNavigate', to);
	// 	return await new Promise((resolve) => setTimeout(resolve, 1500));
	// });

	// afterNavigate((to) => {
	// console.log('afterNavigate', to);
	// const dataTable = document.querySelector('.tableWrapper');
	// if (dataTable) {
	// 	dataTable.classList.remove('fadeOut');
	// }
	// });

	onNavigate((navigation) => {
		// remove /auth/staff/ from route
		// and set endpoint for menu
		const route = navigation.to?.route.id;
		endpoint = route?.slice(14) as Endpoint;
		// @ts-ignore
		// if (!document.startViewTransition) {
		// console.log('no transition');
		// return;
		// }

		// return new Promise((resolve) => {
		// 	// @ts-ignore
		// 	document.startViewTransition(async () => {
		// 		resolve();
		// 		await navigation.complete;
		// 	});
		// });
	});

	// $: console.log('showModal', showModal);
	// $: console.log('endpoint', endpoint);
</script>

<MenuStaff {endpoint} />
<slot />

<style>
	/* :gloabl(.tableWrapper) {
		opacity: 1;
		transition: opacity 1.5s ease-in-out;
	}

	:global(.fadeOut) {
		opacity: 0;
	} */

	/* 
	@keyframes fade-in {
		from {
			opacity: 0;
		}
	}

	@keyframes fade-out {
		to {
			opacity: 0;
		}
	}

	@keyframes slide-from-right {
		from {
			transform: translateX(30px);
		}
	}

	@keyframes slide-to-left {
		to {
			transform: translateX(-30px);
		}
	}

	:root::view-transition-old(root) {
		animation:
			90ms cubic-bezier(0.4, 0, 1, 1) both fade-out,
			300ms cubic-bezier(0.4, 0, 0.2, 1) both slide-to-left;
	}

	:root::view-transition-new(root) {
		animation:
			210ms cubic-bezier(0, 0, 0.2, 1) 90ms both fade-in,
			300ms cubic-bezier(0.4, 0, 0.2, 1) both slide-from-right;
	} */
</style>
