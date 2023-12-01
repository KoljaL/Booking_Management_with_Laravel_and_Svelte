<script lang="ts">
	import type { Endpoint, List } from '$lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { userST } from '$lib/store';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import EditMember from '$lib/components/edit/EditMember.svelte';
	import Select from '$lib/components/form/Select.svelte';
	import { delay } from '$lib/utils';

	///////////////////
	///////////////////
	///////////////////
	///////////////////
	import { makeListStore } from '$lib/store';
	let store = makeListStore('location/list');

	onMount(() => {
		store.subscribe((data: any) => {
			// console.log('store', data);
			locationList = [{ key: 'All', value: '0' }, ...data];
			// console.log('locationList', locationList);
		});
	});
	///////////////////
	///////////////////
	///////////////////
	///////////////////

	let model: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number = 0;
	let locationList: List[] = [];
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
			header: 'Name',
			accessor: 'name',
			width: '20ch'
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
			width: '10ch'
		},
		{
			header: 'Created',
			accessor: 'created_at',
			width: '15ch'
		},
		{
			header: 'Deleted',
			accessor: 'deleted_at',
			width: '5ch'
		},
		{
			header: 'Active',
			accessor: 'active',
			width: '7ch'
		}
	];

	onMount(() => {
		loadData(model);
	});

	$: if (parameterShow || parameterLocation) {
		loadData(model);
	}

	async function loadData(path: string) {
		if (parameterLocation !== '0') {
			path += '?location=' + parameterLocation;
		}
		if (parameterShow !== 'all') {
			path += parameterLocation !== '0' ? '&show=' + parameterShow : '?show=' + parameterShow;
		}
		showTable = false;
		const { status, message, data } = await request('GET', path);
		if (status === 200) {
			tableData = data;
			responseMessage = message;
			showTable = true;
		} else {
			tableData = [];
			responseMessage = 'Error: ' + message;
			console.error('TableData loading failed', message);
		}
	}

	function openModal(rowId: number) {
		id = rowId;
		showModal = true;
	}

	async function closeModal() {
		await delay(300);
		showModal = false;
	}
</script>

<svelte:head>
	<title>RB - Member</title>
</svelte:head>

<div class="selection">
	<Select label={'Show'} bind:value={parameterShow} options={showParameter} />

	{#if $userST.is_admin && locationList.length > 1}
		<Select label={'Location'} bind:value={parameterLocation} options={locationList} />
	{/if}

	<button class="addMember" on:click={() => openModal(0)}>Add member</button>
</div>

<!-- <JsonView json={tableData} open={true} /> -->

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
	<EditMember {id} {closeModal} />
{/if}

<style>
	.selection {
		display: flex;
		gap: 1rem;
		flex-direction: row;
		margin-bottom: 1rem;
	}
	.addMember {
		margin-right: 0; /* Remove margin for the last child */
		margin-left: auto; /* Push the last child to the right */
	}
</style>
