<!-- Updated Navbar -->
<!-- Updated Responsive Navbar with Dropdown for Mobile -->
<nav class="bg-blue-200 w-full py-3 sticky top-0">
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
</nav>


