<!-- Main sidebar -->
<div class="w-full bg-blue-950 text-white sticky top-0 z-20">
	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="lg:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="lg:flex flex-col lg:flex-row items-center justify-between sticky top-0 py-8 px-5 w-full hidden" id="navigation">
		<!-- Logo/Brand Image -->
		<div class="flex justify-center hover:scale-110 transition duration-600 mx-5">
			<a href="{{ route('home') }}" style="cursor: url({{ asset('img/cursor2.cur') }}), pointer;">
				<img src={{ asset("img/AR.W.png") }} alt="logo" width="90px">
			</a>
		</div>

		<!-- Sidebar navigations -->
		<div class="flex flex-col lg:flex-row items-stretch justify-center gap-4 bg-indigo-100 py-3 px-6 rounded-xl w-full lg:w-auto my-5 lg:my-0">
			<x-anchor-button class="{{ Request::is('admin*course*')? 'bg-orange-500' : 'bg-blue-600' }}"
				href="{{ route('admin.course.index') }}">
				<i class="bi bi-grid"></i> Courses
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*teacher*')? 'bg-orange-500' : 'bg-blue-600' }}"
				href="{{ route('admin.teacher.index') }}">
				<i class="bi bi-person-lines-fill"></i> Teachers
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*student*')? 'bg-orange-500' : 'bg-blue-600' }}"
				href="{{ route('admin.student.index') }}">
				<i class="bi bi-person-workspace"></i> Students
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*account*')? 'bg-orange-500' : 'bg-blue-600' }}"
				href="{{ route('admin.account.index') }}">
				<i class="bi bi-person-gear"></i> Accounts
			</x-anchor-button>

			<!-- Logout Button -->
			<form method="POST" action="{{ route('logout') }}" class="">
				@csrf
				<x-button
					class="font-semibold bg-red-600 w-full" onclick="return confirm('Are you sure want to logout from your account?');">
						<i class="bi bi-box-arrow-left"></i> {{ __('Log Out') }}
				</x-button>
			</form>
		</div>

		<a href="{{ route("profile.show") }}" class="flex flex-col items-center font-bold mx-5 hover:text-yellow-400 hover:scale-110 transition ease-in-out text-white">
			<i class="bi bi-person-circle text-3xl "></i>
			<span class=" text-center text-sm mt-1">{{ Auth::user()->full_name }}</span>
		</a>
	</div>

    <script>
        // Toggle mobile menu visibility
        document.getElementById('mobileMenuButton').addEventListener('click', function () {
            var mobileMenu = document.getElementById('navigation');
            mobileMenu.style.display = (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') ? 'block' : 'none';
        });
    </script>
</div>


