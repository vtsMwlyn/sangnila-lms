<!-- Main sidebar -->
<div class="text-white z-10 min-h-screen" style="width: 17%;" id="sidebar-container">
	@php
		// $student = Auth::user();
		// $n_asg_subm = 0;
		// $n_all_asg = 0;

		// foreach($student->enrolled_courses as $crs){
		// 	$student_assignments = App\Models\StudentAssignment::where("student_id", $student->id)->get();

		// 	$student_assignments_in_the_course = [];
		// 	foreach($student_assignments as $asg){
		// 		if($asg->assignment->course_id == $crs->id){
		// 			array_push($student_assignments_in_the_course, $asg);
		// 		}
		// 	}

		// 	foreach($student_assignments_in_the_course as $assg){
		// 		foreach($assg->assignment->submissions as $submission){
		// 			if($submission->student_id == $student->id){
		// 				$n_asg_subm++;
		// 				break;
		// 			}
		// 		}
		// 	}

		// 	$n_all_asg += count($student_assignments_in_the_course);
		// }

		// $n = $n_all_asg - $n_asg_subm;
	@endphp

	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="md:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="md:flex flex-col items-stretch sticky hidden z-0 m-0" style="top: 65px; background: url({{ asset('img/sidebar-bg.png') }}) no-repeat center left; background-size: cover;" id="sidebar">
		<div class="relative flex flex-col dropdown-container">
			<button type="button" class="flex flex-col items-center dropdown-toggler px-10 py-4" style="background: {{ Request::is('profile*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				@if(Auth::user()->details->profpic)
					<img src="{{ Storage::url("app/public/" . Auth::user()->details->profpic) }}" class="rounded-full w-32 h-32 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;">
				@else
					<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full w-32 h-32 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;">
				@endif
				<h1 class="text-xl font-bold">Hello {{ explode(" ", Auth::user()->full_name)[0] }}!</h1>
			</button>

			<div class="absolute py-4 bg-white top-6 w-80 rounded-3xl flex flex-col hidden overflow-hidden dropdown-menu" style="box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); left: 220px;">
				<div class="font-extrabold px-5 text-dark-blue">{{ Auth::user()->full_name }}</div>
				<div class="bg-slate-400 mx-5 mt-3 mb-1" style="height: 1.5px;"></div>
				<a href="{{ route("profile.show") }}"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-edit-profile.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Edit Profile</div></a>
				<a href="{{ route("profile.show") }}"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-change-password.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Change Password</div></a>
			</div>
		</div>

		{{-- <script>
			$(document).ready(function () {
				// Toggle dropdown on button click
				$("#profile-toggler").click(function (e) {
					e.stopPropagation(); // Prevent the click event from bubbling up to the document

					$("#profile-menu").toggle();
				});

				// Close dropdown when clicking outside of it
				$(document).click(function (e) {
					if (!$(e.target).closest("#profile-menu, #profile-toggler").length) {
						$("#profile-menu").hide();
					}
				});
			});
		</script> --}}

		<!-- Sidebar navigations -->
		<div class="flex flex-col items-stretch justify-center w-full mt-8 text-base overflow-y-auto">
			<x-anchor-button class="grow flex items-center gap-4 h-12 px-6 hover:bg-cyan-500"
				href="{{ route('home') }}" style="border-radius: 0; background: {{ Request::is('dashboard*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-dashboard.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Dashboard
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center gap-4 h-12 px-6 hover:bg-cyan-500"
				href="{{ route('student.mycourse.index') }}" style="border-radius: 0; background: {{ Request::is('student*my-course*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-courses.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Courses
			</x-anchor-button>

			<div class="relative grow flex items-center gap-2">
				{{-- @if($n > 0)
					<div class="absolute h-6 w-7 bg-red-600 rounded-full flex justify-center items-center" style="top: -0.5rem; right: -0.5rem;">{{ $n }}</div>
				@endif --}}
				<x-anchor-button class="flex items-center gap-4 h-12 px-6 hover:bg-cyan-500 w-full"
					href="{{ route('student.assignment.index') }}" style="border-radius: 0; background: {{ Request::is('student*assignment*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
					<img src="{{ asset('img/sidebar-assignment.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Assignment
				</x-anchor-button>
			</div>

			<x-anchor-button class="grow flex items-center gap-4 h-12 px-6 hover:bg-cyan-500"
				href="{{ route('student.attendance.index') }}" style="border-radius: 0; background: {{ Request::is('student*attendance*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-attendance.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Attendance
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center gap-4 h-12 px-6 hover:bg-cyan-500"
				href="#" style="border-radius: 0; background: {{ Request::is('schedule*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-schedule.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Schedule
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center gap-4 h-12 px-6 hover:bg-cyan-500"
				href="{{ route('list-announcement') }}" style="border-radius: 0; background: {{ Request::is('announcement*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-announcement.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Announcement
			</x-anchor-button>

			{{-- <!-- Logout Button -->
			<form method="POST" action="{{ route('logout') }}" class="grow flex items-center gap-2">
				@csrf
				<x-button
					class="font-semibold bg-red-600 w-full" onclick="return confirm('Are you sure want to logout from your account?');">
						<i class="bi bi-box-arrow-left"></i> {{ __('Log Out') }}
				</x-button>
			</form> --}}
		</div>
	</div>

	<script>
		// Toggle mobile menu visibility
		$("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
	</script>
</div>
