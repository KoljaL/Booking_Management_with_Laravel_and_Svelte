<script lang="ts">
	import { onMount } from 'svelte';

	let floatFlame = 0.0;
	let floatFlame2 = 0.0;
	let flameUp = true;
	let floatRocket = 0.0;
	let floatRocket2 = 0.0;
	let rocketUp = true;
	let rocketSVG: HTMLAnchorElement;
	let isAnimating = false;
	let animationInterval: number;
	let random = Math.floor(Math.random() * 50) + 10;
	let color = 'rgb(0, 0, 0)';

	function startAnimation() {
		color = 'rgb(97, 175, 239)';
		if (isAnimating) {
			return;
		}
		animationInterval = setInterval(function () {
			if (flameUp) {
				floatFlame += 0.1;
				floatFlame2 += 0.1;
				if (floatFlame > 1.2) {
					flameUp = false;
				}
			} else {
				floatFlame -= 0.1;
				floatFlame2 -= 0.1;
				if (floatFlame < 0.4) {
					flameUp = true;
				}
			}
			if (rocketUp) {
				floatRocket += 0.051;
				floatRocket2 += 0.051;
				if (floatRocket > 0.5) {
					rocketUp = false;
				}
			} else {
				floatRocket -= 0.1;
				floatRocket2 -= 0.1;
				if (floatRocket < 0.0) {
					rocketUp = true;
				}
			}
		}, 20);
		isAnimating = true;
	}

	// onMount(() => {
	// 	setTimeout(function () {
	// 		startAnimation();
	// 		setTimeout(function () {
	// 			isAnimating = false;
	// 			clearInterval(animationInterval);
	// 			floatFlame = 0.0;
	// 			floatFlame2 = 0.0;
	// 			floatRocket = 0.0;
	// 			floatRocket2 = 0.0;
	// 			random = Math.floor(Math.random() * 50) + 10;
	// 		}, 10000);
	// 	}, random * 1000);

	// 	rocketSVG.addEventListener('mouseover', function () {
	// 		startAnimation();
	// 	});
	// 	rocketSVG.addEventListener('mouseout', function () {
	// 		isAnimating = false;
	// 		clearInterval(animationInterval);
	// 		floatFlame = 0.0;
	// 		floatFlame2 = 0.0;
	// 		floatRocket = 0.0;
	// 		floatRocket2 = 0.0;
	// 		color = 'rgb(0, 0, 0)';
	// 	});
	// });
</script>

