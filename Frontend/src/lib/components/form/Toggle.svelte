<script lang="ts">
	export let checked: boolean = false;
	export let label = '';
	export let onChange: (checked: boolean) => void = (checked) => {};
</script>

<label class="toggle">
	<span class="toggle-label">{label}</span>
	<input class="toggle-checkbox" type="checkbox" bind:checked on:change={() => onChange(checked)} />
	<div class="toggle-switch"></div>
</label>

<style>
	.toggle {
		cursor: pointer;
		display: inline-block;
		display: flex;
		align-items: center;
		white-space: nowrap;
		gap: 0.25rem;
	}

	.toggle-switch {
		display: inline-block;
		background: #ccc;
		--width: 30px;
		--height: calc(var(--width) / 1.8125);
		width: var(--width);
		height: var(--height);
		border-radius: calc(var(--width) / 3.625);
		position: relative;
		vertical-align: middle;
		transition: background 0.25s;
	}
	.toggle-switch:before,
	.toggle-switch:after {
		content: '';
	}
	.toggle-switch:before {
		display: block;
		background: linear-gradient(to bottom, #fff 0%, #eee 100%);
		border-radius: 50%;
		box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.25);
		width: calc(var(--width) / 2.4166666667);
		height: calc(var(--width) / 2.4166666667);
		position: absolute;
		top: calc((var(--width) / 14.5));
		left: calc((var(--width) / 14.5));
		transition: left 0.25s;
	}
	.toggle:hover .toggle-switch:before {
		background: linear-gradient(to bottom, #fff 0%, #fff 100%);
		box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.5);
	}
	.toggle-checkbox:checked + .toggle-switch {
		background: var(--blue);
	}
	.toggle-checkbox:checked + .toggle-switch:before {
		left: calc(var(--width) / 1.9333333333);
	}

	.toggle-checkbox {
		position: absolute;
		visibility: hidden;
	}

	/* .toggle-label {	} */
</style>
