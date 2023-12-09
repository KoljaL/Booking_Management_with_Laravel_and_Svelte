<script>
	// @ts-nocheck
	// https://dev.to/finnhvman/grayscale-color-palette-with-equal-contrast-ratios-2pgl

	let contransRatio = 1.10362;
	let colors;
	let countEl;
	let CSS = '';
	let count = 1;
	let previousLuminance = 0;
	let addRed = 0;
	let addGreen = 0;
	let addBlue = 0;
	let redRange;
	let greenRange;
	let blueRange;
	let contrastRange;
	let mode = 'light';

	$: if (contransRatio && colors) {
		createColors();
	}
	function createColors() {
		count = 1;
		previousLuminance = 0;
		colors.innerHTML = '';
		CSS = '\n:root {\n\t';
		addColor(addRed, addGreen, addBlue);

		for (let v = 0; v < 256; v++) {
			const luminance = getLuminance(v, v, v);
			if (contransRatio < (luminance + 0.05) / (previousLuminance + 0.05)) {
				count++;
				const R = v + addRed;
				const G = v + addGreen;
				const B = v + addBlue;
				addColor(R, G, B);
				previousLuminance = luminance;
			}
		}
		countEl.textContent = count;
		CSS += '\n }';
		// CSS = CSS.replace(/^\s*[\r\n]/gm, '');
	}

	const getChannel = (channel) => {
		const normalized = channel / 255;
		if (normalized <= 0.03928) {
			return normalized / 12.92;
		} else {
			return ((normalized + 0.055) / 1.055) ** 2.4;
		}
	};

	const getLuminance = (r, g, b) => {
		let R = getChannel(r);
		let G = getChannel(g);
		let B = getChannel(b);

		return 0.2126 * R + 0.7152 * G + 0.0722 * B;
	};

	const addColor = (r, g, b) => {
		const li = document.createElement('li');
		if (r < 128) {
			li.style.color = 'white';
		}

		li.innerHTML = `#${r.toString(16).padStart(2, '0')}
                     ${g.toString(16).padStart(2, '0')}
                     ${b.toString(16).padStart(2, '0')}<br/>
                     rgb(${r}, ${g}, ${b})`;

		li.style.backgroundColor = `rgb(${r}, ${g}, ${b})`;

		colors.appendChild(li);

		CSS += `--color-${count}: rgb(${r}, ${g}, ${b});\n\t`;
	};

	function onWheel(e, range) {
		e.preventDefault();

		switch (range) {
			case 'contrast':
				if (e.deltaY < 0) {
					contrastRange.value = parseFloat(contrastRange.value) - 0.001;
				}
				if (e.deltaY > 0) {
					contrastRange.value = parseFloat(contrastRange.value) + 0.001;
				}
				contransRatio = parseFloat(contrastRange.value);
				break;

			case 'red':
				if (e.deltaY < 0) {
					redRange.value = parseFloat(redRange.value) - 2;
				}
				if (e.deltaY > 0) {
					redRange.value = parseFloat(redRange.value) + 2;
				}
				addRed = parseFloat(redRange.value);
				break;

			case 'green':
				if (e.deltaY < 0) {
					greenRange.value = parseFloat(greenRange.value) - 2;
				}
				if (e.deltaY > 0) {
					greenRange.value = parseFloat(greenRange.value) + 2;
				}
				addGreen = parseFloat(greenRange.value);
				break;

			case 'blue':
				if (e.deltaY < 0) {
					blueRange.value = parseFloat(blueRange.value) - 2;
				}
				if (e.deltaY > 0) {
					blueRange.value = parseFloat(blueRange.value) + 2;
				}
				addBlue = parseFloat(blueRange.value);
				break;
		}
		createColors();
	}

	function reset() {
		addRed = 0;
		addGreen = 0;
		addBlue = 0;
		contransRatio = 1.10362;
		createColors();
	}
	const toggleMode = () => {
		if (mode === 'light') {
			document.body.classList.add('light');
			mode = 'dark';
		} else {
			document.body.classList.remove('light');
			mode = 'light';
		}
	};
</script>

<header>
	<button on:click={toggleMode} class="toggleButton"> </button>
	<h1>
		Color Shades with Equal Contrast Ratio (<span bind:this={countEl}>⏳</span>)
		<a
			href="https://dev.to/finnhvman/grayscale-color-palette-with-equal-contrast-ratios-2pgl"
			target="_blank"
			class="source"
		>
			Origin
		</a>
	</h1>
