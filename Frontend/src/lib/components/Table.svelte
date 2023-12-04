<script lang="ts">
	// https://github.com/rossrobino/drab/blob/main/src/lib/components/DataTable.svelte
	let className = '';
	export { className as class };
	export let id = '';
	type DataTableItem = Record<string | number, string | number | boolean | Date | undefined | null>;
	export let data: DataTableItem[];
	export let keys: string[] = Object.keys(data[0]);
	export let sortBy = keys[0];
	export let ascending = true;
	export let classThead = '';
	export let classTbody = '';
	export let classTr = '';
	export let classTheadTr = '';
	export let classTbodyTr = '';
	export let classTh = '';
	export let classTd = '';
	export let maxRows = 0;
	export let currentPage = 0;
	$: numberOfPages = Math.floor(data.length / maxRows) + 1;

	/**
	 * - sorts the data by the specified key
	 *
	 * @param key column to sort by
	 * @param toggleAscending whether or not to toggle ascending (first render off)
	 */
	const sort = (key: string, toggleAscending = true) => {
		if (key === sortBy && toggleAscending) {
			// reverse the sort if already selected
			ascending = !ascending;
		} else {
			// reset to true - start with ascending always
			ascending = true;
		}

		const collator = new Intl.Collator();

		data.sort((a, b) => {
			const aVal = a[key];
			const bVal = b[key];
			if (typeof aVal === 'number' && typeof bVal === 'number') {
				if (ascending) {
					return aVal - bVal;
				} else {
					return bVal - aVal;
				}
			} else if (typeof aVal === 'string' && typeof bVal === 'string') {
				if (ascending) {
					return collator.compare(aVal, bVal);
				} else {
					return collator.compare(bVal, aVal);
				}
			} else if (aVal instanceof Date && bVal instanceof Date) {
				if (ascending) {
					return aVal.getTime() - bVal.getTime();
				} else {
					return bVal.getTime() - aVal.getTime();
				}
			} else {
				if (ascending) {
					return aVal === bVal ? 0 : aVal ? -1 : 1;
				} else {
					return aVal === bVal ? 0 : aVal ? 1 : -1;
				}
			}
		});
		data = data; // trigger reactivity
		sortBy = key;
	};

	/**
	 * determines whether the row should appear
	 *
	 * @param i index of the row
	 * @param currentPage passed in for reactivity
	 */
	const showRow = (i: number, currentPage: number) => {
		if (!maxRows) return true;
		const overMin = i >= maxRows * currentPage;
		const underMax = i < maxRows * (currentPage + 1);
		return overMin && underMax;
	};

	sort(sortBy, false);
</script>

<table class={className} {id}>
	<thead class={classThead}>
		<tr class="{classTr} {classTheadTr}">
			{#each keys as key}
				<th class={classTh} on:click={() => sort(key)}>
					<slot name="th" {key} {sortBy}>{key}</slot>
				</th>
			{/each}
		</tr>
	</thead>
	<tbody class={classTbody}>
		{#each data as item, i}
			{#if showRow(i, currentPage)}
				<tr class="{classTr} {classTbodyTr}">
					{#each keys as key}
						<td class={classTd}>
							<slot name="td" {item} {key} {sortBy} value={item[key]}>
								{item[key]}
							</slot>
						</td>
					{/each}
				</tr>
			{/if}
		{/each}
	</tbody>
</table>

<slot name="controls" {maxRows} {numberOfPages} />
