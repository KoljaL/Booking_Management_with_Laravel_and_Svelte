<script lang="ts">
	import type { Endpoint } from '../../../lib/types';
	import { browser } from '$app/environment';
	import { requestNEW } from '$lib/request';
	import DataTable from '$lib/components/DataTable.svelte';
	import MenuStaff from '$lib/components/MenuStaff.svelte';

	let endpoint: Endpoint = 'member';
	let responseMessage: string = '';
	let tableData: any = [];
	let modelForm: any = null;
	let modelId: string = '';
	let showModal = false;
	let showTable = false;

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
	function getEndpointFromMenu(event: CustomEvent) {
		showTable = false;
		setTimeout(() => {
			endpoint = event.detail.endpoint;
		}, 250);
	}

	async function openModal(id: string) {
		modelId = id;
		switch (endpoint) {
			case 'member':
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

	function close() {
		showModal = false;
	}

	$: console.log('showTable', showTable);
</script>

<MenuStaff on:menuClick={getEndpointFromMenu} />

{#if tableData.length > 0}
	<DataTable
		show={showTable}
		model={endpoint}
		{tableData}
		caption={responseMessage}
		callback={(id) => openModal(id)}
	/>
{/if}

{#if showModal}
	<svelte:component this={modelForm} id={modelId} callback={close}></svelte:component>
{/if}
