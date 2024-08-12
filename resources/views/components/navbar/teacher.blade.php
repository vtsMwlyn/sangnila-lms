<!-- Main sidebar -->
<div class="w-full text-white sticky top-0 z-20" style="background-color: rgba(17, 41, 102, 0.5); backdrop-filter: blur(3px);">
	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="lg:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="lg:flex flex-col lg:flex-row items-center justify-between sticky top-0 py-6 px-5 w-full hidden" id="navigation">
		<!-- Logo/Brand Image -->
		<div class="flex justify-center hover:scale-110 transition duration-600 mx-5">
			<a href="{{ route('home') }}" style="cursor: url({{ asset('img/cursor2.cur') }}), pointer;">
				<img src={{ asset("img/AR.W.png") }} alt="logo" style="min-width: 90px; max-width: 90px;">
			</a>
		</div>

		<!-- Sidebar navigations -->
		<div class="flex flex-col lg:flex-row items-stretch justify-center gap-4 mx-10 py-4 px-6 rounded-3xl border border-white grow lg:w-auto my-5 lg:my-0" style="background-color: rgba(217, 217, 217, 0.45);">
			<x-anchor-button class="{{ (Request::is('teacher*my-course*') || Request::is('teacher*topic*') || Request::is('teacher*material*'))? 'bg-orange-500' : 'bg-blue-900' }} grow flex items-center gap-2 justify-center"
				href="{{ route('teacher.mycourse.index') }}">
				<i class="bi bi-grid"></i> Manage Courses
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('teacher*student*')? 'bg-orange-500' : 'bg-blue-900' }} grow flex items-center gap-2 justify-center"
				href="{{ route('teacher.student.select-course') }}">
				<i class="bi bi-file-earmark-lock"></i> Materials Access
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('teacher*attendance*')? 'bg-orange-500' : 'bg-blue-900' }} grow flex items-center gap-2 justify-center"
				href="{{ route('teacher.attendance.index') }}">
				<i class="bi bi-file-earmark-check"></i> Attendances & Progress
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('teacher*assignment*')? 'bg-orange-500' : 'bg-blue-900' }} grow flex items-center gap-2 justify-center"
				href="{{ route('teacher.assignment.index') }}">
				<i class="bi bi-file-earmark-text"></i> Manage Assignments
			</x-anchor-button>

			<!-- Logout Button -->
			<form method="POST" action="{{ route('logout') }}" class="grow flex items-center gap-2 justify-center">
				@csrf
				<x-button
					class="font-semibold bg-red-600 w-full" onclick="return confirm('Are you sure want to logout from your account?');">
						<i class="bi bi-box-arrow-left"></i> {{ __('Log Out') }}
				</x-button>
			</form>
		</div>

		<a href="{{ route("profile.show") }}" class="flex flex-col items-center font-bold mx-5 hover:text-yellow-400 hover:scale-110 transition ease-in-out text-white absolute top-4 lg:top-0 right-0 lg:relative" style="min-width: 90px; max-width: 90px;">
			<i class="bi bi-person-circle text-3xl "></i>
			<span class=" text-center text-sm mt-1">{{ (Auth::user()->details->gender == 1)? "Mr." : "Ms." }} {{ Auth::user()->full_name }}</span>
		</a>
	</div>

    <script>
        // Toggle mobile menu visibility
        $("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
    </script>
</div>
