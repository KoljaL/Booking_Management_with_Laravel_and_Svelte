<script lang="ts">
	import type { Endpoint, List, TableColumn } from '$lib/types';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import EditMember from '$lib/components/edit/EditMember.svelte';
	import Select from '$lib/components/form/Select.svelte';
	import { browser } from '$app/environment';
	import { delay } from '$lib/utils';
	import Plus from '$lib/icons/Plus.svelte';
	import { locationListStore, tokenStore, memberStore } from '$lib/store';

	let model: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number = 0;
	let locationList: List[] = [{ key: 'All', value: '0' }];
	let parameterLocation: string = '0';
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

	let tableColumns: TableColumn[] = [
		{
			header: 'Id',
			accessor: 'id',
			width: '5ch',
			type: 'number',
			sortOrder: null
		},
		{
			header: 'Name',
			accessor: 'name',
			width: '25ch',
			type: 'string',
			sortOrder: null
		},
		{
			header: 'Email',
			accessor: 'email',
			width: '25ch',
			type: 'string',
			sortOrder: null
		},
		{
			header: 'Phone',
			accessor: 'phone',
			width: '20ch',
			type: 'number',
			sortOrder: null
		},
		{
			header: 'City',
			accessor: 'location_city',
			width: '10ch',
			type: 'string',
			sortOrder: null
		},
		{
			header: 'JC-Number',
			accessor: 'jc_number',
			width: '15ch',
			type: 'number',
			sortOrder: null
		},
		// {
		// 	header: 'Max Bookings',
		// 	accessor: 'max_booking',
		// 	width: '5ch',
		// 	type: 'number',
		// 	sortOrder: null
		// },
		// {
		// 	header: 'Created',
		// 	accessor: 'created_at',
		// 	width: '15ch',
		// 	type: 'date',
		// 	sortOrder: null
		// },
		// {
		// 	header: 'Deleted',
		// 	accessor: 'deleted_at',
		// 	width: '5ch',
		// 	type: 'date',
		// 	sortOrder: null
		// },
		{
			header: 'Active',
			accessor: 'active',
			width: '7ch',
			type: 'string',
			sortOrder: null
		}
	];

	if (!$tokenStore.is_admin) {
		tableColumns = tableColumns.filter((column) => column.header !== 'City');
	}

	onMount(() => {
		// loadData(model);
		loadLocationList();
	});

	$: if ((parameterShow && browser) || (parameterLocation && browser)) {
		setTimeout(() => {
			loadData(model);
		}, 100);
	}

	async function loadLocationList() {
		const locationListAsyncable = locationListStore('GET', 'location/list', null);
		const { status, data, message } = await locationListAsyncable.get();
		if (status === 200) {
			locationList = [{ key: 'All', value: '0' }, ...(data as List[])];
		} else {
			console.error('LocationList loading failed', message);
		}
	}

	async function loadData(path: Endpoint) {
		if (parameterLocation !== '0') {
			path += '?location=' + parameterLocation;
		}
		if (parameterShow !== 'all') {
			path += parameterLocation !== '0' ? '&show=' + parameterShow : '?show=' + parameterShow;
		}
		showTable = false;
		const memberAsyncable = memberStore('GET', path, null);
		const { status, data, message } = await memberAsyncable.get();
		if (status === 200) {
			tableData = data;
			responseMessage = message;
			showTable = true;
		} else {
			responseMessage = 'Error: ' + message;
			showTable = true;
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

<div class="selection">
	<Select label={'Show'} bind:value={parameterShow} options={showParameter} />

	{#if $tokenStore.is_admin && locationList.length > 0}
		<Select label={'Location'} bind:value={parameterLocation} options={locationList} />
	{/if}

	<button class="addMember" on:click={() => openModal(0)}><Plus /></button>
</div>

<!-- <JsonView json={tableData} open={true} /> -->

{#if tableData.length > 0}
	{#key tableData}
		<DataTable
			{showTable}
			{tableData}
			{tableColumns}
			caption={responseMessage}
			getRowId={openModal}
		/>
	{/key}
{:else if showTable && tableData.length === 0}
	<p class="noDataMessage">
		<span>
			No data for: {model}
		</span>
		<span>
			Location: {locationList.find((item) => item.value === parameterLocation)?.key}
		</span>
		<span>
			Show: {parameterShow}
		</span>
		<span>{responseMessage}</span>
	</p>
{/if}

{#if showModal}
	<EditMember {id} {closeModal} />
{/if}

<style>
	.selection {
		display: flex;
		gap: 1rem;
		flex-direction: row;
		margin-bottom: 1rem;
	}
	.addMember {
		margin-right: 0;
		margin-left: auto;
		padding: 0.25rem;
		display: flex;
		align-items: center;
		justify-content: center;
		line-height: 1;
	}
	.noDataMessage {
		padding-top: 1rem;
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}

	/* .noDataMessage span {
		padding-top: 0.5rem;
		font-weight: bold;
		display: block;
		font-size: 1.2rem;
	} */
</style>