</header>
<main>
	<div class="left">
		<div class="input">
			<div class="row">
				<label>
					Contrast Ratio
					<input type="text" bind:value={contransRatio} on:input={createColors} />
				</label>
				<button on:click={reset}>Reset</button>
			</div>
			<input
				type="range"
				min="1"
				max="2"
				step="0.0000001"
				bind:value={contransRatio}
				bind:this={contrastRange}
				on:input={createColors}
				on:wheel={(e) => onWheel(e, 'contrast')}
			/>
			<input
				type="range"
				min="0"
				max="255"
				bind:value={addRed}
				bind:this={redRange}
				on:input={createColors}
				on:wheel={(e) => onWheel(e, 'red')}
				class="red"
			/>
			<input
				type="range"
				min="0"
				max="255"
				bind:value={addGreen}
				bind:this={greenRange}
				on:input={createColors}
				on:wheel={(e) => onWheel(e, 'green')}
				class="green"
			/>
			<input
				type="range"
				min="0"
				max="255"
				bind:value={addBlue}
				bind:this={blueRange}
				on:input={createColors}
				on:wheel={(e) => onWheel(e, 'blue')}
				class="blue"
			/>
		</div>

		<pre>
    {CSS}
  </pre>
	</div>

	<div class="right">
		<ul bind:this={colors}></ul>
	</div>
</main>

