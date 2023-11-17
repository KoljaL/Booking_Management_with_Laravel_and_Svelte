<script lang="ts">
	import { tokenST, userST } from '$lib/store';
	import { goto } from '$app/navigation';

	if (!$tokenST) {
		// console.log('no token');
		goto('./login?noToken=true');
	}

	function logout() {
		tokenST.set('');
		userST.set({});
		window.location.href = './';
	}
	// console.log($tokenST);
</script>

<header>
	<h2>Hallo {$userST.name}</h2>
	<p>Role: {$userST.role}</p>
	<p>Admin: {$userST.is_admin}</p>
	<p>Location: {$userST.location.city}</p>
	<button on:click={logout}>Logout</button>
</header>
<slot />

<style>
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
</style>
