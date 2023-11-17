<script lang="ts">
	import { writable, readable } from 'svelte/store';
	import {
		Render,
		Subscribe,
		createTable,
		createRender,
		type DataLabel
	} from 'svelte-headless-table';
	export let tableData: any;
	export let model: string;
	console.log('tableData', tableData);

	const data = readable(tableData);

	// const table = createTable(data);
	const table = createTable(data, {
		// select: addSelectedRows({ ... }),
	});
	let columns;

	switch (model) {
		case 'member':
			columns = table.createColumns([
				table.column({
					header: 'Id',
					accessor: 'id'
				}),
				table.column({
					header: 'Name',
					accessor: 'name'
				}),
				table.column({
					header: 'Email',
					accessor: 'email'
				}),
				table.column({
					header: 'Phone',
					accessor: 'phone'
				}),
				table.column({
					header: 'City',
					accessor: 'location_city'
				}),

				table.column({
					header: 'Created',
					accessor: 'created_at'
				})
			]);
			break;
		case 'location':
			columns = table.createColumns([
				table.column({
					header: 'Id',
					accessor: 'id'
				}),
				table.column({
					header: 'Address',
					accessor: 'address'
				}),
				table.column({
					header: 'City',
					accessor: 'city'
				}),
				table.column({
					header: 'Email',
					accessor: 'email'
				}),
				table.column({
					header: 'Open from',
					accessor: 'opening_hour_from'
				}),
				table.column({
					header: 'Opening Days',
					accessor: 'opening_days'
				}),
				table.column({
					header: 'Open to',
					accessor: 'opening_hour_to'
				}),
				table.column({
					header: 'Max Bookins',
					accessor: 'max_booking'
				}),
				table.column({
					header: 'Slot duration',
					accessor: 'slot_duration'
				}),
				table.column({
					header: 'Workspaces',
					accessor: 'workspaces'
				})
			]);
			break;
		case 'staff':
			columns = table.createColumns([
				table.column({
					header: 'Id',
					accessor: 'id'
				}),
				table.column({
					header: 'Name',
					accessor: 'name'
				}),
				table.column({
					header: 'Email',
					accessor: 'email'
				}),
				table.column({
					header: 'Phone',
					accessor: 'phone'
				}),
				table.column({
					header: 'City',
					accessor: 'location_city'
				}),

				table.column({
					header: 'Created',
					accessor: 'created_at'
				})
			]);
			break;
		case 'booking':
			columns = table.createColumns([
				table.column({
					header: 'Id',
					accessor: 'id'
				}),
				table.column({
					header: 'Member',
					accessor: 'member_name'
				}),
				table.column({
					header: 'Date',
					accessor: 'date'
				}),
				table.column({
					header: 'Location',
					accessor: 'location_city'
				}),
				table.column({
					header: 'Time',
					accessor: 'time'
				}),
				table.column({
					header: 'Slots',
					accessor: 'slots'
				}),
				table.column({
					header: 'Created',
					accessor: 'created_at'
				})
			]);
			break;
	}

	const { headerRows, rows, tableAttrs, tableBodyAttrs } = table.createViewModel(columns);
</script>

<table {...$tableAttrs}>
	<thead>
		{#each $headerRows as headerRow (headerRow.id)}
			<Subscribe rowAttrs={headerRow.attrs()} let:rowAttrs>
				<tr {...rowAttrs}>
					{#each headerRow.cells as cell (cell.id)}
						<Subscribe attrs={cell.attrs()} let:attrs>
							<th {...attrs}>
								<Render of={cell.render()} />
							</th>
						</Subscribe>
					{/each}
				</tr>
			</Subscribe>
		{/each}
	</thead>
	<tbody {...$tableBodyAttrs}>
		{#each $rows as row (row.id)}
			<Subscribe rowAttrs={row.attrs()} let:rowAttrs>
				<tr {...rowAttrs}>
					{#each row.cells as cell (cell.id)}
						<Subscribe attrs={cell.attrs()} let:attrs>
							<td {...attrs}>
								<Render of={cell.render()} />
							</td>
						</Subscribe>
					{/each}
				</tr>
			</Subscribe>
		{/each}
	</tbody>
</table>
