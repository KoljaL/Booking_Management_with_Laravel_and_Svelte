<script lang="ts">
	import { fade } from 'svelte/transition';
	import ArrowUp from '$lib/icons/ArrowUp.svelte';
	import type { TableData, TableColumns } from '$lib/types';

	export let tableData: TableData[];
	export let tableColumns: TableColumns[] = [];
	export let caption: string = '';
	export let showTable = false;
	export let getRowId: (rowId: number) => void = (rowId: number) => {};

	let table: HTMLTableElement;
	let rowCount = tableData.length;

	console.log('tableData', tableData);
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

	$: sort = (column: string) => {
		if (sortBy.col == column) {
			sortBy.ascending = !sortBy.ascending;
			symbolMap[column] = symbolMap[column] === 'arrowUp' ? 'arrowDown' : 'arrowUp';
		} else {
			sortBy.col = column;
			sortBy.ascending = true;
			for (let s in symbolMap) {
				symbolMap[s] = '';
			}
			symbolMap[column] = 'arrowUp';
		}
		let sortModifier = sortBy.ascending ? 1 : -1;
		let sort = (a: SymbolMap, b: SymbolMap) =>
			a[column] < b[column] ? -1 * sortModifier : a[column] > b[column] ? 1 * sortModifier : 0;
		tableData = tableData.sort(sort);
	};
</script>

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
{/if}

<style>
	i {
		width: 10px;
		height: 13px;
		display: inline-block;
		opacity: 0;
		color: #000;
		transition:
			transform 0.2s,
			opacity 0.4s;
	}
	i.arrowUp {
		opacity: 1;
		transform: rotateX(0deg);
	}
	i.arrowDown {
		opacity: 1;
		transform: rotateX(180deg);
		transform-origin: center;
	}
	.tableWrapper {
		overflow-y: auto;
		max-height: calc(100vh - var(--header-height) - var(--footer-height) - var(--menu-height));
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

	table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	caption {
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
	.rowCount {
		float: right;
		padding-right: 1em;
		font-size: 0.9em;
		font-weight: normal;
	}
	thead {
		position: sticky;
		top: 3em;
		background-color: #f5f5f5;
	}
	th {
		display: inline-block;
		padding: 0.5em;
		background-color: #f5f5f5;
		cursor: pointer;
		text-align: left;
		transition: 0.3s;
	}
	th:hover {
		color: var(--blue);
	}
	tr {
		transition: 0.3s;
	}

	tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	tr:hover {
		text-shadow: 0 0 0.1px #000050;
		cursor: pointer;
		background-color: #ddd;
	}
	td {
		display: inline-block;
		padding: 0.5em;
		padding-right: 1em;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		max-width: 20em;
	}
</style>
