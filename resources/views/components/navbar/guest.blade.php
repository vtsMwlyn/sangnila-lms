<!-- Main sidebar -->
<div class="w-full text-white sticky top-0 z-20" style="background-color: rgba(17, 41, 102, 0.5);">
	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="lg:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="lg:flex flex-col lg:flex-row items-center justify-between sticky top-0 py-6 px-5 w-full hidden" id="navigation">
		<!-- Logo/Brand Image -->
		<div class="flex justify-center hover:scale-110 transition duration-600 mx-5">
			<a href="{{ route('home') }}" style="cursor: url({{ asset('img/cursor2.cur') }}), pointer;">
				<img src={{ asset("img/AR.W.png") }} alt="logo" width="90px">
			</a>
		</div>

		<!-- Sidebar navigations -->
		<div class="flex flex-col lg:flex-row items-stretch justify-center gap-4 mx-10 py-4 px-6 rounded-3xl border border-white grow lg:w-auto my-5 lg:my-0" style="background-color: rgba(217, 217, 217, 0.45);">
			<x-anchor-button class="{{ Request::is('admin*course*')? 'bg-orange-500' : 'bg-blue-900' }} grow"
				href="{{ route('admin.course.index') }}">
				<i class="bi bi-grid"></i> Manage Courses
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*teacher*')? 'bg-orange-500' : 'bg-blue-900' }} grow"
				href="{{ route('admin.teacher.index') }}">
				<i class="bi bi-person-lines-fill"></i> Manage Teachers
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*student*')? 'bg-orange-500' : 'bg-blue-900' }} grow"
				href="{{ route('admin.student.index') }}">
				<i class="bi bi-person-workspace"></i> Manage Students
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*account*')? 'bg-orange-500' : 'bg-blue-900' }} grow"
				href="{{ route('admin.account.index') }}">
				<i class="bi bi-person-gear"></i> Manage Accounts
			</x-anchor-button>

			<!-- Logout Button -->
			<form method="POST" action="{{ route('logout') }}" class="grow">
				@csrf
				<x-button
					class="font-semibold bg-red-600 w-full" onclick="return confirm('Are you sure want to logout from your account?');">
						<i class="bi bi-box-arrow-left"></i> {{ __('Log Out') }}
				</x-button>
			</form>
		</div>

		<a href="{{ route("profile.show") }}" class="flex flex-col items-center font-bold mx-5 hover:text-yellow-400 hover:scale-110 transition ease-in-out text-white absolute top-4 lg:top-0 right-0 lg:relative" style="max-width: 90px;">
			<i class="bi bi-person-circle text-3xl "></i>
			<span class=" text-center text-sm mt-1">{{ Auth::user()->full_name }}</span>
		</a>
	</div>

    <script>
        // Toggle mobile menu visibility
        $("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
    </script>
</div>



{{-- <nav class="bg-blue-200 w-full py-3 sticky top-0">
    <div class="container mx-auto flex flex-col md:flex-row items-center">
        <div class="flex items-center  ">
			<!-- Logo/Brand Image -->
			<a href="{{ route('home') }}" class="flex items-center pl-3 mb-1 md:mb-0">
				<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="Your Logo/Brand" width="70px" class="mr-20 ml-4 md:mr-2">
				<span class="text-blue-700 text-2xl font-semibold hidden lg:flex ">SANGNILA LMS</span>
        	</a>

			<!-- Mobile Menu Button with 3-line icon -->
			<button id="mobileMenuButton"
				class="md:hidden text-indigo-700 font-semibold text-3xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 pl-40 pr-3 mx-1">
				<span class="inline-block">&#9776;</span> <!-- 3-line icon (hamburger) -->
			</button>

            <!-- Standard Navigation Links for Larger Screens -->
            <div class="hidden md:flex md:items-center md:space-x-4 md:justify-start px-14">
                <div class="flex items-center space-x-4 justify-start">

                    <a href="{{ route('guest.index') }}"
                        class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
                        Our Courses
                    </a>

					<a href="https://academy.sangnilaindonesia.com/"
                        class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
                        About Us
                    </a>

					<a href="https://sangnilaindonesia.com"
                        class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
                        Visit Our Website
                    </a>

                    <!-- Add more navigation links as needed -->
                </div>
            </div>
        </div>

        <!-- Register Button -->
        <a href="https://register.sangnilaindonesia.com/"
			class="ml-auto hidden md:flex pr-10 text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300">
			Register Now!
		</a>

        <!-- Dropdown Menu (hidden by default on larger screens) -->
        <div id="mobileMenu" class="md:hidden mt-2 px-6 w-full" style="display: none;">

            <a href="{{ route('guest.index') }}"
                class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
                Our Courses
            </a>

			<a href="https://academy.sangnilaindonesia.com/"
                class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
                About Us
            </a>

			<a href="https://sangnilaindonesia.com/"
                class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
                Visit Our Website
            </a>
            <!-- Add more navigation links as needed -->

			<a href="https://register.sangnilaindonesia.com/"
				class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
				Register Now!
			</a>


        </div>
    </div>

    <script>
        // Toggle mobile menu visibility
        document.getElementById('mobileMenuButton').addEventListener('click', function () {
            var mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.style.display = (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') ? 'block' : 'none';
        });
    </script>
</nav> --}}


