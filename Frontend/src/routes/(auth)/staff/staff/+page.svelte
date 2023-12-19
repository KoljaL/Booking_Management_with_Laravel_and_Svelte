<script lang="ts">
	import type { Endpoint, List, TableColumn } from '$lib/types';
	import { locationListStore, tokenStore, bookingStore, memberListStore } from '$lib/store';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { browser } from '$app/environment';
	import URLHandler from '$lib/urlHandler';
	import { onMount } from 'svelte';
	import Plus from '$lib/icons/Plus.svelte';
	import { page } from '$app/stores';
	import Modal from '$lib/components/Modal.svelte';
	import EditStaff from '$lib/components/edit/EditStaff.svelte';

	let urlHandler: URLHandler;
	let model: Endpoint = 'staff';
	let responseMessage: string = '';
	let showSelection = false;
	let tableData: any = [];
	let showModal = false;
	let locationList: List[] = [{ key: 'All', value: '0' }];
	let showTable = false;
	let id: number;

	const tableColumns = [
		{
			header: 'Id',
			accessor: 'id',
			width: '10%'
		},
		{
			header: 'Name',
			accessor: 'name',
			width: '20%'
		},
		{
			header: 'Email',
			accessor: 'email',
			width: '20%'
		},
		{
			header: 'Phone',
			accessor: 'phone',
			width: '20%'
		},
		{
			header: 'City',
			accessor: 'location_city',
			width: '20%'
		},

		{
			header: 'Created',
			accessor: 'created_at',
			width: '10%'
		}
	];

	$: if (locationList && browser) {
		setTimeout(() => {
			loadData(model);
		}, 100);
	}

	onMount(async () => {
		loadLocationList();
		setTimeout(() => {
			showSelection = true;
		}, 500);

		urlHandler = new URLHandler();
		const modelId = urlHandler.read('bid');
		if (modelId) {
			id = typeof modelId === 'string' ? parseInt(modelId.slice(1)) : modelId;
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

	function openModal(rowId: number) {
		id = rowId;
		console.log('openModal', id);
		showModal = true;
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

	function onClose() {
		showModal = false;
		console.log('onClose');
		$page.url.searchParams.delete('id');
		// remove id from url params
		window.history.replaceState({}, '', $page.url.toString());
	}

	function callback() {
		showModal = false;
		loadData(model);
	}

	// $: console.log('showModal', showModal);
	// $: console.log('endpoint', endpoint);
	// $: console.log('pageTitle', pageTitle);
	// $: console.log('modelForm', modelForm);
</script>

<svelte:head>
	<title>RB - Staff</title>
</svelte:head>
<!-- <MenuStaff endpoint={'staff'} /> -->

{#if tableData.length > 0}
	<DataTable
		{showTable}
		{tableData}
		{tableColumns}
		caption={responseMessage}
		getRowId={openModal}
	/>
{/if}

{#if showModal}
	<Modal {onClose} isOpen={true}>
		<EditStaff {id} {callback} />
	</Modal>
{/if}
