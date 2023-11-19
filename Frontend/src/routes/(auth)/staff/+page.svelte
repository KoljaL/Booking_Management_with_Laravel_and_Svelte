<script lang="ts">
	import { request } from '$lib/request';
	import { userST } from '$lib/store';
	import type { Response, Endpoint, ModelMenu } from '../../../lib/types';
	import { browser, dev, building, version } from '$app/environment';
	// import { clickOutside } from '$lib/utils';
	// import JsonView from '$lib/components/debug/JsonView.svelte';
	import MemberTable from '$lib/components/DataTable.svelte';
	// import Modal from '$lib/components/Modal.svelte';
	import MenuStaff from '$lib/components/MenuStaff.svelte';

	let endpoint: Endpoint = 'member';
	let responseJson: any = {};
	let responseMessage: string = '';
	let tableData: any = [];
	let modelForm: any = null;
	let modelId: string = '';
	let showModal = false;

	function getEndpointFromMenu(event: CustomEvent) {
		endpoint = event.detail.endpoint;
	}

	function closeModal() {
		showModal = false;
	}
	if (browser) {
		loadData(endpoint);
	}

	async function loadData(path: Endpoint) {
		tableData = [];
		const response: Response = await request('GET', path);
		responseMessage = response.message;
		responseJson = response[path];
		tableData = response[path];
	}
	$: if (endpoint) {
		loadData(endpoint);
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
</script>

{#if showModal}
	<!-- <svelte:component this={modelForm} id={modelId}></svelte:component> -->
{/if}

<MenuStaff on:menuClick={getEndpointFromMenu} />

{#if tableData.length > 0}
	<MemberTable
		model={endpoint}
		{tableData}
		caption={responseMessage}
		callback={(id) => openModal(id)}
	/>
{/if}

<!-- <JsonView json={responseJson} /> -->
