<script lang="ts">
	import { userST } from '$lib/store';
	import type { ModelMenu } from '$lib/types';
	import { createEventDispatcher } from 'svelte';

	const dispatch = createEventDispatcher();
	export let endpoint: string = 'location';
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

<nav>
	<ul>
		{#each menuItems as item}
			{#if $userST.is_admin || !item.is_admin}
				<li>
					<button
						on:click={() => {
							dispatch('menuClick', {
								endpoint: item.slug
							});
						}}
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
	}
	nav ul {
		display: flex;
		justify-content: space-between;
		padding: 0;
		list-style: none;
	}
	nav ul li {
		margin: 0 1rem;
	}
</style>
