<script lang="ts">
	import Rocket from '$lib/icons/AnimatedRocket.svelte';
	import { tokenStore, userStore } from '$lib/store';
	import Config from '$lib/components/Config.svelte';
	import { browser } from '$app/environment';
	import MenuStaff from '$lib/components/layout/MenuStaff.svelte';
	import type { Endpoint } from '$lib/types';

	export let endpoint: Endpoint = '';
</script>

<header>
	<div class="left">
		<Rocket />
	</div>
	<div class="center">
		<MenuStaff {endpoint} />
	</div>
	<div class="right">
		{#if browser}
			<h3>{$tokenStore.name}</h3>
		{/if}
		<Config />
	</div>
</header>

<style>
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: var(--header-height);
		margin-bottom: 1.5rem;
	}
	.left {
		display: flex;
		align-items: end;
		flex-direction: row;
		gap: 0.5rem;
	}

	.center {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		margin-top: 0.4rem;
	}
	.right {
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}
</style>
