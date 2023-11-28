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
	:global(nav.cursor a) {
		cursor:
			url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="24" height="24" viewBox="0 0 24 24"%3E%3Cg%3E%3Cpath fill="currentColor" d="M7 3H17V7.2L12 12L7 7.2V3Z"%3E%3Canimate id="eosIconsHourglass0" fill="freeze" attributeName="opacity" begin="0%3BeosIconsHourglass1.end" dur="2s" from="1" to="0"%2F%3E%3C%2Fpath%3E%3Cpath fill="currentColor" d="M17 21H7V16.8L12 12L17 16.8V21Z"%3E%3Canimate fill="freeze" attributeName="opacity" begin="0%3BeosIconsHourglass1.end" dur="2s" from="0" to="1"%2F%3E%3C%2Fpath%3E%3Cpath fill="currentColor" d="M6 2V8H6.01L6 8.01L10 12L6 16L6.01 16.01H6V22H18V16.01H17.99L18 16L14 12L18 8.01L17.99 8H18V2H6ZM16 16.5V20H8V16.5L12 12.5L16 16.5ZM12 11.5L8 7.5V4H16V7.5L12 11.5Z"%2F%3E%3CanimateTransform id="eosIconsHourglass1" attributeName="transform" attributeType="XML" begin="eosIconsHourglass0.end" dur="0.5s" from="0 12 12" to="180 12 12" type="rotate"%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E')
				16 16,
			pointer;
	}
</style>
