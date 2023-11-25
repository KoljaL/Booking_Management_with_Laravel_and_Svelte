<script lang="ts">
	import JsonHighlight from './JsonHighlight.svelte';
	import { cubicInOut } from 'svelte/easing';
	import { fade, slide, scale } from 'svelte/transition';

	function transitionIn(node: HTMLElement) {
		return {
			duration: 200,
			delay: 0,
			easing: cubicInOut,
			css: (t: number) => `opacity: ${t}`
		};
	}

	function transitionOut(node: HTMLElement) {
		return {
			duration: 200,
			delay: 0,
			easing: cubicInOut,
			css: (t: number) => `opacity: ${t}`
		};
	}
	export let json: any;
	export let depth: number = Infinity;
	export let _cur = 0;
	export let _last = true;

	let open = false;
	let height = 0;
	let trigger: HTMLSpanElement;
	let summary: HTMLDivElement;
	function handleClick() {
		// open = !open;
		const parent = summary.parentElement;
		if (!open) {
			open = true;
			if (parent) {
				setTimeout(() => {
					parent.scrollTop = parent.scrollHeight;
					// trigger.scrollIntoView({ behavior: 'smooth' });
				}, 100);
			}
		} else {
			if (parent) {
				parent.scrollTop = 0;
			}
			setTimeout(() => {
				open = false;
			}, 200);
		}
	}
	$: console.log('json', height);
</script>

<!-- svelte-ignore a11y-no-static-element-interactions -->
<!-- svelte-ignore a11y-click-events-have-key-events -->
<div class="summary" bind:this={summary} class:open on:click={handleClick}>
	<h3>
		{#if open}
			<span class="left"> &#65371; </span><span class="right"> &#65373; </span>
		{:else}
			<span class="left"> &#65371; </span><span class="right"> &#65373; </span>
		{/if}
	</h3>
	{#if open}
		<div
			class="details"
			bind:offsetHeight={height}
			in:slide={{ duration: 200, delay: 0, easing: cubicInOut }}
			out:slide={{ duration: 200, delay: 0, easing: cubicInOut }}
			style="--height:var(--height);"
		>
			<JsonHighlight {json} {depth} {_cur} {_last} />
			<span class="trigger" bind:this={trigger}></span>
		</div>
	{/if}
</div>

<style>
	.summary {
		position: relative;
		display: flex;
		flex-direction: column;
		justify-content: end;
		/* width: 100%; */
		max-width: fit-content;
		transition: height 1.2s ease-in-out;
	}
	h3 {
		cursor: pointer;
		padding: 0;
		margin: 0;
		display: flex;
		font-size: 1.3rem;
		color: #3366cc;
		text-shadow: 0 0 0.5px #000;
		max-width: fit-content;
	}
	.left,
	.right {
		transition: transform 0.2s ease-in-out;
	}
	h3:hover .left {
		transition: transform 0.2s ease-in-out;
		transform: translateX(-0.2em);
	}
	h3:hover .right {
		transition: transform 0.2s ease-in-out;
		transform: translateX(0.2em);
	}

	.open h3 .left {
		transform: translateX(-0.4em) rotate(30deg);
		transform-origin: 100% 50%;
	}
	.open h3 .right {
		transform: translateX(0.4em) rotate(-30deg);
		transform-origin: 0% 50%;
	}

	.open h3:hover .left {
		transform: translateX(-0.3em) rotate(30deg);
		transform-origin: 100% 50%;
	}
	.open h3:hover .right {
		transform: translateX(0.3em) rotate(-30deg);
		transform-origin: 0% 50%;
	}
	.summary .details {
		height: 0;
		overflow: hidden;
		position: absolute;
		top: 2rem;
		left: 0;
	}

	.summary.open .details {
		height: var(--height);
	}
	.trigger {
		/* position: absolute; */
		display: block;
		height: 2rem;
		bottom: 0;
		left: 0;
	}
</style>
