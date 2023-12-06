<script lang="ts">
	import type { TableData, TableColumns } from '$lib/types';
	import { onMount } from 'svelte';
	import { fade } from 'svelte/transition';

	export let tableData: TableData[];
	export let tableColumns: TableColumns[] = [];
	export let caption: string = '';
	export let showTable = false;
	export let getRowId: (rowId: number) => void = (rowId: number) => {};

	let tableWrapper: HTMLDivElement;
	let rowCount = tableData.length;
	onMount(() => {
		// console.log('tableData', tableData);
		createTable(tableData);
	});
	// console.log('tableData', tableData);

	function createTable(jsonData: any[]) {
		tableWrapper.innerHTML = '';

		const table = document.createElement('table');
		const captionEl = table.createCaption();
		captionEl.innerHTML = `${caption}<span class="rowCount">Rows: ${rowCount}</span>`;

		const cols = tableColumns.map((item) => item.accessor);

		// Create header row
		const headerRow = document.createElement('tr');
		tableColumns.forEach((item) => {
			const th = document.createElement('th');
			th.innerText = item.header;
			// th.style.maxWidth = item.width;
			// th.style.minWidth = item.width;
			th.style.width = item.width;
			th.setAttribute('data-column', item.accessor);
			th.addEventListener('click', (e) => {
				sort(item.accessor);
			});
			const arrowIcon = document.createElement('i');
			arrowIcon.className = 'arrow-icon';
			th.appendChild(arrowIcon);
			headerRow.appendChild(th);
		});

		const thead = table.createTHead();
		thead.appendChild(headerRow);

		// Create data rows
		jsonData.forEach((item: any) => {
			const tr = document.createElement('tr');
			// tr.classList.add('shadow');
			let rowId = item[cols[0]];

			cols.forEach((col, i) => {
				const td = document.createElement('td');
				td.style.width = tableColumns[i].width;
				// td.style.minWidth = tableColumns[i].width;
				// td.style.maxWidth = tableColumns[i].width;
				td.innerText = item[col];
				tr.appendChild(td);
			});

			tr.addEventListener('click', () => getRowId(parseInt(rowId, 10)));
			table.appendChild(tr);
		});

		tableWrapper.appendChild(table);
	}

	//
	// SORT TABLE
	//
	type SymbolMap = {
		[key: string]: string;
	};
	let sortBy = { col: 'id', ascending: true };
	let symbolMap: SymbolMap = tableData[0]
		? Object.keys(tableData[0]).reduce((acc, cur) => ({ ...acc, [cur]: '' }), {})
		: {};

	const sort = (column: string) => {
		if (sortBy.col === column) {
			sortBy.ascending = !sortBy.ascending;
			symbolMap[column] = sortBy.ascending ? 'arrowUp' : 'arrowDown';
		} else {
			sortBy.col = column;
			sortBy.ascending = true;

			// Reset all symbols
			for (let s in symbolMap) {
				symbolMap[s] = '';
			}

			symbolMap[column] = 'arrowUp';
		}

		let sortModifier = sortBy.ascending ? 1 : -1;

		tableData = [...tableData].sort((a, b) => {
			const valueA = getSortableValue(a[column]);
			const valueB = getSortableValue(b[column]);

			if (typeof valueA === 'string' && typeof valueB === 'string') {
				return valueA.localeCompare(valueB) * sortModifier;
			} else if (typeof valueA === 'number' && typeof valueB === 'number') {
				return (valueA - valueB) * sortModifier;
			} else {
				return 0;
			}
		});
	};

	//
	// HELPER FUNCTIONS
	//
	function getSortableValue(value: any): string | number {
		if (typeof value === 'string') {
			// Parse as date if it's a valid date string  28.11.2023 17:12:29 (dd.mm.yyyy hh:mm:ss)
			const dateValue = Date.parse(value.replace(/(\d{2}).(\d{2}).(\d{4})/, '$2/$1/$3'));
			return isNaN(dateValue) ? value : dateValue;
		} else if (typeof value === 'number') {
			return value;
		} else {
			return '';
		}
	}

	function updateArrowIcons(column: string) {
		const allArrowIcons = document.querySelectorAll('.arrow-icon');
		allArrowIcons.forEach((icon) => {
			icon.classList.remove('arrowUp', 'arrowDown');
		});

		// Add arrow icon to the clicked column
		const arrowIcon = document.querySelector(`th[data-column="${column}"] .arrow-icon`);
		if (arrowIcon) {
			arrowIcon.classList.add(sortBy.ascending ? 'arrowUp' : 'arrowDown');
		}
	}

	$: {
		if (tableData && tableData.length > 0 && tableWrapper) {
			createTable(tableData);
			updateArrowIcons(sortBy.col);
		}
	}
	// $: console.log('symbolMap', symbolMap);
