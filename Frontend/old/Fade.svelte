<script lang="ts">
	import { cubicInOut } from 'svelte/easing';

	export let show: boolean = false;
	let duration = 250;
	let delay = duration;

	const transitionIn = (node: HTMLElement) => ({
		duration,
		delay,
		easing: cubicInOut,
		css: (t: number) => `opacity: ${t}`
	});

	const transitionOut = (node: HTMLElement) => ({
		duration,
		delay: 0,
		easing: cubicInOut,
		css: (t: number) => `opacity: ${t}`
	});
</script>

<!-- <div in:transitionIn out:transitionOut|local> -->
{#if show}
	<div in:transitionIn out:transitionOut>
		<slot />
	</div>
{/if}
