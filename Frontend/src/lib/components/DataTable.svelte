<script lang="ts">
	import { fade } from 'svelte/transition';
	export let tableData: any;
	export let caption: string = '';
	export let model: string;
	export let callback: any = (id: string) => {};

	let showTable = true;
	let table: HTMLTableElement;
	let rowCount = tableData.length;

	function getRowId(e: Event) {
		const target = e.target as HTMLElement;
		const row = target.closest('tr');
		if (!row) return;
		const id = row.querySelector('td.id')?.textContent;
		// console.log('id', id);
		console.log('callback', callback, id);
		callback(id);
	}

	//
	// SORT TABLE
	//
	function sortTable(column: number) {
		let rows,
			switching,
			i,
			x,
			y,
			shouldSwitch,
			dir,
			switchcount = 0;
		switching = true;
		dir = 'asc';
		while (switching) {
			switching = false;
			rows = table.rows;
			for (i = 0; i < rows.length - 1; i++) {
				shouldSwitch = false;
				x = rows[i].querySelectorAll('td')[column];
				y = rows[i + 1].querySelectorAll('td')[column];
				if (dir === 'asc') {
					if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
						shouldSwitch = true;
						break;
					}
				} else if (dir === 'desc') {
					if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
						shouldSwitch = true;
						break;
					}
				}
			}
			if (shouldSwitch) {
				rows[i].parentNode?.insertBefore(rows[i + 1], rows[i]);
				switching = true;
				switchcount++;
			} else {
				if (switchcount === 0 && dir === 'asc') {
					dir = 'desc';
					switching = true;
				}
			}
		}
	}

	const tableColumns = [
		{
			endpoint: 'member',
			columns: [
				{
					header: 'Id',
					accessor: 'id',
					width: '10%'
				},
				{
					header: 'Name',
					accessor: 'name',
					width: '20%'
				},
				{
					header: 'Email',
					accessor: 'email',
					width: '20%'
				},
				{
					header: 'Phone',
					accessor: 'phone',
					width: '20%'
				},
				{
					header: 'City',
					accessor: 'location_city',
					width: '20%'
				},
				{
					header: 'Created',
					accessor: 'created_at',
					width: '10%'
				}
			]
		},
		{
			endpoint: 'location',
			columns: [
				{
					header: 'Id',
					accessor: 'id',
					width: '10%'
				},
				{
					header: 'Address',
					accessor: 'address',
					width: '20%'
				},
				{
					header: 'City',
					accessor: 'city',
					width: '20%'
				},
				{
					header: 'Email',
					accessor: 'email',
					width: '20%'
				},
				{
					header: 'Open from',
					accessor: 'opening_hour_from',
					width: '10%'
				},
				{
					header: 'Opening Days',
					accessor: 'opening_days',
					width: '10%'
				},
				{
					header: 'Open to',
					accessor: 'opening_hour_to',
					width: '10%'
				},
				{
					header: 'Max Bookins',
					accessor: 'max_booking',
					width: '10%'
				},
				{
					header: 'Slot duration',
					accessor: 'slot_duration',
					width: '10%'
				},
				{
					header: 'Workspaces',
					accessor: 'workspaces',
					width: '10%'
				}
			]
		},
		{
			endpoint: 'staff',
			columns: [
				{
					header: 'Id',
					accessor: 'id',
					width: '10%'
				},
				{
					header: 'Name',
					accessor: 'name',
					width: '20%'
				},
				{
					header: 'Email',
					accessor: 'email',
					width: '20%'
				},
				{
					header: 'Phone',
					accessor: 'phone',
					width: '20%'
				},
				{
					header: 'City',
					accessor: 'location_city',
					width: '20%'
				},

				{
					header: 'Created',
					accessor: 'created_at',
					width: '10%'
				}
			]
		},
		{
			endpoint: 'booking',
			columns: [
				{
					header: 'Id',
					accessor: 'id',
					width: '10%'
				},
				{
					header: 'Member',
					accessor: 'member_name',
					width: '20%'
				},
				{
					header: 'Date',
					accessor: 'date',
					width: '20%'
				},
				{
					header: 'Location',
					accessor: 'location_city',
					width: '20%'
				},
				{
					header: 'Time',
					accessor: 'time',
					width: '10%'
				},
				{
					header: 'Slots',
					accessor: 'slots',
					width: '10%'
				},
				{
					header: 'Created',
					accessor: 'created_at',
					width: '10%'
				}
			]
		}
	];
</script>

{#if showTable}
	<div class="tableWrapper" transition:fade={{ delay: 250, duration: 300 }}>
		<table id="table_{model}" bind:this={table}>
			{#if caption}
				<caption>
					{caption}
					<span class="rowCount">Rows: {rowCount}</span>
				</caption>
			{/if}
			<thead>
				{#each tableColumns as tableColumn}
					{#if tableColumn.endpoint === model}
						{#each tableColumn.columns as column, index}
							<th style="width: {column.width}" on:click={() => sortTable(index)}>
								{column.header}
							</th>
						{/each}
					{/if}
				{/each}
			</thead>
			<tbody>
				{#each tableData as row}
					<tr on:click={(e) => getRowId(e)}>
						{#each tableColumns as tableColumn}
							{#if tableColumn.endpoint === model}
								{#each tableColumn.columns as column}
									<td class={column.accessor} style="width: {column.width}">
										{row[column.accessor]}
									</td>
								{/each}
							{/if}
						{/each}
					</tr>
				{/each}
			</tbody>
		</table>
	</div>
{:else}
	<p>No model</p>
{/if}

<style>
	.tableWrapper {
		overflow-y: auto;
		max-height: calc(100vh - var(--header-height) - var(--footer-height) - var(--menu-height));
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

	th {
		padding: 0.5em;
		position: sticky;
		background-color: #f5f5f5;
		top: 3em;
		cursor: pointer;
	}
	th:hover {
		color: grey;
	}
	tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	tr:hover {
		background-color: #ddd;
	}
	td {
		padding: 0.5em;
		padding-right: 1em;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		max-width: 20em;
	}
</style>
