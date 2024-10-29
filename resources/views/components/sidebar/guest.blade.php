<!-- Main sidebar -->
<div class="text-white z-10 min-h-screen" style="width: 17%;" id="sidebar-container">

	{{-- <!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="md:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button> --}}

	<div class="md:flex flex-col items-stretch sticky hidden z-0 m-0" style="top: 65px; background: url({{ asset('img/sidebar-bg.png') }}) no-repeat center left; background-size: cover;" id="sidebar">
		<div class="flex flex-col items-center dropdown-toggler px-10 py-4 mb-6" style="background: {{ Request::is('profile*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
			<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full w-32 h-32 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;">
			<h1 class="text-xl font-bold">Hello Guest!</h1>
		</div>

		<!-- Sidebar navigations -->
		<div class="flex flex-col items-stretch justify-center w-full text-base" id="large-sidebar">
			{{-- <x-anchor-button class="grow flex items-center gap-4 text-start py-3 px-6 hover:bg-cyan-500"
				href="{{ route('home') }}" style="border-radius: 0; background: {{ Request::is('dashboard*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-dashboard.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Dashboard
			</x-anchor-button> --}}

			<x-anchor-button class="grow flex items-center gap-4 text-start py-3 px-6 hover:bg-cyan-500"
				href="{{ route('guest.index') }}" style="border-radius: 0; background: {{ Request::is('student*my-course*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-courses.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Our Courses
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center gap-4 text-start py-3 px-6 hover:bg-cyan-500"
				href="https://academy.sangnilaindonesia.com" style="border-radius: 0; background: {{ Request::is('student*attendance*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<i class="bi bi-info-circle text-2xl"></i> About Us
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center gap-4 text-start py-3 px-6 hover:bg-cyan-500"
				href="https://sangnilaindonesia.com" style="border-radius: 0; background: {{ Request::is('schedule*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<i class="bi bi-globe2 text-2xl"></i> Our Website
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center gap-4 text-start py-3 px-6 hover:bg-cyan-500"
				href="https://register.sangnilaindonesia.com" style="border-radius: 0; background: {{ Request::is('announcement*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<i class="bi bi-person-check-fill text-2xl"></i> Register Now!
			</x-anchor-button>
		</div>
	</div>

	<script>
		// Toggle mobile menu visibility
		$("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
	</script>
</div>
