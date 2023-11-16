<script lang="ts">
	import { request } from '$lib/request';

	request('GET', 'booking')
		.then((data) => {
			console.log(data);
		})
		.catch((error) => {
			console.log(error);
		});
</script>

<h1>Staff</h1>
