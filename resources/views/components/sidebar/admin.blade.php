<!-- Main sidebar -->
<!-- Main sidebar -->
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

	{{-- <!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="md:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button> --}}

	<div class="md:flex flex-col items-stretch sticky hidden z-0 m-0 overflow-y-auto" style="top: 65px; background: url({{ asset('img/sidebar-bg.png') }}) no-repeat center left; background-size: cover;" id="sidebar">
		<div class="relative flex flex-col dropdown-container">
			<button type="button" class="flex flex-col items-center dropdown-toggler px-10 py-4 mb-6" style="background: {{ Request::is('profile*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
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

		<!-- Sidebar navigations -->
		<div class="flex flex-col items-stretch justify-center w-full text-base" id="large-sidebar">
			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="{{ route('home') }}" style="border-radius: 0; background: {{ Request::is('dashboard*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-dashboard.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Dashboard
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="{{ route('admin.course.index') }}" style="border-radius: 0; background: {{ Request::is('admin*course*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-managecourses.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Courses
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="{{ route('admin.teacher.index') }}" style="border-radius: 0; background: {{ Request::is('admin*teacher*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-manageteacher.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Teachers
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="{{ route('admin.student.index') }}" style="border-radius: 0; background: {{ Request::is('admin*student*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-managestudents.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Students
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="{{ route('admin.account.index') }}" style="border-radius: 0; background: {{ Request::is('admin*account*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-manageaccounts.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Accounts
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="#" style="border-radius: 0; background: {{ Request::is('schedule*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-schedule.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Schedule
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6 hover:bg-cyan-500"
				href="{{ route('admin.announcement.index') }}" style="border-radius: 0; background: {{ Request::is('admin*announcement*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-manageannouncement.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Announcements
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

{{-- <div class="w-full md:w-1/5 bg-blue-950 text-white md:min-h-screen sticky top-0 md:static z-30" >
	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="md:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="md:flex flex-col items-stretch sticky top-0 py-10 hidden px-5 z-0" id="navigation">
		<!-- Brand -->
		<div class="w-full flex justify-center hover:scale-110 transition duration-600">
			<a href="{{ route('home') }}" style="cursor: url({{ asset('img/cursor2.cur') }}), pointer;">
				<img src={{ asset("img/AR.W.png") }} alt="logo" width="100px">
			</a>
		</div>

		<a href="{{ route("profile.show") }}" class="flex items-center visible md:invisible mt-5 gap-3 font-bold">
			<i class="bi bi-person-circle text-xl"></i>
			<span class="text-white text-center text-sm mt-1">{{ Auth::user()->full_name }}</span>
		</a>

		<!-- Sidebar navigations -->
		<div class="mt-5 flex flex-col text-sm">
			<x-anchor-button class="{{ Request::is('admin*course*')? 'bg-orange-500' : 'bg-blue-600' }} my-2 hover:scale-105 transition duration-600"
				href="{{ route('admin.course.index') }}">
				<i class="bi bi-grid"></i> Courses
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*teacher*')? 'bg-orange-500' : 'bg-blue-600' }} my-2 hover:scale-105 transition duration-600"
				href="{{ route('admin.teacher.index') }}">
				<i class="bi bi-person-lines-fill"></i> Teachers
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*student*')? 'bg-orange-500' : 'bg-blue-600' }} my-2 hover:scale-105 transition duration-600"
				href="{{ route('admin.student.index') }}">
				<i class="bi bi-person-workspace"></i> Students
			</x-anchor-button>

			<x-anchor-button class="{{ Request::is('admin*account*')? 'bg-orange-500' : 'bg-blue-600' }} my-2 hover:scale-105 transition duration-600"
				href="{{ route('admin.account.index') }}">
				<i class="bi bi-person-gear"></i> Accounts
			</x-anchor-button>
		</div>

		<!-- Logout Button -->
		<form method="POST" action="{{ route('logout') }}" class="mt-5">
			@csrf
			<x-button
				class="font-semibold w-full bg-red-600 hover:scale-105 transition duration-600" onclick="return confirm('Are you sure want to logout from your account?');">
					<i class="bi bi-box-arrow-left"></i> {{ __('Log Out') }}
			</x-button>
		</form>
	</div>

	<script>
		// Toggle mobile menu visibility
		$("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
	</script>
</div> --}}
