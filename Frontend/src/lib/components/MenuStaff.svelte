<script lang="ts">
	import type { ModelMenu, Endpoint } from '$lib/types';
	import { getParamFromUrl, setParamToUrl } from '$lib/utils';
	import { userST } from '$lib/store';

	export let endpoint: Endpoint;
	// $: console.log('MenuStaff', endpoint);
	// let activeSlug = 'member';
	const menuItems: ModelMenu[] = [
		{
			name: 'Location',
			slug: 'location',
			is_admin: true
		},
		{
			name: 'Staff',
			slug: 'staff',
			is_admin: true
		},
		{
			name: 'Member',
			slug: 'member',
			is_admin: false
		},
		{
			name: 'Booking',
			slug: 'booking',
			is_admin: false
		}
	];

	function menuClick(slug: string) {
		setParamToUrl('e', slug);
	}
	// menuClick(item.slug);

	// function hashChanged() {
	// const urlParams = new URLSearchParams(window.location.search);
	// console.log('hashChanged', urlParams);
	// const hash = window.location.hash.replace('#', '').split('/')[0] as Endpoint;
	// if (hash) {
	// 	endpoint = hash;
	// }
	// endpoint = getParamFromUrl('e') as Endpoint;
	// console.log('hashChanged', endpoint);
	// }
	// $: console.log('MenuStaff', endpoint);
</script>

<!-- <svelte:window on:hashchange={hashChanged} /> -->

<nav>
	<ul>
		{#each menuItems as item}
			{#if $userST.is_admin || !item.is_admin}
				<li>
					<button
						on:click={() => {
							setParamToUrl('e', item.slug);
						}}
						class:active={endpoint === item.slug}
					>
						{item.name}
					</button>
				</li>
			{/if}
		{/each}
	</ul>
</nav>

<style>
	nav {
		height: var(--menu-height);
		display: flex;
		margin-bottom: 1rem;
	}
	ul {
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: 2rem;
		padding: 0;
		list-style: none;
		border: 1px solid black;
		border-radius: 0.5rem;
	}
	li {
		height: 2rem;
		margin: 0 1rem;
	}
	button {
		background-color: transparent;
		border: none;
		font-size: 1rem;
		font-weight: bold;
		cursor: pointer;
		padding: 0;
		line-height: 2rem;
		transition: color 0.2s ease-in-out;
	}
	button.active {
		color: var(--blue);
	}
	button:hover {
		color: var(--blue);
	}
</style>
