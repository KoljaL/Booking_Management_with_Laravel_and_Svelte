<script lang="ts">
	// origin: https://github.com/ukchukx/svelte-inline-input/tree/master
	import { tick, createEventDispatcher } from 'svelte';

	const dispatch = createEventDispatcher();

	// Props
	export let value: string | number = '';
	export let type = 'text';
	export let placeholder = '';
	export let labelClasses = '';
	export let inputClasses = '';
	export let rows = 2;
	export let cols = 20;
	export let options: { label: string; value: string }[] = [];

	let editing = false;
	let inputEl: HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement;
	let label: string | number;
	let selectedIndex = options.findIndex((o) => o.value === value);

	// Computed
	$: isText = type === 'text';
	$: isNumber = type === 'number';
	$: isTextArea = type === 'textarea';
	$: isSelect = type === 'select';
	$: if (isNumber) {
		label = value === '' ? placeholder : value;
	} else if (isText || isTextArea) {
		label = value ? value : placeholder;
	} else {
		// Select
		label = selectedIndex === -1 ? placeholder : options[selectedIndex].label;
	}

	const toggle = async (e: Event) => {
		console.log('toggle', e);
		editing = !editing;

		if (editing) {
			await tick();
			inputEl.focus();
		}
	};

	const handleInput = (e: Event) => {
		const target = e.target as HTMLInputElement;
		value = isNumber ? +target.value : target.value;
		// value = isNumber ? +e.target.value : e.target.value;
	};

	const handleEnter = (e: KeyboardEvent) => {
		if (e.keyCode === 13) inputEl.blur();
	};

	const handleBlur = (e: Event) => {
		toggle(e);
		dispatch('blur', value);
	};

	const handleChange = (e: Event) => {
		const target = e.target as HTMLSelectElement;
		selectedIndex = placeholder ? target.selectedIndex - 1 : target.selectedIndex;
		value = options[selectedIndex].value;
	};
</script>

{#if editing && (isText || isNumber)}
	<input
		class={inputClasses}
		bind:this={inputEl}
		{type}
		{value}
		{placeholder}
		on:input={handleInput}
		on:keyup={handleEnter}
		on:blur={handleBlur}
	/>
{:else if editing && isTextArea}
	<textarea
		class={inputClasses}
		bind:this={inputEl}
		{placeholder}
		{value}
		{rows}
		{cols}
		on:input={handleInput}
		on:blur={handleBlur}
	/>
{:else if editing && isSelect}
	<select
		class={inputClasses}
		bind:this={inputEl}
		on:change={handleChange}
		{value}
		on:blur={handleBlur}
	>
		{#if placeholder}
			<option selected value disabled>{placeholder}</option>
		{/if}
		{#each options as { label, value }, i (value)}
			<option {value}>
				{label}
			</option>
		{/each}
	</select>
{:else}
	<span class={labelClasses} on:click={toggle} on:keydown={toggle} tabindex="0" role="button">
		{label}<slot name="selectCaret"
			>{#if isSelect}<span>&#9660;</span>{/if}</slot
		>
	</span>
{/if}
