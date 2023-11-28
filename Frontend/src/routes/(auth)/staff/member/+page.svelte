<script lang="ts">
	import type { Endpoint } from '../../../../lib/types';
	import { request } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import { userST } from '$lib/store';
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import EditMember from '$lib/components/forms/EditMember.svelte';
	import { delay } from '$lib/utils';
	console.log('userST', $userST);
	let model: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let id: number = 0;
	let locations: any = [];

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
		getLocationList().then((data) => {
			console.log('data', JSON.stringify(data, null, 2));
		});
	});
	async function getLocationList() {
		const { status, message, data } = await request('GET', 'location/list');
		if (status === 200) {
			locations = data;
		} else {
			console.error('Location list loading failed', message);
			return [];
		}
	}

	async function getMemberList() {
		const { status, message, data } = await request('GET', 'member/list');
		if (status === 200) {
			return data;
		} else {
			console.error('Member list loading failed', message);
			return [];
		}
	}
	getMemberList().then((data) => {
		console.log('data', JSON.stringify(data, null, 2));
	});

	async function loadData(path: string) {
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
	let parameterLocation: number = 0; // $userST.is_admin ? 0 : $userST.location.id;
	let parameterShow: string = 'active';
	let test = () => loadData(model + '/?location=' + parameterLocation);
</script>

<svelte:head>
	<title>RB - Member</title>
</svelte:head>

<!-- <label>
	Show
	<select bind:value={parameterShow} on:change={() => loadData(model + '/?show=' + parameterShow)}>
		{#each showParameter as show}
			<option value={show.value}>{show.key}</option>
		{/each}
	</select>
</label> -->

{#if $userST.is_admin}
	<label>
		Location
		<select
			bind:value={parameterLocation}
			on:change={() => loadData(model + '/?location' + parameterLocation)}
		>
			{#each locations as location}
				<option value={location.id}>{location.city}</option>
			{/each}
		</select>
	</label>
{/if}

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
