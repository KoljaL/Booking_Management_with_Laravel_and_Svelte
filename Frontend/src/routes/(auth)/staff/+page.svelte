<script lang="ts">
	import type { Endpoint } from '$lib/types';
	// import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import EditBooking from '$lib/components/edit/EditBooking.svelte';
	import URLHandler from '$lib/urlHandler';
	import { request, bookingStore } from '$lib/store';
	let urlHandler: URLHandler;

	let model: Endpoint = 'booking';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number;

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

	onMount(() => {
		loadData(model);
		urlHandler = new URLHandler();
		const modelId = urlHandler.read('bid');
		if (modelId) {
			id = typeof modelId === 'string' ? parseInt(modelId.slice(1)) : modelId;
			openModal(id);
		}
	});

	async function loadData(path: Endpoint) {
		tableData = [];
		const { status, data, message } = await request('GET', path, null, bookingStore as any);
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
<button on:click={() => openModal(0)}>New Booking</button>

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
{/if}

{#if showModal}
	<EditBooking {id} {closeModal} />
{/if}
