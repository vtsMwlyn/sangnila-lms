<!-- Updated Navbar -->
<nav class="bg-blue-200 w-full py-4">
	<div class="container mx-auto flex justify-between items-center">
		<!-- Your Logo/Brand Image -->
		<a href="{{ route('home') }}" class="flex items-center">
			<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="Your Logo/Brand" width="40px" class="mr-2">
			<span class="text-indigo-700 text-2xl font-semibold">SANGNILA LMS</span>
		</a>
		<div class="flex space-x-4">
			<a href="{{ route('teacher.mycourse.index') }}"
				class="text-indigo-700 hover:text-blue-700 transition duration-300 {{ Request::is('teacher/mycourse*') ? 'text-blue-700' : '' }} px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
				My Course(s)
			</a>
			<a href="{{ route('teacher.schedule.index') }}"
				class="text-indigo-700 hover:text-blue-700 transition duration-300 {{ Request::is('teacher/mycourse*') ? 'text-blue-700' : '' }} px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
				My Schedule(s)
			</a>
			<a href="{{ route('teacher.student.index') }}"
				class="text-indigo-700 hover:text-blue-700 transition duration-300 {{ Request::is('teacher/student*') ? 'text-blue-700' : '' }} px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
				Students
			</a>
			<!-- Add more navigation links as needed -->

			<!-- Logout Button -->
			<form method="POST" action="{{ route('logout') }}" class="flex">
				@csrf

				<button type="submit"
					class="text-indigo-700 hover:text-blue-700 transition duration-300 px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
					{{ __('Log Out') }}
				</button>
			</form>
		</div>
	</div>
</nav>
