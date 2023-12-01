<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import Modal from '$lib/components/Modal.svelte';
	import EditLocation from '$lib/components/edit/EditLocation.svelte';

	let model: Endpoint = 'location';
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
	onMount(() => {
		loadData(model);
		// if ($page.url.searchParams.get('id')) {
		// 	id = $page.url.searchParams.get('id')!;
		// 	openModal(id);
		// }
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
