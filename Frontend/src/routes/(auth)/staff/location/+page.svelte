<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import Modal from '$lib/components/Modal.svelte';
	import EditLocation from '$lib/components/forms/EditLocation.svelte';
	import MenuStaff from '$lib/components/MenuStaff.svelte';
	let model: Endpoint = 'location';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: string = '';

	onMount(() => {
		loadData(model);
		console.log('page', $page.url.searchParams.get('id'));
		if ($page.url.searchParams.get('id')) {
			id = $page.url.searchParams.get('id')!;
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
	<title>RB - Location</title>
</svelte:head>
<!-- <MenuStaff endpoint={'location'} /> -->

{#if tableData.length > 0}
	<DataTable {showTable} {model} {tableData} caption={responseMessage} getRowId={openModal} />
{/if}

{#if showModal}
	<Modal {onClose} isOpen={true}>
		<EditLocation {id} {callback} />
	</Modal>
{/if}
