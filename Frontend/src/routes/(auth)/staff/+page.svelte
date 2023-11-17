<script lang="ts">
	import { request } from '$lib/request';
	import { userST } from '$lib/store';
	import type { Response, Endpoint } from './../../../types';
	import { browser, dev, building, version } from '$app/environment';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import MemberTable from '$lib/components/table/MemberTable.svelte';
	// console.log($userST);

	let endpoint: Endpoint = 'member';
	let responseJson: any = {};
	let responseMessage: string = '';
	let tableData: any = [];

	if (browser) {
		loadData(endpoint);
	}

	async function loadData(path: Endpoint) {
		tableData = [];
		const response: Response = await request('GET', path);
		console.log(response);
		responseMessage = response.message;
		responseJson = response[path];
		tableData = response[path];
	}
	$: {
		if (endpoint) {
			loadData(endpoint);
		}
	}
</script>

<h1>Staff</h1>
<nav>
	<ul>
		{#if $userST.is_admin}
			<li>
				<button
					on:click={() => {
						endpoint = 'location';
					}}
				>
					Location
				</button>
			</li>
			<li>
				<button
					on:click={() => {
						endpoint = 'staff';
					}}
				>
					Staff
				</button>
			</li>
		{/if}

		<li>
			<button
				on:click={() => {
					endpoint = 'booking';
				}}
			>
				Booking
			</button>
		</li>

		<li>
			<button
				on:click={() => {
					endpoint = 'member';
				}}
			>
				Member
			</button>
		</li>
	</ul>
</nav>

<h2>{responseMessage}</h2>
<!-- {#await tableData then tableData} -->
{#if tableData.length > 0}
	<MemberTable model={endpoint} {tableData} />
{/if}

<!-- {/await} -->

<!-- <JsonView json={responseJson} /> -->

<style>
	nav {
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
