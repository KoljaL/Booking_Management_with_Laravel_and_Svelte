<script lang="ts">
	import { request } from '$lib/request';
	import JsonView from '$lib/components/debug/JsonView.svelte';
	import Modal from '$lib/components/Modal.svelte';

	let showModal = true;
	function closeModal() {
		showModal = false;
	}
	export let id = '';
	let member: any = {};

	// request('GET', 'member/' + id).then((response) => {
	// 	console.log('response', response);
	// 	member = response.member;
	// });
	// console.log('id', id);
</script>

<Modal isOpen={showModal} onClose={closeModal}>
	<span slot="name"> P. Sherman </span>

	<h2>Member {id}</h2>
	<form action=""></form>

	<JsonView json={member} />
</Modal>

<style>
</style>
