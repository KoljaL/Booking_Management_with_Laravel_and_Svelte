<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import Modal from '$lib/components/Modal.svelte';
	import EditStaff from '$lib/components/forms/EditStaff.svelte';

	let model: Endpoint = 'staff';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
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
