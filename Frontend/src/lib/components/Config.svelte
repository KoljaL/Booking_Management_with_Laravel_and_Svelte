<script lang="ts">
	import { slide } from 'svelte/transition';
	import Gear from '$lib/icons/Gear.svelte';
	import { onMount } from 'svelte';
	import { tokenStore } from '$lib/store';
	import { goto } from '$app/navigation';
	import { base as baseUrl } from '$app/paths';
	import Toggle from '$lib/components/form/Toggle.svelte';
	import { clickOutsideAction } from '$lib/utils';
	function logout() {
		goto(`${baseUrl}/login`);
		// tokenStore.set({});
	}
	let open: boolean = false;
	let storeUserData = false;
	let configItems: HTMLDivElement;
	let userData: any;
	$: storeUserData = storeUserData;

	onMount(async () => {
		userData = JSON.parse(localStorage.getItem('user') as string);
		if (userData) {
			addToLocalStorage(userData);
			tokenStore.set(userData);
		} else {
			removeFromLocalStorage();
		}
	});

	function handleStoreUserData(storeUserData: boolean) {
		if (!storeUserData) {
			removeFromLocalStorage();
		}
		if (storeUserData) {
			addToLocalStorage($tokenStore);
		}
	}

	function addToLocalStorage(data: any) {
		localStorage.setItem('user', JSON.stringify(data));
		storeUserData = true;
	}

	function removeFromLocalStorage() {
		localStorage.removeItem('user');
		storeUserData = false;
	}
	// $: console.log('userData', userData);
</script>

<div class="selectWrapper">
	<label>
		<button class="toggleButton" on:click={() => (open = !open)}>
			<Gear />
			<span class="arrow" class:open />
		</button>
	</label>

	{#if open}
		<div
			transition:slide={{ delay: 0, duration: 200 }}
			class="configItems"
			bind:this={configItems}
			use:clickOutsideAction
			on:clickoutside={() => (open = false)}
		>
			<ul>
				<li>
					<Toggle onChange={handleStoreUserData} checked={storeUserData} label={'remember me'} />
				</li>
				<li>
					<button on:click={logout}>Logout</button>
				</li>
			</ul>
		</div>
	{/if}
</div>

<style>
	.selectWrapper {
		position: relative;
		width: fit-content;
	}

	label {
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		gap: 0.25rem;
	}
	.toggleButton {
		padding-left: 0.25rem;
		padding-right: 0.1rem;
		display: flex;
		white-space: nowrap;
	}
	.toggleButton :global(svg) {
		width: 1rem;
		height: 1rem;
	}
	.arrow {
		margin: 0;
		width: 1rem;
		height: 1rem;
		rotate: 180deg;
		background: url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="24" height="24" viewBox="0 0 24 24"%3E%3Cpath fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m17 14l-5-5l-5 5"%2F%3E%3C%2Fsvg%3E')
			no-repeat center;
		background-size: contain;
		transition: transform 0.2s ease-in-out;
	}

	.arrow.open {
		transform: rotate(180deg);
	}
	.configItems {
		min-width: fit-content;
		width: var(--width);
		position: absolute;
		top: 100%;
		right: 0;
		background: var(--colors-gray12);
		overflow: hidden;
		box-shadow: var(--box-shadow);
		border-radius: var(--border-radius-m, 0.5rem);
		z-index: 1;
	}

	ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	li {
		margin: 0;
		padding: 0;
		padding: 0.5rem;
	}
</style>
