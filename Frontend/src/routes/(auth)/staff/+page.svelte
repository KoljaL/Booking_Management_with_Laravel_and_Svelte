<script lang="ts">
	import type { Endpoint, List, TableColumn } from '$lib/types';
	import { onMount } from 'svelte';
	import { locationListStore, tokenStore, bookingStore, memberListStore } from '$lib/store';
	import { browser } from '$app/environment';
	import { fade, slide } from 'svelte/transition';
	import URLHandler from '$lib/urlHandler';
	import Select from '$lib/components/form/Select.svelte';
	import DataTable from '$lib/components/DataTable.svelte';
	import EditBooking from '$lib/components/edit/EditBooking.svelte';
	import SveltyPicker, { config } from 'svelty-picker';
	import Plus from '$lib/icons/Plus.svelte';
	import { de, jp } from 'svelty-picker/i18n';

	// config.i18n = de;
	let urlHandler: URLHandler;
	let model: Endpoint = 'booking';
	let responseMessage: string = '';
	let tableData: any = [];
	let showModal = false;
	let showTable = false;
	let showSelection = false;
	let id: number;
	let locationList: List[] = [{ key: 'All', value: '0' }];
	let memberList: List[] = [{ key: 'All', value: '0' }];
	// let parameterLocation: string = '0';
	// let parameterShow: string = 'active';

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

	const tableColumns: TableColumn[] = [
		{
			header: 'Id',
			accessor: 'id',
			width: '6ch',
			type: 'number',
			sortOrder: null
		},
		{
			header: 'Member',
			accessor: 'member_name',
			width: '20ch',
			type: 'string',
			sortOrder: null
		},
		{
			header: 'Date',
			accessor: 'date',
			width: '20ch',
			type: 'date',
			sortOrder: null
		},
		{
			header: 'Slots',
			accessor: 'slots',
			width: '7ch',
			type: 'number',
			sortOrder: null
		},
		{
			header: 'Location',
			accessor: 'location_city',
			width: '16ch',
			type: 'string',
			sortOrder: null
		},

		{
			header: 'Comment Member',
			accessor: 'comment_member',
			width: '20ch',
			type: 'string',
			sortOrder: null
		},
		{
			header: 'Comment Staff',
			accessor: 'comment_staff',
			width: '20ch',
			type: 'string',
			sortOrder: null
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
	// $: console.log('filterParameter', filterParameter);

	$: if (filterParameter && browser) {
		setTimeout(() => {
			loadData(model);
		}, 100);
	}

	onMount(async () => {
		loadLocationList();
		loadMemberList();
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

	async function loadMemberList() {
		const memberListAsyncable = memberListStore('GET', 'member/list', null);
		const { status, data, message } = await memberListAsyncable.get();
		if (status === 200) {
			memberList = [{ key: 'All', value: '0' }, ...(data as List[])];
		} else {
			console.error('MemberList loading failed', message);
		}
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

	async function loadData(path: Endpoint) {
		showTable = false;
		path += addFilterParameter(filterParameter);

		const bookingAsyncable = bookingStore('GET', path, null);
		const { status, data, message } = await bookingAsyncable.get();
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

	function addFilterParameter(filterParameter: FilterParameter): string {
		const params = new URLSearchParams();
		if (filterParameter.show) {
			params.set('show', filterParameter.show);
		}
		if (filterParameter.date && filterParameter.date !== 'All') {
			params.set('date', filterParameter.date);
		}
		if (filterParameter.location && filterParameter.location !== '0') {
			params.set('location', filterParameter.location as string);
		}
		if (filterParameter.member && filterParameter.member !== '0') {
			params.set('member', filterParameter.member as string);
		}
		const queryString = params.toString();
		const path = queryString ? `?${queryString}` : '';
		// console.log('path', path);
		return path;
	}

	function openModal(rowId: number) {
		id = rowId;
		showModal = true;
		urlHandler.add('bid', id);
	}

	function closeModal() {
		showModal = false;
		urlHandler.remove('bid');
	}
</script>

<svelte:head>
	<title>RB - Booking</title>
</svelte:head>

<!-- {JSON.stringify(tableData)} -->
{#if showSelection}
	<div class="selection" transition:fade={{ duration: 300 }}>
		<Select label={'Show'} bind:value={filterParameter.show} options={showParameter} />

		<label for="date">
			Date
			<SveltyPicker
				bind:value={filterParameter.date}
				displayFormat="j. n. Y"
				displayFormatType="php"
			/>
		</label>

		<Select
			label={'Member'}
			bind:value={filterParameter.member}
			options={memberList}
			maxwidth={'10ch'}
		/>

		{#if $tokenStore.is_admin && locationList.length > 0}
			<Select label={'Location'} bind:value={filterParameter.location} options={locationList} />
		{/if}

		<button class="addBooking" on:click={() => openModal(0)}><Plus /></button>
	</div>
{/if}

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
{:else if showTable && tableData.length === 0}
	<p class="noDataMessage">
		<span>
			No data for: {model}
		</span>
		<span>
			<!-- Location: {locationList.find((item) => item.value === parameterLocation)?.key} -->
		</span>
		<span>
			<!-- Show: {parameterShow} -->
		</span>
		<span>{responseMessage}</span>
	</p>
{/if}

{#if showModal}
	<EditBooking {id} {closeModal} />
{/if}

<style>
	.selection {
		display: flex;
		align-items: center;
		gap: 1rem;
		flex-direction: row;
		margin-bottom: 1rem;
	}
	@keyframes fade-in {
		0% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	.addBooking {
		margin-right: 0;
		margin-left: auto;
		padding: 0.25rem;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.noDataMessage {
		padding-top: 1rem;
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}
	label {
		display: flex;
		align-items: baseline;
		flex-wrap: nowrap;
		gap: 0.5rem;
	}
</style>
