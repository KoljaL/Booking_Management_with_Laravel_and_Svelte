<script>
	import '$lib/style/app.css';
	console.log('layout');
</script>

<main>
	<slot />
</main>

<style>
	main {
		max-width: 1200px;
		margin: 0 auto;
		display: flex;
		flex-direction: column;
		height: 100vh;
	}
</style>
