<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { userST } from '$lib/store';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import EditMember from '$lib/components/forms/EditMember.svelte';
	import { delay } from '$lib/utils';
	// console.log('userST', $userST);

	let model: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number = 0;
	let locationList: any = [{ id: 0, city: 'All' }];
	let parameterLocation: number = 0;
	let parameterShow: string = 'active';

	const showParameter = [
		{
			key: 'Active',
			value: 'active'
		},
		{
			key: 'Inactive',
			value: 'inactive'
		},
		{
			key: 'Deleted',
			value: 'deleted'
		},
		{
			key: 'All',
			value: 'all'
		}
	];

	const tableColumns = [
		{
			header: 'Id',
			accessor: 'id',
			width: '4ch'
		},
		{
			header: 'Name',
			accessor: 'name',
			width: '20ch'
		},
		{
			header: 'Email',
			accessor: 'email',
			width: '25ch'
		},
		{
			header: 'Phone',
			accessor: 'phone',
			width: '20ch'
		},
		{
			header: 'City',
			accessor: 'location_city',
			width: '10ch'
		},
		{
			header: 'Created',
			accessor: 'created_at',
			width: '15ch'
		},
		{
			header: 'Deleted',
			accessor: 'deleted_at',
			width: '5ch'
		},
		{
			header: 'Active',
			accessor: 'active',
			width: '7ch'
		}
	];

	onMount(() => {
		loadData(model);
		setTimeout(() => {
			getLocationList();
		}, 200);
	});

	async function getLocationList() {
		const { status, message, data } = await request('GET', 'location/list');
		if (status === 200) {
			locationList = [...locationList, ...data];
		} else {
			console.error('Location list loading failed', message);
			return [];
		}
	}

	async function loadData(path: string) {
		if (parameterLocation !== 0) {
			path += '?location=' + parameterLocation;
		}
		if (parameterShow !== 'all') {
			path += parameterLocation !== 0 ? '&show=' + parameterShow : '?show=' + parameterShow;
		}
		// console.log('path', path);
		showTable = false;
		console.time('loadData');

		const { status, message, data } = await request('GET', path);
		console.timeEnd('loadData');
		if (status === 200) {
			tableData = data;
			responseMessage = message;
			showTable = true;
		} else {
			tableData = [];
			responseMessage = 'Error: ' + message;
			console.error('TableData loading failed', message);
		}
	}

	function openModal(rowId: number) {
		id = rowId;
		showModal = true;
	}

	async function closeModal() {
		await delay(300);
		showModal = false;
	}
</script>

<svelte:head>
	<title>RB - Member</title>
</svelte:head>

<label>
	Show
	<div class="shadowWrapper">
		<select bind:value={parameterShow} on:change={() => loadData(model)}>
			{#each showParameter as show}
				<option value={show.value}>{show.key}</option>
			{/each}
		</select>
	</div>
</label>

{#if $userST.is_admin}
	<label>
		Location
		<div class="shadowWrapper">
			<select bind:value={parameterLocation} on:change={() => loadData(model)}>
				{#each locationList as location}
					<option value={location.id}>{location.city}</option>
				{/each}
			</select>
		</div>
	</label>
{/if}

<button on:click={() => openModal(0)}>Add member</button>

<!-- <JsonView json={tableData} open={true} /> -->

{#if tableData.length > 0}
	<!-- {JSON.stringify(tableData)} -->

	{#key tableData}
		<DataTable
			{showTable}
			{tableData}
			{tableColumns}
			caption={responseMessage}
			getRowId={openModal}
		/>
	{/key}
{/if}

{#if showModal}
	<EditMember {id} {closeModal} />
{/if}
