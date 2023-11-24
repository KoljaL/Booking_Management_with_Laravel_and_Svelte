<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import Modal from '$lib/components/Modal.svelte';
	import EditBooking from '$lib/components/forms/EditBooking.svelte';
	import MenuStaff from '$lib/components/MenuStaff.svelte';
	import URLHandler from '$lib/urlHandler';

	let urlHandler: URLHandler;

	let model: Endpoint = 'booking';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: string = '';

	const tableColumns = [
		{
			header: 'Id',
			accessor: 'id',
			width: '4ch'
		},
		{
			header: 'Member',
			accessor: 'member_name',
			width: '20%'
		},
		{
			header: 'Date',
			accessor: 'date',
			width: '20%'
		},
		{
			header: 'Location',
			accessor: 'location_city',
			width: '20%'
		},
		{
			header: 'Time',
			accessor: 'time',
			width: '10%'
		},
		{
			header: 'Slots',
			accessor: 'slots',
			width: '10%'
		},
		{
			header: 'Created',
			accessor: 'created_at',
			width: '10%'
		}
	];
	// const { getHash, setHash } = urlStore;
	// console.log('hash', hash);
	onMount(() => {
		loadData(model);
		urlHandler = new URLHandler();
		const modelId = urlHandler.read('bid');
		if (modelId) {
			id = modelId.slice(1);
			openModal(id);
		}
	});

	async function loadData(path: Endpoint) {
		tableData = [];
		const { status, message, data } = await request('GET', path);
		if (status === 200) {
			tableData = data;
			console.log('tableData', tableData);
			responseMessage = message;
			showTable = true;
		} else {
			responseMessage = 'Error: ' + message;
			console.error('TableData loading failed', message);
		}
	}

	function openModal(rowId: string) {
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
<!-- <MenuStaff endpoint={'booking'} /> -->

{#if tableData.length > 0}
	<DataTable
		{showTable}
		{model}
		{tableData}
		{tableColumns}
		caption={responseMessage}
		getRowId={openModal}
	/>
{/if}

{#if showModal}
	<EditBooking {id} {closeModal} />
{/if}
