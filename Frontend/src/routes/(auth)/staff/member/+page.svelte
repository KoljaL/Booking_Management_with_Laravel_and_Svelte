<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import EditMember from '$lib/components/forms/EditMember.svelte';
	import { delay } from '$lib/utils';

	let model: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number = 0;

	const tableColumns = [
		{
			header: 'Id',
			accessor: 'id',
			width: '4ch'
		},
		{
			header: 'Name',
			accessor: 'name',
			width: '25ch'
		},
		{
			header: 'Email',
			accessor: 'email',
			width: '25ch'
		},
		{
			header: 'Phone',
			accessor: 'phone',
			width: '20ch'
		},
		{
			header: 'City',
			accessor: 'location_city',
			width: '20ch'
		},
		{
			header: 'Created',
			accessor: 'created_at',
			width: '15ch'
		}
	];

	onMount(() => {
		loadData(model);
		// if ($page.url.searchParams.get('id')) {
		// 	console.log('page', $page.url.searchParams.get('id'));
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
		showModal = true;
	}

	// function onClose() {
	// 	showModal = false;
	// 	console.log('onClose');
	// 	$page.url.searchParams.delete('id');
	// 	// remove id from url params
	// 	window.history.replaceState({}, '', $page.url.toString());
	// }

	async function closeModal() {
		await delay(300);
		showModal = false;
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

<button on:click={() => openModal(0)}>Add member</button>

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
	<EditMember {id} {closeModal} />
{/if}
