<!-- Main sidebar -->
<div class="w-full md:w-1/5 bg-blue-950 text-white md:min-h-screen sticky top-0 md:static z-30" >
	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="md:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="md:flex flex-col items-stretch sticky top-0 py-10 hidden px-5 z-0" id="navigation">
		<!-- Brand -->
		<div class="w-full flex justify-center">
			<a href="{{ route('home') }}">
				<img src={{ asset("img/AR.W.png") }} alt="logo" width="100px">
			</a>
		</div>

		<!-- Sidebar navigations -->
		<div class="mt-8 flex flex-col text-sm">
			<x-anchor-button class="bg-orange-500 my-2 hover:scale-105 transition duration-600"
				href="{{ route('guest.index') }}">
				<i class="bi bi-grid"></i> Courses Available
			</x-anchor-button>

			<x-anchor-button class="bg-blue-600 my-2 hover:scale-105 transition duration-600"
				href="https://academy.sangnilaindonesia.com/">
				<i class="bi bi-info-circle"></i> About Us
			</x-anchor-button>

			<x-anchor-button class="bg-blue-600 my-2 hover:scale-105 transition duration-600"
				href="https://sangnilaindonesia.com/">
				<i class="bi bi-globe2"></i> Our Website
			</x-anchor-button>
		</div>

		<x-anchor-button class="bg-green-600 mt-5"
			href="https://register.sangnilaindonesia.com/">
			<i class="bi bi-person-check-fill"></i> Register Now!
		</x-anchor-button>
	</div>

	<script>
		// Toggle mobile menu visibility
		$("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
	</script>
</div>
