<script lang="ts">
	import type { Endpoint } from '../../../lib/types';
	import { browser } from '$app/environment';
	import { getParamFromUrl, setParamToUrl } from '$lib/utils';
	import { requestNEW } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import MenuStaff from '$lib/components/MenuStaff.svelte';
	import { onMount } from 'svelte';
	let endpoint: Endpoint = 'member';
	let lastEndpoint: Endpoint;
	let lastModelId = '';
	let responseMessage: string = '';
	let tableData: any = [];
	let modelForm: any = null;
	let modelId: string = '';
	let showModal = false;
	let showTable = false;
	let pageTitle: string = '';

	onMount(() => {
		hashChanged();
	});

	function hashChanged() {
		const newEndpoint = getParamFromUrl('e') as Endpoint;
		const newModelId = getParamFromUrl('id') || '';
		// console.log('newEndpoint', newEndpoint);

		if (newEndpoint && newEndpoint !== lastEndpoint) {
			pageTitle = newEndpoint.charAt(0).toUpperCase() + newEndpoint.slice(1);
			document.title = 'RB - ' + newEndpoint.charAt(0).toUpperCase() + newEndpoint.slice(1);
			showTable = false;
			// setTimeout(() => {
			endpoint = newEndpoint;
			lastEndpoint = newEndpoint;
			showTable = true;
			// }, 250);
		}
		if (newModelId && newModelId !== lastModelId) {
			lastModelId = newModelId;
			openModal(newModelId);
		} else if (newModelId === '' && lastModelId !== '') {
			// lastModelId = newModelId;
			showModal = false;
		}
	}

	async function loadData(path: Endpoint) {
		tableData = [];
		const { status, message, data } = await requestNEW('GET', path);
		if (status === 200) {
			tableData = data;
			responseMessage = message;
			showTable = true;
		} else {
			responseMessage = 'Error: ' + message;
			console.error('TableData loading failed', message);
		}
	}

	$: if (endpoint && browser) {
		loadData(endpoint);
	}

	async function openModal(id: string) {
		// console.log('openModal', endpoint, id);
		setParamToUrl('id', id);
		modelId = id;
		switch (endpoint) {
			case 'member':
				console.log('member');
				modelForm = (await import('$lib/components/forms/EditMember.svelte')).default;
				break;
			case 'staff':
				modelForm = (await import('$lib/components/forms/EditStaff.svelte')).default;
				break;
			case 'booking':
				modelForm = (await import('$lib/components/forms/EditBooking.svelte')).default;
				break;
			case 'location':
				modelForm = (await import('$lib/components/forms/EditLocation.svelte')).default;
				break;
			default:
				showModal = false;
		}
		showModal = true;
	}

	// $: console.log('showModal', showModal);
	$: console.log('endpoint', endpoint);
	// $: console.log('pageTitle', pageTitle);
	// $: console.log('modelForm', modelForm);
</script>

<svelte:window on:hashchange={hashChanged} />
<svelte:head>
	<title>RB - {pageTitle}</title>
</svelte:head>

<MenuStaff {endpoint} />

{#if tableData.length > 0}
	<DataTable
		{showTable}
		model={endpoint}
		{tableData}
		caption={responseMessage}
		getRowId={openModal}
	/>
{/if}

{#if showModal}
	<svelte:component
		this={modelForm}
		id={modelId}
		callback={() => {
			showModal = false;
		}}
	></svelte:component>
{/if}
