<script lang="ts">
	import { fade } from 'svelte/transition';

	export let isOpen: boolean = false;
	export let onClose: () => void;

	let backdrop: HTMLDivElement;

	function handleClickOutside(event: Event) {
		if (event.target === backdrop) {
			onClose();
		}
	}

	function handleKeydown(event: KeyboardEvent) {
		if (event.key === 'Escape') {
			onClose();
		}
	}

	// $: console.log('isOpen MODAL', isOpen);
</script>

<svelte:window on:keydown={handleKeydown} />

{#if isOpen}
	<!-- svelte-ignore a11y-no-static-element-interactions -->
	<!-- svelte-ignore a11y-click-events-have-key-events -->
	<div
		transition:fade={{ duration: 200 }}
		class="modalBackdrop"
		bind:this={backdrop}
		on:click={handleClickOutside}
	>
		<div class="modalWrapper">
			<div class="modalHeader">
				<h2>
					<slot name="header" />
				</h2>
			</div>
			<div class="modalBody">
				<slot />
			</div>
			<div class="modalFooter">
				<slot name="footer" />
				<button on:click={onClose}>Close</button>
			</div>
			<div class="modalBeyondFooter">
				<slot name="beyondFooter" />
			</div>
		</div>
	</div>
{/if}

<style>
	.modalBackdrop {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 1000;
		width: 100vw;
		height: 100vh;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		justify-content: center;
		align-items: start;
	}

	.modalWrapper {
		width: 80ch;
		max-width: 95vw;
		max-height: 90vh;
		margin-top: var(--header-height);
		display: flex;
		flex-direction: column;
		border-radius: 10px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
		background-color: white;
	}

	.modalHeader {
		width: 100%;
		padding-inline: 1rem;
	}

	.modalBody {
		width: 100%;
		height: 50%;
		overflow: auto;
		padding: 1rem;
		padding-bottom: 0;
	}

	.modalFooter {
		width: 100%;
		padding: 1rem;
		padding-top: 0;
		display: flex;
		gap: 1rem;
	}

	.modalBeyondFooter {
		width: 100%;
		padding-inline: 1rem;
	}
</style>
