<script lang="ts">
	import { onMount, onDestroy } from 'svelte';

	export let isOpen: boolean = false;
	export let onClose: () => void;
	// export let callback: () => void;

	let backdrop: HTMLDivElement;

	const handleClickOutside = (event: Event) => {
		// // console.log('event', event.target);
		// console.log('backdrop', backdrop);
		// console.log('isOpen', isOpen);
		if (event.target === backdrop) {
			// console.log('click outside');
			// onClose();
		}
	};

	$: console.log('isOpen MODAL', isOpen);
</script>

{#if isOpen}
	<!-- svelte-ignore a11y-no-static-element-interactions -->
	<!-- svelte-ignore a11y-click-events-have-key-events -->
	<div
		class="modalBackdrop"
		bind:this={backdrop}
		on:click={handleClickOutside}
		on:keypress={handleClickOutside}
	>
		<div class="modalWrapper">
			<div class="modalHeader">
				<h2>
					<slot name="name">
						<span class="missing">Unknown name</span>
					</slot>
				</h2>
			</div>
			<div class="modalBody">
				<slot />
			</div>
			<div class="modalFooter">
				<button on:click={onClose}>Close Modal</button>
				<!-- <button on:click={callback}>Callback</button> -->
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
		align-items: center;
	}

	.modalWrapper {
		width: 100%;
		height: 100%;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		/* pointer-events: none; */
	}

	.modalHeader {
		width: 50%;
		height: 10%;
		background-color: white;
		border-radius: 10px 10px 0 0;
		z-index: 1001;
	}

	.modalBody {
		width: 50%;
		height: 50%;
		background-color: white;
		padding: 1rem;
		z-index: 1001;
	}

	.modalFooter {
		width: 50%;
		height: 10%;
		background-color: white;
		border-radius: 0 0 10px 10px;
		z-index: 1001;
	}
</style>
