<!-- Updated Responsive Navbar with Dropdown for Mobile -->
<nav class="bg-blue-200 w-full py-3">
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
					<a href="{{ route('student.mycourse.index') }}"
						class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
						Courses
					</a>
					<a href="{{ route('student.assignment.index') }}"
						class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
						Assignments
					</a>
					<a href="{{ route('student.attendance.index') }}"
						class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
						Attendance
					</a>

                    <!-- Add more navigation links as needed -->
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class=" ml-auto hidden md:flex pr-10 ">
            @csrf
			<button type="submit"
            class="text-indigo-700 font-semibold text-xl transition duration-300 py-2 hover:text-red-500 px-1">
            {{ __('Log Out') }}
        </button>
        <svg class=" h-12 w-8 text-red-500 " width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
            <path d="M7 12h14l-3 -3m0 6l3 -3" />
        </svg>

        </form>

        <!-- Dropdown Menu (hidden by default on larger screens) -->
        <div id="mobileMenu" class="md:hidden mt-2 px-6 w-full" style="display: none;">
			<a href="{{ route('student.mycourse.index') }}"
				class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
				Courses
			</a>
			<a href="{{ route('student.attendance.index', Auth::user()->id) }}"
				class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
				Attendance
			</a>

            <!-- Add more navigation links as needed -->

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" >
                @csrf
                <button type="submit"
                    class="text-red-500 font-semibold text-md pt-1 pb-3">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle mobile menu visibility
        document.getElementById('mobileMenuButton').addEventListener('click', function () {
            var mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.style.display = (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') ? 'block' : 'none';
        });
    </script>
</nav>



