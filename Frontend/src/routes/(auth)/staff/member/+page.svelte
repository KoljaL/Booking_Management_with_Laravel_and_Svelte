<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import Modal from '$lib/components/Modal.svelte';
	import EditMember from '$lib/components/forms/EditMember.svelte';
	import StaffMenu from '$lib/components/MenuStaff.svelte';

	let model: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: string = '';

	onMount(() => {
		loadData(model);
		if ($page.url.searchParams.get('id')) {
			console.log('page', $page.url.searchParams.get('id'));
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
		// console.log('openModal with Id', id);
		id = rowId;
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
	// $: console.log('id', id);
</script>

<svelte:head>
	<title>RB - Member</title>
</svelte:head>
<!-- <StaffMenu endpoint={'member'} /> -->

{#if tableData.length > 0}
	<DataTable {showTable} {model} {tableData} caption={responseMessage} getRowId={openModal} />
{/if}

{#if showModal}
	<Modal {onClose} isOpen={true}>
		<EditMember {id} {callback} />
	</Modal>
{/if}
