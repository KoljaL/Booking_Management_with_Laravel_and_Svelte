<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import DataTable from '$lib/components/DataTable.svelte';
	import { fade } from 'svelte/transition';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import Select from '$lib/components/form/Select.svelte';
	import Modal from '$lib/components/Modal.svelte';
	import EditLocation from '$lib/components/edit/EditLocation.svelte';
	import { locationStore } from '$lib/store';
	import { browser } from '$app/environment';
	let model: Endpoint = 'location';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let showSelection = false;
	let id: number;

	const showParameter = [
		{
			key: 'Active',
			value: ''
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
			header: 'Address',
			accessor: 'address',
			width: '22ch'
		},
		{
			header: 'City',
			accessor: 'city',
			width: '25ch'
		},
		{
			header: 'Email',
			accessor: 'email',
			width: '22ch'
		},
		// {
		// 	header: 'Open from',
		// 	accessor: 'opening_hour_from',
		// 	width: '4ch'
		// },
		// {
		// 	header: 'Opening Days',
		// 	accessor: 'opening_days',
		// 	width: '5ch'
		// },
		// {
		// 	header: 'Open to',
		// 	accessor: 'opening_hour_to',
		// 	width: '4ch'
		// },
		{
			header: 'Max Bookins',
			accessor: 'max_booking',
			width: '12ch'
		},
		// {
		// 	header: 'Slot duration',
		// 	accessor: 'slot_duration',
		// 	width: '4ch'
		// },
		{
			header: 'Workspaces',
			accessor: 'workspaces',
			width: '10ch'
		}
	];

	type FilterParameter = {
		show: 'active' | 'inactive' | 'deleted' | 'all' | null;
		date: string | undefined;
		location: string | number | null;
		member: string | number | null;
	};

	let filterParameter: FilterParameter = {
		show: null,
		date: new Date().toISOString().slice(0, 10),
		location: null,
		member: null
	};

	$: if (filterParameter && browser) {
		setTimeout(() => {
			loadData(model);
		}, 100);
	}

	onMount(() => {
		// loadData(model);
		// if ($page.url.searchParams.get('id')) {
		// 	id = $page.url.searchParams.get('id')!;
		// 	openModal(id);
		// }
	});

	// async function loadData(path: Endpoint) {
	// 	tableData = [];
	// 	const { status, message, data } = await request('GET', path);
	// 	if (status === 200) {
	// 		tableData = data;
	// 		console.log('tableData', tableData);
	// 		responseMessage = message;
	// 		showTable = true;
	// 	} else {
	// 		responseMessage = 'Error: ' + message;
	// 		console.error('TableData loading failed', message);
	// 	}
	// }

	async function loadData(path: Endpoint) {
		showTable = false;

		const locationAsyncable = locationStore('GET', path, null);
		const { status, data, message } = await locationAsyncable.get();
		if (status === 200) {
			tableData = data;
			// console.log('tableData', tableData);
			responseMessage = message;
			showTable = true;
			showSelection = true;
		} else {
			responseMessage = 'Error: ' + message;
			showTable = true;
			console.error('TableData loading failed', message);
		}
	}

	function openModal(rowId: number) {
		id = rowId;
		console.log('openModal', id);
		showModal = true;
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
</script>

<svelte:head>
	<title>RB - Location</title>
</svelte:head>

{#if showSelection}
	<div class="selection" transition:fade={{ duration: 300 }}>
		<Select label={'Show'} bind:value={filterParameter.show} options={showParameter} />
	</div>
{/if}

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
		<EditLocation {id} {callback} />
	</Modal>
{/if}
