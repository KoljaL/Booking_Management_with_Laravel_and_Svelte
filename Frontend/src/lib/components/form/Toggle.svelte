<script lang="ts">
	export let checked: boolean = false;
	export let label = '';
	export let onChange: (checked: boolean) => void = (checked) => {};
</script>

<label class="toggle">
	<span class="toggle-label">{label}</span>
	<input
		class="toggle-checkbox slider"
		type="checkbox"
		bind:checked
		on:change={() => onChange(checked)}
	/>
	<div class="toggle-switch"></div>
</label>

<style>
	/* .toggle {
		display: block;
		white-space: nowrap;
		color: rgb(166, 166, 166);
		user-select: none;
		cursor: pointer;
		width: fit-content;
		border: 1px solid #ccc;
		box-sizing: border-box;
		-webkit-tap-highlight-color: rgba(249, 103, 67, 0.1);
		transition-duration: 0.5s;
		transition-timing-function: cubic-bezier(0.23, 1, 0.32, 1);
		transition-delay: 0s;
		margin: 0px;
		transition-property: background, background-color, background-image, border;
	}

	.toggle-label {
		width: fit-content;
		display: inline-block;
		box-sizing: border-box;
		-webkit-tap-highlight-color: rgba(249, 103, 67, 0.1);
		transition-duration: 0.5s;
		transition-timing-function: cubic-bezier(0.23, 1, 0.32, 1);
		transition-delay: 0s;
		margin: 0px;
		padding: 0px;
		transition-property: background, background-color, background-image, border;
	}

	.toggle-checkbox {
		top: -1px;
		appearance: none;
		border: 0px none rgba(0, 0, 0, 0);
		border-radius: 6.66667px;
		outline: rgb(255, 255, 255) none 0px;
		width: calc(100% - 0.6em);
		max-width: 26.6667px;
		height: 13.3281px;
		margin: 0px 8px 0px 0px;
		position: relative;
		box-sizing: border-box;
		-webkit-tap-highlight-color: rgba(249, 103, 67, 0.1);
		transition-duration: 0.5s;
		transition-timing-function: cubic-bezier(0.23, 1, 0.32, 1);
		transition-delay: 0s;
		padding: 0px;
		transition-property: background, background-color, background-image, border;
	}

	input[type='checkbox']:before {
		content: '';
		background: rgba(0, 0, 0, 0.1);
		box-sizing: content-box;
		border-radius: 1em;
		width: 100%;
		height: 100%;
		padding: 2px;
		display: block;
		position: absolute;
		top: 0;
		left: 0;
		border: 1px solid grey;
	}

	input[type='checkbox']:checked:before {
		background: var(--blue);
	}

	input[type='checkbox']:checked:after {
		left: calc(100% - 1em + 2px);
	}

	input[type='checkbox']:after {
		content: '';
		background: #fff;
		border-radius: 1em;
		width: 1em;
		height: 1em;
		transition:
			background 0.2s ease-out,
			left 0.2s ease-out;
		display: block;
		position: absolute;
		top: 2px;
		left: 2px;
		box-shadow:
			0 0 1px #0006,
			0 4px 2px #0000001a;
	} */
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
