<!-- Main sidebar -->
<div class="w-full text-white sticky top-0 z-20" style="background-color: rgba(17, 41, 102, 0.5);">
	@php
		$students = App\Models\User::where("role_id", 3)->get();
		$n = 0;

		foreach($students as $student){
			$sa = App\Models\StudentAttendance::where("user_id", $student->id)->get();

			foreach($student->enrolled_courses as $course){
				$cs = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", $student->id)->first();

				if($cs->is_imported){
					$count = App\Models\ImportedStudent::where("course_id", $course->id)->where("student_id", $student->id)->first()->last_attendance_count;
				} else {
					$count = 0;
				}

				foreach($sa as $atd){
					if($atd->attendance->course_id == $course->id && $atd->is_attend == 1){
						$count++;
					}
				}

				if((($count + 1) % $cs->max_course_session == 0) || $count >= $cs->max_course_session){
					$n++;
				}
			}
		}
	@endphp

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

			<div class="relative grow">
				@if($n > 0)
					<div class="absolute h-6 w-7 bg-red-600 rounded-full flex justify-center items-center" style="top: -0.5rem; right: -0.5rem;">{{ $n }}</div>
				@endif
				<x-anchor-button class="{{ Request::is('admin*student*')? 'bg-orange-500' : 'bg-blue-900' }} w-full"
					href="{{ route('admin.student.index') }}">
					<i class="bi bi-person-workspace"></i> Manage Students
				</x-anchor-button>
			</div>

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