<style>
	main {
		display: flex;
		flex-direction: row;
	}
	.left {
		width: 30%;
		max-width: 500px;
		height: calc(100vh - var(--header-height));
		overflow-y: scroll;
		padding: 1rem;
		box-sizing: border-box;
		position: relative;
		top: 0;
		/* top: var(--header-height); */
	}

	.right {
		position: relative;
		/* top: var(--header-height); */
		/* margin-top: var(--header-height); */
		width: 70%;
		top: 0;

		height: calc(100vh - var(--header-height));
		overflow-y: scroll;
		padding: 1rem;
		box-sizing: border-box;
	}

	pre {
		margin: 0;
		padding: 1rem;
		background: #333;
		color: #ccc;
		font-size: 1rem;
		white-space: pre-wrap;
		word-break: break-all;
		max-height: 50vh;
		overflow-y: scroll;
		tab-size: 2;
	}

	:global(*) {
		scrollbar-gutter: stable;
	}

	:global(body) {
		margin: 0;
		padding: 0;
		color: #e8e8e8;
		background: #1b1b1b;
		font-family: sans-serif;
		--header-height: 4rem;
	}
	:global(body.light) {
		background: #e8e8e8;
		color: #1b1b1b;
	}

	.toggleButton {
		--size: 25px;
		position: absolute;
		top: 0.5rem;
		width: var(--size);
		height: var(--size);
		left: 0.5rem;
		margin: 0;
		padding: 0;
		border: 0;
		border-radius: 0;
		background: transparent;
		cursor: pointer;
		color: #ccc;
		fill: #ccc;
		stroke: #ccc;
	}
	.toggleButton::after {
		position: absolute;
		top: 0;
		right: 0;
		width: var(--size);
		height: var(--size);
		color: #ccc;
		fill: #ccc;
		stroke: #ccc;
		background-size: var(--size);
		background-repeat: no-repeat;
		background-position: center;
		content: '';
		background-image: url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="256" height="256" viewBox="0 0 256 256"%3E%3Cpath fill="%23a2a2a2" d="M116 32V16a12 12 0 0 1 24 0v16a12 12 0 0 1-24 0Zm80 96a68 68 0 1 1-68-68a68.07 68.07 0 0 1 68 68Zm-24 0a44 44 0 1 0-44 44a44.05 44.05 0 0 0 44-44ZM51.51 68.49a12 12 0 1 0 17-17l-12-12a12 12 0 0 0-17 17Zm0 119l-12 12a12 12 0 0 0 17 17l12-12a12 12 0 1 0-17-17ZM196 72a12 12 0 0 0 8.49-3.51l12-12a12 12 0 0 0-17-17l-12 12A12 12 0 0 0 196 72Zm8.49 115.51a12 12 0 0 0-17 17l12 12a12 12 0 0 0 17-17ZM44 128a12 12 0 0 0-12-12H16a12 12 0 0 0 0 24h16a12 12 0 0 0 12-12Zm84 84a12 12 0 0 0-12 12v16a12 12 0 0 0 24 0v-16a12 12 0 0 0-12-12Zm112-96h-16a12 12 0 0 0 0 24h16a12 12 0 0 0 0-24Z"%2F%3E%3C%2Fsvg%3E');
	}

	:global(.light) .toggleButton::after {
		background-image: url('data:image/svg+xml,%3Csvg xmlns="http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg" width="256" height="256" viewBox="0 0 256 256"%3E%3Cpath fill="%23919191" d="M236.37 139.4a12 12 0 0 0-12-3A84.07 84.07 0 0 1 119.6 31.59a12 12 0 0 0-15-15a108.86 108.86 0 0 0-54.91 38.48A108 108 0 0 0 136 228a107.09 107.09 0 0 0 64.93-21.69a108.86 108.86 0 0 0 38.44-54.94a12 12 0 0 0-3-11.97Zm-49.88 47.74A84 84 0 0 1 68.86 69.51a84.93 84.93 0 0 1 23.41-21.22Q92 52.13 92 56a108.12 108.12 0 0 0 108 108q3.87 0 7.71-.27a84.79 84.79 0 0 1-21.22 23.41Z"%2F%3E%3C%2Fsvg%3E');
	}
	header {
		background: linear-gradient(45deg, #333, #ccc);
		position: sticky;
		box-shadow: 0 0 10px 3px rgba(0, 0, 0, 0.25);
		top: 0;
		left: 0;

		height: var(--header-height);
	}
	h1 {
		margin: 0;
		padding: 16px;
		font-weight: normal;
		color: #000;
		text-align: center;
	}
	.source {
		color: #555;
		float: right;
		line-height: 2rem;
		font-size: 1.5rem;
		margin-right: 16px;
	}
	ul {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(164px, 1fr));
		grid-gap: 16px;
		padding: 16px 32px;
	}

	:global(li) {
		border-radius: 4px;
		padding: 48px 16px 16px;
		list-style: none;
		text-align: end;
		color: #000;
		box-shadow: 0 0 8px rgba(0, 0, 0, 0.25);
	}
	.row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 1rem;
	}
	button {
		display: inline-block;
		font-size: 1rem;
		margin: 0;
		padding: 16px;
		font-size: 16px;
		border: 0;
		border-radius: 0;
		background: transparent;
		color: #555;

		cursor: pointer;
	}

	label {
		color: #555;
	}
	input {
		width: 70px;
		margin: 0;
		padding-block: 0.5rem;
		font-size: 16px;
		border: 0;
		color: #555;

		border-radius: 0;
		background: transparent;
	}

	input[type='range'] {
		-webkit-appearance: none;
		width: 100%;
		margin: 0;
		font-size: 16px;
		border: 0;
		border-radius: 0;
	}
	input[type='range']::-webkit-slider-thumb {
		-webkit-appearance: none;
		width: 16px;
		height: 16px;
		background: #333;
		border-radius: 50%;
		cursor: pointer;
	}
	input[type='range']::-moz-range-thumb {
		width: 16px;
		height: 16px;
		background: #333;
		border-radius: 50%;
		cursor: pointer;
	}
	input[type='range']::-ms-thumb {
		width: 16px;
		height: 16px;
		background: #333;
		border-radius: 50%;
		cursor: pointer;
	}
	input[type='range']::-webkit-slider-runnable-track {
		width: 100%;
		height: 4px;
		cursor: pointer;
		background: linear-gradient(90deg, transparent, #000);
		border-radius: 0;
	}
	input[type='range']::-moz-range-track {
		width: 100%;
		height: 4px;
		cursor: pointer;
		background: linear-gradient(90deg, transparent, #000);
		border-radius: 0;
	}
	input[type='range']::-ms-track {
		width: 100%;
		height: 4px;
		cursor: pointer;
		background: linear-gradient(90deg, transparent, #000);
		border-radius: 0;
	}

	input[type='range'].red::-webkit-slider-thumb {
		background: red;
	}
	input[type='range'].red::-moz-range-thumb {
		background: red;
	}
	input[type='range'].red::-ms-thumb {
		background: red;
	}
	input[type='range'].red::-webkit-slider-runnable-track {
		background: linear-gradient(90deg, transparent, red);
	}
	input[type='range'].red::-moz-range-track {
		background: linear-gradient(90deg, transparent, red);
	}
	input[type='range'].red::-ms-track {
		background: linear-gradient(90deg, transparent, red);
	}

	input[type='range'].green::-webkit-slider-thumb {
		background: green;
	}
	input[type='range'].green::-moz-range-thumb {
		background: green;
	}
	input[type='range'].green::-ms-thumb {
		background: green;
	}
	input[type='range'].green::-webkit-slider-runnable-track {
		background: linear-gradient(90deg, transparent, green);
	}
	input[type='range'].green::-moz-range-track {
		background: linear-gradient(90deg, transparent, green);
	}
	input[type='range'].green::-ms-track {
		background: linear-gradient(90deg, transparent, green);
	}

	input[type='range'].blue::-webkit-slider-thumb {
		background: blue;
	}
	input[type='range'].blue::-moz-range-thumb {
		background: blue;
	}
	input[type='range'].blue::-ms-thumb {
		background: blue;
	}
	input[type='range'].blue::-webkit-slider-runnable-track {
		background: linear-gradient(90deg, transparent, blue);
	}
	input[type='range'].blue::-moz-range-track {
		background: linear-gradient(90deg, transparent, blue);
	}
	input[type='range'].blue::-ms-track {
		background: linear-gradient(90deg, transparent, blue);
	}

	@media (max-width: 600px) {
		ul {
			grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
		}
	}
</style>
