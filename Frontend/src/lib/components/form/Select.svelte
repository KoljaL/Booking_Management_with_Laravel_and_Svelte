<script lang="ts">
	import { slide } from 'svelte/transition';
	import type { List } from '$lib/types';

	export let options: List[];
	console.log('options', options);
	export let value: string = '';
	export let label: string = 'Select';
	export let name: string = 'select';
	// export let onInput: (event: Event) => void;
	// export let onFocus?: (event: Event | null) => void;
	// export let onBlur: (event: Event | null) => void;
	// export let onChange?: (event: Event) => void;
	// on:input={onInput} on:focus={onFocus} on:blur={onBlur}   on:change={onChange}

	$: value = options[0]?.value;
	$: selectedKey = options[0]?.key;

	let open: boolean = false;
	let width =
		options.reduce((acc, cur) => {
			return cur.key.length > acc ? cur.key.length : acc;
		}, 0) *
			0.75 +
		'rem';
	console.log('width', width);

	function selectOption(event: Event) {
		const target = event.target as HTMLElement;
		value = target.dataset.value as string;
		selectedKey = target.innerText;
		open = false;
	}

	const handleDropdownFocusLoss = (event: FocusEvent) => {
		if (event.relatedTarget === null) {
			open = false;
		}
	};
</script>

<div class="selectWrapper" style="--width:{width}">
	<label on:focusout={handleDropdownFocusLoss}>
		{label}
		<button class="toggleButton" on:click={() => (open = !open)}>
			{selectedKey}
			<span class="arrow" class:open />
		</button>
		<input type="hidden" {name} bind:value />
	</label>

	{#if open}
		<div
			transition:slide={{ delay: 0, duration: 200 }}
			class="options"
			on:click={selectOption}
			on:keydown={selectOption}
			role="button"
			tabindex="0"
		>
			<ul>
				{#each options as { key, value } (value)}
					<li>
						<button class="optionButton" data-value={value} class:current={selectedKey === key}>
							{key}
						</button>
					</li>
				{/each}
			</ul>
		</div>
	{/if}
</div>

<style>
	.selectWrapper {
		position: relative;
		width: fit-content;
	}

	label {
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		gap: 0.25rem;
	}
	.toggleButton {
		min-width: fit-content;
		width: var(--width);
		text-align: left;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-right: 0;
		white-space: nowrap;
	}

	.arrow {
		margin-left: 0.5rem;
		width: 1rem;
		height: 1rem;
		rotate: 180deg;
		background: url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="24" height="24" viewBox="0 0 24 24"%3E%3Cpath fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m17 14l-5-5l-5 5"%2F%3E%3C%2Fsvg%3E')
			no-repeat center;
		background-size: contain;
		transition: transform 0.2s ease-in-out;
	}

	.arrow.open {
		transform: rotate(180deg);
	}
	.options {
		min-width: fit-content;
		width: var(--width);
		position: absolute;
		top: 100%;
		right: 0;
		background: var(--colors-gray12);
		overflow: hidden;
		box-shadow: var(--box-shadow);
		border-radius: var(--border-radius-m, 0.5rem);
		z-index: 1;
	}

	ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	li {
		margin: 0;
		padding: 0;
	}

	button.optionButton {
		width: 100%;
		text-align: left;
		border: none;
		border-radius: 0;
		background: none;
		cursor: pointer;
		box-shadow: none;
		margin: 0;
		padding: 0.25rem 0.5rem;
		transition: all 0.2s ease-in-out;
	}
	button.optionButton::after {
		box-shadow: none;
	}

	button.optionButton.current {
		background: var(--colors-gray11);
	}
	button.optionButton:hover {
		background: var(--colors-gray11);
	}
</style>
