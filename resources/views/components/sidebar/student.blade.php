<!-- Main sidebar -->
<div class="w-full md:w-1/5 text-white md:min-h-screen md:static z-10">
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
	<button id="mobileMenuButton" class="md:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="md:flex flex-col items-stretch sticky py-10 hidden px-5 z-0" style="top: 65px; height: 100vh; background: url('img/sidebar-bg.png') no-repeat; background-size: cover;" id="navigation">
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
		<div class="flex flex-col items-stretch justify-center gap-4 py-4 px-6 rounded-3xl w-full my-5 lg:my-0">
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
	</div>

	<script>
		// Toggle mobile menu visibility
		$("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
	</script>
</div>
