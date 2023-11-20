<script lang="ts">
	import { userST } from '$lib/store';
	import type { ModelMenu } from '$lib/types';
	import { createEventDispatcher } from 'svelte';

	const dispatch = createEventDispatcher();

	function menuClick(slug: string) {
		dispatch('menuClick', {
			endpoint: slug
		});
		activeSlug = slug;
	}
	$: activeSlug = 'member';
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
</script>

<!-- 
on:click={() => {
	dispatch('menuClick', {
		endpoint: item.slug
	});
}} -->

<nav>
	<ul>
		{#each menuItems as item}
			{#if $userST.is_admin || !item.is_admin}
				<li>
					<button
						on:click={() => {
							menuClick(item.slug);
						}}
						class:active={activeSlug === item.slug}
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
	nav ul {
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: 2rem;
		padding: 0;
		list-style: none;
		border: 1px solid black;
		border-radius: 0.5rem;
	}
	nav ul li {
		height: 2rem;
		margin: 0 1rem;
	}
	nav ul li button {
		background-color: transparent;
		border: none;
		font-size: 1rem;
		font-weight: bold;
		cursor: pointer;
		padding: 0;
		line-height: 2rem;
		transition: color 0.2s ease-in-out;
	}
	nav ul li button.active {
		color: var(--blue);
	}
	button:hover {
		color: var(--blue);
	}
</style>
