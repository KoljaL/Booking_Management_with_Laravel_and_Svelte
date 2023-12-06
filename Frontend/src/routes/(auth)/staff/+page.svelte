<script lang="ts">
	import type { Endpoint, List } from '$lib/types';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import EditBooking from '$lib/components/edit/EditBooking.svelte';
	import URLHandler from '$lib/urlHandler';
	import type { Asyncable } from '$lib/asyncStore';
	import Select from '$lib/components/form/Select.svelte';
	import { locationListStore, tokenStore, bookingStore } from '$lib/store';
	import { browser } from '$app/environment';
	let urlHandler: URLHandler;
	let model: Endpoint = 'booking';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number;
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

	const tableColumns = [
		{
			header: 'Id',
			accessor: 'id',
			width: '4ch'
		},
		{
			header: 'Member',
			accessor: 'member_name',
			width: '20ch'
		},
		{
			header: 'Date',
			accessor: 'date',
			width: '11ch'
		},
		{
			header: 'Time',
			accessor: 'time',
			width: '7ch'
		},
		{
			header: 'Slots',
			accessor: 'slots',
			width: '5ch'
		},
		{
			header: 'Location',
			accessor: 'location_city',
			width: '16ch'
		},

		{
			header: 'Created',
			accessor: 'created_at',
			width: '20ch'
		}
	];

	$: if ((parameterShow && browser) || (parameterLocation && browser)) {
		setTimeout(() => {
			loadData(model);
		}, 100);
	}

	onMount(async () => {
		// loadData(model);
		loadLocationList();

		urlHandler = new URLHandler();
		const modelId = urlHandler.read('bid');
		if (modelId) {
			id = typeof modelId === 'string' ? parseInt(modelId.slice(1)) : modelId;
			openModal(id);
		}
	});

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
		// tableData = [];
		showTable = false;
		const bookingAsyncable = bookingStore('GET', path, null);
		const { status, data, message } = await bookingAsyncable.get();
		if (status === 200) {
			tableData = data;
			// console.log('tableData', tableData);
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
		urlHandler.add('bid', id);
	}

	function closeModal() {
		// console.log('closeModal');
		showModal = false;
		urlHandler.remove('bid');
	}

	// $: console.log('showModal', showModal);
	// $: console.log('endpoint', endpoint);
	// $: console.log('pageTitle', pageTitle);
	// $: console.log('modelForm', modelForm);
</script>

<svelte:head>
	<title>RB - Booking</title>
</svelte:head>

<!-- {JSON.stringify(tableData)} -->
<div class="selection">
	<Select label={'Show'} bind:value={parameterShow} options={showParameter} />

	{#if $tokenStore.is_admin && locationList.length > 0}
		<Select label={'Location'} bind:value={parameterLocation} options={locationList} />
	{/if}

	<button class="addBooking" on:click={() => openModal(0)}>Add Booking</button>
</div>

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
			<!-- Location: {locationList.find((item) => item.value === parameterLocation)?.key} -->
		</span>
		<span>
			<!-- Show: {parameterShow} -->
		</span>
		<span>{responseMessage}</span>
	</p>
{/if}

{#if showModal}
	<EditBooking {id} {closeModal} />
{/if}

<style>
	.selection {
		display: flex;
		gap: 1rem;
		flex-direction: row;
		margin-bottom: 1rem;
	}
	.addBooking {
		margin-right: 0;
		margin-left: auto;
	}
	.noDataMessage {
		padding-top: 1rem;
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}
</style>
