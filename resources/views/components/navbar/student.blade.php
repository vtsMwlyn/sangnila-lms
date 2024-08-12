<!-- Main sidebar -->
<div class="w-full text-white sticky top-0 z-20" style="background-color: rgba(17, 41, 102, 0.5); backdrop-filter: blur(3px);">
	@php
		$student = Auth::user();
		$n_asg_subm = 0;
		$n_all_asg = 0;

		foreach($student->enrolled_courses as $crs){
			$student_assignments = App\Models\StudentAssignment::where("student_id", $student->id)->get();

			$student_assignments_in_the_course = [];
			foreach($student_assignments as $asg){
				if($asg->assignment->course_id == $crs->id){
					array_push($student_assignments_in_the_course, $asg);
				}
			}

			foreach($student_assignments_in_the_course as $assg){
				foreach($assg->assignment->submissions as $submission){
					if($submission->student_id == $student->id){
						$n_asg_subm++;
						break;
					}
				}
			}

			$n_all_asg += count($student_assignments_in_the_course);
		}

		$n = $n_all_asg - $n_asg_subm;
	@endphp

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
			<x-anchor-button class="{{ Request::is('student*my-course*')? 'bg-orange-500' : 'bg-blue-900' }} grow flex items-center gap-2 justify-center"
				href="{{ route('student.mycourse.index') }}">
				<i class="bi bi-grid"></i> My Courses
			</x-anchor-button>

			<div class="relative grow flex items-center gap-2 justify-center">
				@if($n > 0)
					<div class="absolute h-6 w-7 bg-red-600 rounded-full flex justify-center items-center" style="top: -0.5rem; right: -0.5rem;">{{ $n }}</div>
				@endif
				<x-anchor-button class="{{ Request::is('student*assignment*')? 'bg-orange-500' : 'bg-blue-900' }} flex items-center gap-2 justify-center w-full h-full"
					href="{{ route('student.assignment.index') }}">
					<i class="bi bi-file-earmark-text"></i> My Assignments
				</x-anchor-button>
			</div>

			<x-anchor-button class="{{ Request::is('student*attendance*')? 'bg-orange-500' : 'bg-blue-900' }} grow flex items-center gap-2 justify-center"
				href="{{ route('student.attendance.index') }}">
				<i class="bi bi-person-check-fill"></i> My Attendances
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
			<div class="flex flex-col">
				<span class="text-blue-700 text-2xl font-semibold hidden lg:flex ">SANGNILA LMS</span>
				@auth
					<span class="text-blue-700 text-md font-semibold hidden lg:flex ">Logged in as: {{ Auth::user()->full_name }}</span>
				@endauth
			</div>
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
			<a href="{{ route('student.assignment.index') }}"
				class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
				Assignments
			</a>
			<a href="{{ route('student.attendance.index') }}"
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
</nav> --}}



