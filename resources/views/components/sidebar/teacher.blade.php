<!-- Main sidebar -->
<div class="w-full md:w-1/5 bg-blue-950 text-white md:min-h-screen sticky top-0 md:static" >
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

		<a href="{{ route("profile.show") }}" class="flex items-center visible md:invisible mt-5 gap-3 font-bold">
			<i class="bi bi-person-circle text-xl"></i>
			<span class="text-white text-center text-sm mt-1">{{ Auth::user()->full_name }}</span>
		</a>

		<!-- Sidebar navigations -->
		<div class="mt-8 flex flex-col text-sm">
			<x-anchor-button class="{{ (Request::is('teacher*mycourse*') || Request::is('teacher*topic*') || Request::is('teacher*material*'))? 'bg-orange-500' : 'bg-blue-600' }} my-2"
				href="{{ route('teacher.mycourse.index') }}">
				<i class="bi bi-grid"></i> My Courses
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('teacher*student*')? 'bg-orange-500' : 'bg-blue-600' }} my-2"
				href="{{ route('teacher.student.select-course') }}">
				<i class="bi bi-graph-up"></i> Student Progress
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('teacher*attendance*')? 'bg-orange-500' : 'bg-blue-600' }} my-2"
				href="{{ route('teacher.attendance.index') }}">
				<i class="bi bi-person-check-fill"></i> Attendances
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('teacher*assignment*')? 'bg-orange-500' : 'bg-blue-600' }} my-2"
				href="{{ route('teacher.assignment.index') }}">
				<i class="bi bi-file-earmark-text"></i> Assignments
			</x-anchor-button>
		</div>

		<!-- Logout Button -->
		<form method="POST" action="{{ route('logout') }}" class="mt-5">
			@csrf
			<x-button
				class="font-semibold w-full bg-red-600">
					<i class="bi bi-box-arrow-left"></i> {{ __('Log Out') }}
			</x-button>
		</form>
	</div>

	<script>
		// Toggle mobile menu visibility
		document.getElementById('mobileMenuButton').addEventListener('click', function () {
			var mobileMenu = document.getElementById('navigation');
			mobileMenu.style.display = (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') ? 'block' : 'none';
		});
	</script>
</div>
