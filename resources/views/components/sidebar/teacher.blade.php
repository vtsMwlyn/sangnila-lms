{{-- Main sidebar --}}
<div class="text-white z-10 min-h-screen w-[75%] md:w-[40%] xl:w-[17%] fixed xl:static hidden xl:block" id="sidebar-container">
	<div class="flex flex-col items-stretch sticky z-0 m-0 w-full h-screen overflow-y-auto" style="background: url({{ asset('img/sidebar-bg.webp') }}) no-repeat center left; background-size: cover;" id="sidebar">
		<div class="relative flex flex-col dropdown-container">
			<button type="button" class="flex flex-col items-center dropdown-toggler px-10 py-4 mb-6 hover:bg-slate-400" style="background: {{ Request::is('profile*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				@if(Auth::user()->details->profpic)
					<img src="{{ Storage::url("app/public/" . Auth::user()->details->profpic) }}" class="rounded-full w-32 h-32 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
				@else
					<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full w-32 h-32 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
				@endif
				<h1 class="text-xl font-bold">Hello {{ (Auth::user()->details->gender == 1)? "Mr. " : "Ms. " }} {{ explode(" ", Auth::user()->full_name)[0] }}!</h1>
			</button>

			<div class="absolute py-4 bg-white top-6 w-80 rounded-3xl flex flex-col hidden overflow-hidden dropdown-menu text-base" style=" left: 220px;">
				<div class="font-extrabold px-5 text-dark-blue">{{ Auth::user()->full_name }}</div>
				<div class="bg-slate-400 mx-5 mt-3 mb-1" style="height: 1.5px;"></div>
				<a href="{{ route("profile.show") }}"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-edit-profile.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Edit Profile</div></a>
				<a href="{{ route("profile.show") }}"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-change-password.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Change Password</div></a>
				@can('can_swap_role')
					<form action="{{ route('change-role') }}" method="post">
						@csrf
						<button type="submit" onclick="return confirm('Are you sure want to swap your role into admin?');" class="w-full"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><i class="bi bi-arrow-left-right text-slate-400"></i> Change Role</div></button>
					</form>
				@endcan
			</div>
		</div>

		{{-- Sidebar navigations --}}
		<div class="flex flex-col items-stretch justify-center w-full text-base" id="large-sidebar">
			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('home') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('dashboard*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-dashboard.svg') }}" class="h-6 w-8" alt="sidebar-icon"> Dashboard
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('teacher.course.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('teacher*course*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-courses.svg') }}" class="h-6 w-8" alt="sidebar-icon"> Courses
				@if(!session('all_course_has_topics'))
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: 0; right: 0;">!</div>
				@endif
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-2 px-6"
				href="{{ route('teacher.student.select-course') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('teacher*student*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<i class="bi bi-person-fill text-3xl mr-0.5"></i> Students
				@if(session('there_is_student_with_no_progress_unlocked'))
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: 0; right: 0;">!</div>
				@endif
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('teacher.forum.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('teacher*forum*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-forum.svg') }}" class="h-6 w-8"> Forum Discussion
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('teacher.assignment.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('teacher*assignment*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-assignment.svg') }}" class="h-6 w-8" alt="sidebar-icon"> Assignment
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('teacher.attendance.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('teacher*attendance*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-attendance.svg') }}" class="h-6 w-8" alt="sidebar-icon"> Attendance
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-2 px-6"
				href="{{ route('teacher.lecturer-invoice-reimburse.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('teacher*invoices*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<i class="bi bi-receipt-cutoff text-2xl mx-1"></i> Invoice & Reimburse
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('list-announcement') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('announcement*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-announcement.svg') }}" class="h-6 w-8" alt="sidebar-icon"> Announcement
			</x-anchor-button>
		</div>
	</div>
</div>
