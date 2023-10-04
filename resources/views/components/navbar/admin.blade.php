<!-- Updated Navbar -->
<nav class="bg-blue-200 w-full py-4">
	<div class="container mx-auto flex justify-between items-center">
		<!-- Your Logo/Brand Image -->
		<a href="#" class="flex items-center">
			<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="Your Logo/Brand" width="40px" class="mr-2">
			<span class="text-indigo-700 text-2xl font-semibold">SANGNILA LMS</span>
		</a>
		<div class="flex space-x-4">
			<a href="{{ route('admin.schedule.index') }}"
				class="text-indigo-700 hover:text-blue-700 transition duration-300 {{ Request::is('admin/account*') ? 'text-blue-700' : '' }} px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
				Schedules
			</a>
			<a href="{{ route('admin.course.index') }}"
				class="text-indigo-700 hover:text-blue-700 transition duration-300 {{ Request::is('sysadmin/course*') ? 'text-blue-700' : '' }} px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
				Courses
			</a>
			<a href="{{ route('admin.teacher.index') }}"
				class="text-indigo-700 hover:text-blue-700 transition duration-300 {{ Request::is('sysadmin/course*') ? 'text-blue-700' : '' }} px-3 py-2 rounded-lg bg-white hover:bg-indigo-200">
				Teachers
			</a>
			<!-- Add more navigation links as needed -->
		</div>
	</div>
</nav>
