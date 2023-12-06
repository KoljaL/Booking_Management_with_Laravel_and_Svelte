<script lang="ts">
	import type { ModelMenu, Endpoint } from '$lib/types';
	import { tokenStore } from '$lib/store';
	import { base } from '$app/paths';
	import { slide } from 'svelte/transition';
	import { onMount } from 'svelte';

	onMount(() => {
		showNav = true;
	});

	export let endpoint: Endpoint;

	let menuItems: ModelMenu[] = [
		{
			name: 'Location',
			slug: 'location',
			is_admin: true,
			element: null
		},
		{
			name: 'Staff',
			slug: 'staff',
			is_admin: true,
			element: null
		},
		{
			name: 'Booking',
			slug: '',
			is_admin: false,
			element: null
		},
		{
			name: 'Member',
			slug: 'member',
			is_admin: false,
			element: null
		}
	];

	let showNav: boolean = false;
	let sliderWidth: number = 0;
	let sliderLeft: number = 0;
	let sliderClass: string = 'left';
	let transitionTime: number = 0.4;
	let selectedIndex: number = 0;
	let oldIndex: number = 0;

	$: if (endpoint || endpoint === '') {
		const item = menuItems.find((item) => item.slug === endpoint);
		oldIndex = selectedIndex;
		selectedIndex = menuItems.findIndex((item) => item.slug === endpoint);
		if (item) {
			sliderWidth = item.element?.clientWidth || 0;
			sliderLeft = item.element?.offsetLeft || 0;
			transitionTime = 0.2 * Math.abs(selectedIndex - oldIndex);
			if (selectedIndex === 0) {
				sliderClass = 'left';
			} else if (selectedIndex === menuItems.length - 1) {
				sliderClass = 'right';
			} else {
				sliderClass = '';
			}
		}
	}
</script>

{#if showNav}
	<nav>
		<ul transition:slide={{ delay: 200, axis: 'x' }}>
			{#each menuItems as item, i (i)}
				{#if $tokenStore.is_admin || !item.is_admin}
					<li bind:this={menuItems[i].element}>
						<a href="{base}/staff/{item.slug}">
							{item.name}
						</a>
					</li>
				{/if}
			{/each}
		</ul>
		<div
			class="slider {sliderClass}"
			style="--slider-left:{sliderLeft}px; --slider-width:{sliderWidth}px; --transition-time:{transitionTime}s;"
		/>
	</nav>
{:else}
	<nav></nav>
{/if}

<style>
	nav {
		--menu-height: 2rem;
		height: var(--menu-height);
		display: flex;
		max-width: fit-content;
		margin-bottom: 1rem;
		position: relative;
	}
	ul {
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: var(--menu-height);
		padding: 0;
		list-style: none;
		overflow: hidden;
		border-radius: var(--border-radius-m);
		box-shadow: var(--box-shadow);
		z-index: 1;
		margin: 0;
	}
	li {
		height: 100%;
		padding: 0;
		margin: 0;
		z-index: 2;
	}
	a {
		display: inline-block;
		position: relative;
		height: 100%;
		color: var(--colors-gray6);
		text-decoration: none;
		font-size: 1.1rem;
		line-height: 2rem;
		font-weight: bold;
		cursor: pointer;
		padding: 0 1rem;
		padding-top: 1.5px;
		/* opacity: 0.8; */
		transition: all 0.2s ease-in-out;
		z-index: 2;
	}
	a:hover {
		color: var(--colors-gray1);
		/* opacity: 1; */
	}
	a::before {
		content: '';
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		top: 0;
		background-color: transparent;
		opacity: 0;
		transition: all 0.15s linear;
		z-index: -1;
	}

	a:hover::before {
		background-color: var(--blue);
		border-radius: var(--border-radius-s);
		box-shadow: var(--box-shadow);
		opacity: 0.3;
	}

	.slider {
		--transition-time: 0.4s;
		left: var(--slider-left, 0);
		width: var(--slider-width, 0);
		position: absolute;
		top: 0;
		/* height: calc(var(--menu-height) - 1px); */
		height: 100%;
		background-color: var(--blue);
		transition: all var(--transition-time) ease-in-out;
		/* transition: all 0.4s ease-in-out; */
		border-radius: var(--border-radius-s);
		box-shadow: var(--box-shadow-after), var(--box-shadow);
		/* z-index: 1; */
		margin: 0;
		opacity: 0;
		animation: fadein 0.5s 0.5s ease-in-out forwards;
	}

	@keyframes fadein {
		0% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	.slider.left {
		border-radius: var(--border-radius-m) var(--border-radius-s) var(--border-radius-s)
			var(--border-radius-m);
	}
	.slider.right {
		border-radius: var(--border-radius-s) var(--border-radius-m) var(--border-radius-m)
			var(--border-radius-s);
	}

	:global(nav.cursor a) {
		cursor:
			url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="24" height="24" viewBox="0 0 24 24"%3E%3Cg%3E%3Cpath fill="currentColor" d="M7 3H17V7.2L12 12L7 7.2V3Z"%3E%3Canimate id="eosIconsHourglass0" fill="freeze" attributeName="opacity" begin="0%3BeosIconsHourglass1.end" dur="2s" from="1" to="0"%2F%3E%3C%2Fpath%3E%3Cpath fill="currentColor" d="M17 21H7V16.8L12 12L17 16.8V21Z"%3E%3Canimate fill="freeze" attributeName="opacity" begin="0%3BeosIconsHourglass1.end" dur="2s" from="0" to="1"%2F%3E%3C%2Fpath%3E%3Cpath fill="currentColor" d="M6 2V8H6.01L6 8.01L10 12L6 16L6.01 16.01H6V22H18V16.01H17.99L18 16L14 12L18 8.01L17.99 8H18V2H6ZM16 16.5V20H8V16.5L12 12.5L16 16.5ZM12 11.5L8 7.5V4H16V7.5L12 11.5Z"%2F%3E%3CanimateTransform id="eosIconsHourglass1" attributeName="transform" attributeType="XML" begin="eosIconsHourglass0.end" dur="0.5s" from="0 12 12" to="180 12 12" type="rotate"%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E')
				16 16,
			pointer;
	}
</style>
