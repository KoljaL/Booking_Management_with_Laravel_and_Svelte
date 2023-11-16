<script lang="ts">
	import { tokenST, userST } from '$lib/store';
	import { goto } from '$app/navigation';

	if ($tokenST === null || $tokenST === undefined || !$tokenST) {
		console.log('no token');
		goto('/test');
	}

	console.log($tokenST);
</script>

<h2>Hallo {$userST.name}</h2>
<slot />
