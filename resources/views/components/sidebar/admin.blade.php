{{-- Main sidebar --}}
<div class="text-white z-10 min-h-screen w-[75%] md:w-[40%] xl:w-[17%] fixed xl:static hidden xl:block" id="sidebar-container">
	<div class="flex flex-col items-stretch sticky z-0 m-0 w-full" style="background: url({{ asset('img/sidebar-bg.webp') }}) no-repeat center left; background-size: cover;" id="sidebar">
		<div class="relative flex flex-col dropdown-container">
			<button type="button" class="flex flex-col items-center dropdown-toggler px-10 py-4 mb-6 hover:bg-slate-400" style="background: {{ Request::is('profile*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<div class="relative">
					@if(Auth::user()->details->profpic)
						<img src="{{ Storage::url("app/public/" . Auth::user()->details->profpic) }}" class="rounded-full w-32 h-32 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
					@else
						@if(Auth::user()->details->gender == 1)
							<img src="{{ asset('img/tempblankprofpicmale.png') }}" alt="Image Preview" class="rounded-full w-32 h-32 mt-2 mb-4" style="object-fit: cover; object-position: center;" loading="lazy">
						@else
							<img src="{{ asset('img/tempblankprofpicfemale.png') }}" alt="Image Preview" class="rounded-full w-32 h-32 mt-2 mb-4" style="object-fit: cover; object-position: center;" loading="lazy">
						@endif
					@endif

					{{-- Special events only --}}
					{{-- <img src="{{ asset('img/special-events/birthdayhat.png') }}" class="absolute top-[-20px] right-[-20px] w-14 rotate-[38deg]"> --}}
				</div>
				<h1 class="text-xl font-bold">Hello @if(Auth::user()->role_id == 1){{ explode(" ", Auth::user()->full_name)[0] }}@else{{ __('System Admin') }}@endif!</h1>
			</button>

			<div class="absolute py-4 bg-white top-6 w-80 rounded-3xl flex flex-col hidden overflow-hidden dropdown-menu text-base" style=" left: 220px;">
				<div class="font-extrabold px-5 text-dark-blue">{{ Auth::user()->full_name }}</div>
				<div class="bg-slate-400 mx-5 mt-3 mb-1" style="height: 1.5px;"></div>
				<a href="{{ route("profile.show") }}"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-edit-profile.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Edit Profile</div></a>
				<a href="{{ route("profile.show") }}"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-change-password.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Change Password</div></a>
				@can('can_swap_role')
					<form action="{{ route('change-role') }}" method="post">
						@csrf
						<button type="submit" onclick="return confirm('Are you sure want to swap your role into teacher?');" class="w-full"><div class="w-full px-5 py-1 hover:bg-slate-300 text-black font-semibold flex items-center gap-1"><i class="bi bi-arrow-left-right text-slate-400"></i> Change Role</div></button>
					</form>
				@endcan
			</div>
		</div>

		{{-- Sidebar navigations --}}
		<div class="flex flex-col items-stretch justify-center w-full text-base" id="large-sidebar">
			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('home') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('dashboard*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-dashboard.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Dashboard
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('admin.course.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('admin*course*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-managecourses.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Courses
				@if(session('uncomplete_course_data'))
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: 0; right: 0;">!</div>
				@endif
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('admin.teacher.index') }}" style="transform: scale(1); border-radius: 0; background: {{ (Request::is('admin/teacher*') || Request::is('admin*lecturer-attendance*')) ? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-manageteacher.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Teachers
				@if(session('some_teachers_not_assigned_to_course'))
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: 0; right: 0;">!</div>
				@endif
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('admin.student.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('admin/student*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-managestudents.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Students
				@if(session('some_students_not_assigned_to_course'))
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: 0; right: 0;">!</div>
				@endif
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('admin.account.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('admin*account*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-manageaccounts.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Accounts
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('admin.attendance.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('admin*attendance*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-attendance.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Attendances
			</x-anchor-button>

			<x-anchor-button class="grow flex items-center text-start gap-4 py-3 px-6"
				href="{{ route('admin.announcement.index') }}" style="transform: scale(1); border-radius: 0; background: {{ Request::is('admin*announcement*')? 'linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%)' : '' }};">
				<img src="{{ asset('img/sidebar-manageannouncement.svg') }}" class="h-6 w-6" alt="sidebar-icon"> Manage Announcements
			</x-anchor-button>
		</div>
	</div>
</div>

