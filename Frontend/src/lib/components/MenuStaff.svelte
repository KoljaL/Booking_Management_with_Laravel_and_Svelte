<script lang="ts">
	import type { ModelMenu, Endpoint } from '$lib/types';
	import { userST } from '$lib/store';
	import { base } from '$app/paths';

	export let endpoint: Endpoint;

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
			name: 'Booking',
			slug: '',
			is_admin: false
		},
		{
			name: 'Member',
			slug: 'member',
			is_admin: false
		}
	];
</script>

<nav>
	<ul>
		{#each menuItems as item}
			{#if $userST.is_admin || !item.is_admin}
				<li>
					<a href="{base}/staff/{item.slug}" class:active={endpoint === item.slug}>
						{item.name}
					</a>
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
		padding-top: 0.2rem;
		margin: 0 1rem;
	}
	a {
		color: var(--black);
		text-decoration: none;
		font-size: 1rem;
		font-weight: bold;
		cursor: pointer;
		padding: 0;
		transition: color 0.2s ease-in-out;
	}
	a.active {
		color: var(--blue);
	}
	a:hover {
		color: var(--blue);
	}
</style>