<a href="/staff" class="rockedBooking" bind:this={rocketSVG} style="--color:{color}">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="50" height="50">
		<defs>
			<clipPath id="animatedRocket">
				<rect width="50" height="50" x="0" y="0"> </rect>
			</clipPath>
		</defs>
		<g clip-path="url(#animatedRocket)">
			<g transform="matrix(1,0,0,1,{floatRocket},-{floatRocket2})">
				<g transform="matrix(1,0,0,1,12.843000411987305,37.17100143432617)">
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						fill-opacity="0"
						stroke="rgb(0,0,0)"
						stroke-opacity="1"
						stroke-width="2"
						d=" M-2.6670000553131104,-5.281000137329102 C-2.6670000553131104,-5.281000137329102 -4.815999984741211,-1.6649999618530273 -4.815999984741211,-1.6649999618530273 C-4.815999984741211,-1.6649999618530273 -5.293000221252441,-0.8230000138282776 -2.242000102996826,2.2300000190734863 C0.8100000023841858,5.28000020980835 1.6540000438690186,4.801000118255615 1.6540000438690186,4.801000118255615 C1.6540000438690186,4.801000118255615 5.291999816894531,2.6480000019073486 5.291999816894531,2.6480000019073486"
					>
					</path>
				</g>
				<g transform="matrix(1,0,0,1,25.084999084472656,39)">
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						fill-opacity="0"
						stroke="rgb(0,0,0)"
						stroke-opacity="1"
						stroke-width="2"
						d=" M3.9149999618530273,-7 C3.9149999618530273,-7 3.9149999618530273,2 3.9149999618530273,2 C3.9149999618530273,2 -2.0850000381469727,7 -2.0850000381469727,7 C-2.0850000381469727,7 -3.9149999618530273,-0.3179999887943268 -3.9149999618530273,-0.3179999887943268"
					>
					</path>
				</g>
				<g transform="matrix(1,0,0,1,11,24.915000915527344)">
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						fill-opacity="0"
						stroke="rgb(0,0,0)"
						stroke-opacity="1"
						stroke-width="2"
						d=" M7,-3.9149999618530273 C7,-3.9149999618530273 -2,-3.9149999618530273 -2,-3.9149999618530273 C-2,-3.9149999618530273 -7,2.0850000381469727 -7,2.0850000381469727 C-7,2.0850000381469727 0.3179999887943268,3.9149999618530273 0.3179999887943268,3.9149999618530273"
					>
					</path>
				</g>
				<g transform="matrix(1,0,0,1,28.881000518798828,21.121000289916992)">
					<path
						stroke-linecap="round"
						stroke-linejoin="miter"
						fill-opacity="0"
						stroke-miterlimit="10"
						stroke="rgb(0,0,0)"
						stroke-opacity="1"
						stroke-width="2"
						d=" M19.118999481201172,-19.1200008392334 C19.118999481201172,-19.1200008392334 11.9350004196167,-18.993999481201172 5.090000152587891,-15.586999893188477 C2.8929998874664307,-14.489999771118164 0.6930000185966492,-13.020000457763672 -1.2769999504089355,-11.050999641418457 C-7.01800012588501,-5.309999942779541 -18.606000900268555,9.420999526977539 -18.606000900268555,9.420999526977539 C-18.606000900268555,9.420999526977539 -19.118999481201172,11.083000183105469 -15.098999977111816,15.104000091552734 C-11.081000328063965,19.121000289916992 -9.418999671936035,18.60700035095215 -9.418999671936035,18.60700035095215 C-9.418999671936035,18.60700035095215 5.474999904632568,6.85099983215332 11.050000190734863,1.2769999504089355 C13.01099967956543,-0.6869999766349792 14.479999542236328,-2.884000062942505 15.574999809265137,-5.085000038146973 C18.966999053955078,-11.918999671936035 19.118999481201172,-19.1200008392334 19.118999481201172,-19.1200008392334z"
					>
					</path>
				</g>
				<g transform="matrix(1,0,0,1,34,16)">
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						fill-opacity="0"
						stroke="rgb(0,0,0)"
						stroke-opacity="1"
						stroke-width="2"
						d=" M0,-4 C-2.2090001106262207,-4 -4,-2.2090001106262207 -4,0 C-4,2.2090001106262207 -2.2090001106262207,4 0,4 C2.2090001106262207,4 4,2.2090001106262207 4,0 C4,-2.2090001106262207 2.2090001106262207,-4 0,-4z"
					>
					</path>
				</g>
			</g>
			<g
				style="display: block;"
				transform="matrix(1,0,0,1,{floatFlame},-{floatFlame2})"
				opacity="1"
			>
				<g transform="matrix(1,0,0,1,6.01200008392334,43.98899841308594)">
					<path
						stroke-linecap="butt"
						stroke-linejoin="miter"
						fill-opacity="0"
						stroke-miterlimit="10"
						stroke="rgb(0,0,0)"
						stroke-opacity="1"
						stroke-width="2"
						d=" M0.9879999756813049,-4.988999843597412 C-2.4465980529785156,-2.3484766483306885 -4.6593780517578125,-3.7543251514434814 -4.511142253875732,4.511334419250488 C3.723292827606201,4.635313987731934 2.7908990383148193,1.9734054803848267 4.98799991607666,-0.9889037609100342"
					>
					</path>
				</g>
			</g>
		</g>
	</svg>
	<h2>Rocked Booking</h2>
</a>

<style>
	.rockedBooking {
		color: var(--color);
		display: flex;
		align-items: end;
		gap: 0.5rem;
		text-decoration: none;
		transition: color 0.2s ease-in-out;
	}
	.rockedBooking:hover {
		color: var(--blue);
	}
	.rockedBooking svg {
		width: 2rem;
		height: 2rem;
	}
	.rockedBooking h2 {
		margin: 0;
	}
</style>
