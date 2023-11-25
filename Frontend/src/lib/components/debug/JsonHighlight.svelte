<script lang="ts">
	export let json: any;
	export let depth: number = Infinity;
	export let _cur = 0;
	export let _last = true;

	let items: any[];
	let isArray = false;
	let brackets = ['', ''];
	let collapsed = false;

	function getType(i: any): string {
		if (i === null) return 'null';
		return typeof i;
	}

	function format(i: any): string {
		const t = getType(i);
		if (t === 'string') return `"${i}"`;
		if (t === 'function') return 'f () {...}';
		if (t === 'symbol') return i.toString();
		return i;
	}

	function clicked() {
		collapsed = !collapsed;
	}

	function pressed(e: Event) {
		if (e instanceof KeyboardEvent && ['Enter', ' '].includes(e.key)) clicked();
	}

	$: {
		items = getType(json) === 'object' ? Object.keys(json) : [];
		isArray = Array.isArray(json);
		brackets = isArray ? ['[', ']'] : ['{', '}'];
	}

	$: collapsed = depth < _cur;
</script>

<div class="jsonView">
	{#if !items.length}
		<span class="_jsonBkt empty" class:isArray>{brackets[0]}{brackets[1]}</span>
		{#if !_last}
			<span class="_jsonSep">,</span>
		{/if}
	{:else if collapsed}
		<span
			class="_jsonBkt"
			class:isArray
			role="button"
			tabindex="0"
			on:click={clicked}
			on:keydown={pressed}
			>{brackets[0]}...{brackets[1]}
		</span>
		{#if !_last && collapsed}
			<span class="_jsonSep">,</span>
		{/if}
	{:else}
		<span
			class="_jsonBkt"
			class:isArray
			role="button"
			tabindex="0"
			on:click={clicked}
			on:keydown={pressed}
		>
			{brackets[0]}
		</span>
		<ul class="_jsonList">
			{#each items as i, idx}
				<li>
					{#if !isArray}
						<span class="_jsonKey">"{i}"</span><span class="_jsonSep">:</span>
					{/if}
					{#if getType(json[i]) === 'object'}
						<svelte:self json={json[i]} {depth} _cur={_cur + 1} _last={idx === items.length - 1} />
					{:else}
						<span class="_jsonVal {getType(json[i])}">{format(json[i])}</span>
						{#if idx < items.length - 1}
							<span class="_jsonSep commata">,</span>
						{/if}
					{/if}
				</li>
			{/each}
		</ul>
		<span
			class="_jsonBkt"
			class:isArray
			role="button"
			tabindex="0"
			on:click={clicked}
			on:keydown={pressed}
			>{brackets[1]}
		</span>
		{#if !_last}
			<span class="_jsonSep">,</span>
		{/if}
	{/if}
</div>

<style>
	.jsonView {
		--fontSize: 14px;
		--paddingLeft: 1rem;
		--yellow: #e5c07b;
		--yellow: #d19a66;
		--lightred: #ef596f;
		--red: #f44747;
		--red: #be5046;
		--lightblue: #2bbac5;
		--blue: #61afef;
		--lightgreen: #89ca78;
		--purple: #d55fde;
		--background: #1d2128;
		--backgroundHover: #393939;
		--font: #808080;
		--bracket: #808080;
		--bracketHover: #c4c4c4;
		--separator: #808080;
		background-color: var(--background);
		color: var(--font);
		padding: 0.25rem;
		font-family:
			JetBrains Mono,
			monospace;
		line-height: 1.5;
		font-weight: 200;
		font-size: var(--fontSize);
		border-radius: 0.4rem;
		border: 1px solid #000;
	}
	li {
		transition: 0.3s;
		max-width: fit-content;
		white-space: nowrap;
	}
	li:hover {
		background-color: var(--backgroundHover);
	}
	:where(._jsonList) {
		list-style: none;
		margin: 0;
		padding: 0;
		padding-left: var(--paddingLeft);
	}
	:where(._jsonBkt) {
		color: var(--bracket);
		padding-right: 5rem;
		transition: 0.3s;
	}
	:where(._jsonBkt):not(.empty):hover {
		cursor: pointer;
		color: var(--bracketHover);
		/* background: var(--backgroundHover); */
	}
	:where(._jsonSep) {
		color: var(--separator);
	}
	:where(._jsonSep.commata) {
		margin-left: -0.6em;
	}
	:where(._jsonKey) {
		color: var(--lightred);
	}
	:where(._jsonVal) {
		color: var(--blue);
	}
	:where(._jsonVal).string {
		color: var(--lightgreen);
	}
	:where(._jsonVal).number {
		color: var(--yellow);
	}
	:where(._jsonVal).boolean {
		color: var(--yellow);
	}
</style>
