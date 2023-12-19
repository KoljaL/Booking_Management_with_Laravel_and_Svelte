<script lang="ts">
	import type { TableData, TableColumn } from '$lib/types';
	import { fade } from 'svelte/transition';
	import { flip } from 'svelte/animate';
	import { onMount } from 'svelte';
	import Check from '$lib/icons/Check.svelte';
	import Close from '$lib/icons/Close.svelte';

	export let tableData: TableData[];
	export let tableColumns: TableColumn[] = [];
	export let caption: string = '';
	export let showTable = false;
	export let getRowId: (rowId: number) => void = (rowId: number) => {};

	let table: HTMLTableElement;
	let tableWrapper: HTMLDivElement;
	let rowCount = tableData.length;
	let parentHeight = '100%';

	onMount(() => {
		rowCount = tableData.length;
		parentHeight = tableWrapper?.parentElement?.clientHeight + 'px' || '100%';
	});

	const sort = (accessor: any, type: TableColumn['type']) => {
		let sortOrder = tableColumns.find((column) => column.accessor === accessor)?.sortOrder;
		tableData = [...tableData].sort((a, b) => {
			const valueA: any = a[accessor];
			const valueB: any = b[accessor];

			if (type === 'date') {
				if (sortOrder === 'asc') {
					return new Date(valueA).getTime() - new Date(valueB).getTime();
				} else {
					return new Date(valueB).getTime() - new Date(valueA).getTime();
				}
			} else if (type === 'number') {
				if (sortOrder === 'asc') {
					return valueA - valueB;
				} else {
					return valueB - valueA;
				}
			} else if (type === 'string') {
				if (sortOrder === 'asc') {
					return valueA.localeCompare(valueB);
				} else {
					return valueB.localeCompare(valueA);
				}
			}
		});

		tableColumns = tableColumns.map((column) => {
			if (column.accessor === accessor) {
				column.sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
			} else {
				column.sortOrder = null;
			}
			return column;
		});
	};
</script>

{#if showTable}
	<div
		class="tableWrapper"
		bind:this={tableWrapper}
		in:fade={{ duration: 200 }}
		out:fade={{ duration: 300 }}
		style="--parentHeight: {parentHeight}"
	>
		<table bind:this={table}>
			{#if caption}
				<caption>
					{caption}
					<span class="rowCount">Rows: {rowCount}</span>
				</caption>
			{/if}
			<thead>
				{#each tableColumns as column}
					<th style="width: {column.width}" on:click={() => sort(column.accessor, column.type)}>
						<nobr>
							{column.header + ' '}
							<i class="arrow-icon {column.sortOrder}" />
						</nobr>
					</th>
				{/each}
			</thead>
			<tbody>
				{#each tableData as row (row)}
					<tr on:click={(e) => getRowId(parseInt(row.id))}>
						{#each tableColumns as column}
							<td
								class={column.accessor}
								style="width: {column.width}"
								title={row[column.accessor]}
							>
								{#if column.type === 'date'}
									{new Date(row[column.accessor]).toLocaleString('de-DE', {
										year: 'numeric',
										month: '2-digit',
										day: '2-digit',
										hour: '2-digit',
										minute: '2-digit'
									})}
								{:else if row[column.accessor] === true}
									<Check />
								{:else if row[column.accessor] === false}
									<Close />
								{:else if row[column.accessor] === null}
									&nbsp;
								{:else}
									{row[column.accessor]}
								{/if}
							</td>
						{/each}
					</tr>
				{/each}
			</tbody>
		</table>
	</div>
{/if}

<style>
	.tableWrapper {
		overflow-x: auto;
		max-width: var(--page-width);
		height: var(--parentHeight);
	}
	table {
		max-width: var(--page-width);
		width: max-content;
		table-layout: fixed;
	}

	caption {
		position: sticky;
		top: 0;
		left: 0;
		/* height: 25px; */
		max-width: var(--page-width);
		padding-top: 0.5em;
		padding-bottom: 0.5em;
		font-size: 1.5em;
		font-weight: bold;
		text-align: left;
		background-color: #f5f5f5;
		box-shadow: 0px 5px 0px 0px var(--colors-gray13);
	}
	.rowCount {
		float: right;
		padding-right: 1em;
		font-size: 0.9em;
		font-weight: normal;
	}
	thead {
		position: sticky;
		top: 51px;
		background-color: #f5f5f5;
	}
	th {
		display: inline-block;
		/* padding: 0.5em; */
		padding-right: 0.5em;
		padding-bottom: 0.25em;
		padding-top: 0.25em;
		background-color: #f5f5f5;
		cursor: pointer;
		text-align: left;
		transition: 0.3s;
		white-space: nowrap;
	}
	th:hover {
		text-decoration: underline;
	}
	tr {
		transition: 0.3s;
		box-shadow: 0px 1px 0.1px 0px rgba(0, 0, 0, 0.1);
		margin-block: 1px;
		display: flex;
	}

	/* tr:nth-child(even) {	} */

	tr:hover:not(thead tr) {
		text-shadow: 0 0 0.1px #000050;
		cursor: pointer;
		background-color: var(--blue);
		box-shadow: var(--box-shadow);
		border-radius: 0.2em;
	}

	tr:hover:not(thead tr) {
		box-shadow: var(--box-shadow);
		position: relative;
		/* margin-inline: 2px; */
	}
	tr:hover:after:not(thead tr) {
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
	td {
		display: inline-block;
		/* padding: 0.4em; */
		padding-right: 0.5em;
		padding-top: 0.75em;
		padding-bottom: 0.55em;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		margin-top: -2px;
		margin-bottom: -2px;
		/* border-bottom: 1px solid rgba(0, 0, 0, 0.1); */
	}
	td :global(svg) {
		width: 10px;
		height: 10px;
		scale: 1.4;
	}

	i.arrow-icon {
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

	i.asc {
		opacity: 1;
		transform: rotateX(0deg);
	}
	i.desc {
		opacity: 1;
		transform: rotateX(180deg);
		transform-origin: center;
	}
</style>