</script>

{#if showTable}
	<div
		class="tableWrapper"
		bind:this={tableWrapper}
		in:fade={{ duration: 200 }}
		out:fade={{ duration: 300 }}
	/>
{/if}

<!-- {JSON.stringify(tableData)} -->
<!-- 
{#if showTable}
	<div class="tableWrapper" in:fade={{ duration: 200 }} out:fade={{ duration: 300 }}>
		<table bind:this={table}>
			{#if caption}
				<caption>
					{caption}
					<span class="rowCount">Rows: {rowCount}</span>
				</caption>
			{/if}
			<thead>
				{#each tableColumns as column}
					<th style="width: {column.width}" on:click={() => sort(column.accessor)}>
						<nobr>
							{column.header + ' '}
							<i class={symbolMap[column.accessor]}>
								<ArrowUp />
							</i>
						</nobr>
					</th>
				{/each}
			</thead>
			<tbody>
				{#each tableData as row}
					<tr on:click={(e) => getRowId(parseInt(row.id))}>
						{#each tableColumns as column}
							<td class={column.accessor} style="width: {column.width}">
								{row[column.accessor]}
							</td>
						{/each}
					</tr>
				{/each}
			</tbody>
		</table>
	</div>
{/if} -->

<style>
	:global(.tableWrapper) {
		overflow-y: auto;
		max-height: calc(100vh - var(--header-height) - var(--footer-height) - var(--menu-height));
		/* max-height: 200px; */
		animation: fade 0.3s;
	}

	@keyframes fade {
		from {
			opacity: 0;
		}
		to {
			opacity: 1;
		}
	}

	:global(table) {
		/* border: 1px solid #ddd; */
		/* width: 100%; */
		max-width: var(--page-width);
		width: max-content;
		table-layout: fixed;
		/* border-collapse: collapse; */
		/* border-spacing: 0; */
	}

	:global(caption) {
		font-size: 1.5em;
		font-weight: bold;
		margin: 0;
		text-align: left;
		padding: 0.5em;
		position: sticky;
		top: 0;
		left: 0;
		height: 2em;
		background-color: #f5f5f5;
		max-width: var(--page-width);
	}
	:global(.rowCount) {
		float: right;
		padding-right: 1em;
		font-size: 0.9em;
		font-weight: normal;
	}
	:global(thead) {
		position: sticky;
		top: 3em;
		background-color: #f5f5f5;
	}
	:global(th) {
		display: inline-block;
		padding: 0.5em;
		background-color: #f5f5f5;
		cursor: pointer;
		text-align: left;
		transition: 0.3s;
		white-space: nowrap;
	}
	:global(th:hover) {
		color: var(--blue);
	}
	:global(tr) {
		transition: 0.3s;
		max-width: 99%;
	}

	:global(tr:nth-child(even)) {
		background-color: rgba(0, 0, 0, 0.01);
	}

	:global(tr:hover:not(thead tr)) {
		text-shadow: 0 0 0.1px #000050;
		cursor: pointer;
		background-color: var(--blue);
		box-shadow: var(--box-shadow);
		border-radius: 0.2em;
	}

	:global(tr:hover:not(thead tr)) {
		box-shadow: var(--box-shadow);
		position: relative;
		margin-inline: 2px;
	}
	:global(tr:hover:after:not(thead tr)) {
		position: absolute;
		top: 0;
		left: 0;
		display: block;
		width: 100%;
		height: 100%;
		pointer-events: none;
		content: '';
		border-radius: inherit;
		box-shadow: var(--box-shadow-after);
	}
	:global(td) {
		display: inline-block;
		padding: 0.4em;
		padding-right: 0.4em;
		padding-top: 0.55em;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	:global(i.arrow-icon) {
		display: inline-block;
		margin-left: 0.1em;
		width: 10px;
		height: 13px;
		color: #000;
		opacity: 0;
		transition:
			transform 0.2s,
			opacity 0.4s;
		background-image: url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="10" height="13" viewBox="0 0 10 13"%3E%3Cpath fill="currentColor" d="m 5 0.086 l -4.707 4.707 a 0.999 0.999 0 1 0 1.414 1.414 L 4 3.914 V 11.5 a 1 1 0 1 0 2 0 V 3.914 l 2.293 2.293 a 0.997 0.997 0 0 0 1.414 0 a 0.999 0.999 0 0 0 0 -1.414 L 5 0.086 z"%2F%3E%3C%2Fsvg%3E');
		background-repeat: no-repeat;
		background-position: center;
	}

	:global(i.arrowUp) {
		opacity: 1;
		transform: rotateX(0deg);
	}
	:global(i.arrowDown) {
		opacity: 1;
		transform: rotateX(180deg);
		transform-origin: center;
	}
</style>
