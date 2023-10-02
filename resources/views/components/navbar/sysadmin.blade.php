<!-- Updated Navbar -->
<nav class="bg-blue-200 w-full py-4">
	<div class="container mx-auto flex justify-between items-center">
		<!-- Your Logo/Brand Image -->
		<a href="#" class="flex items-center">
			<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="Your Logo/Brand" width="40px" class="mr-2">
		</a>
		<div class="flex space-x-4">
			<a href="{{ route('sysadmin.account.index') }}" class=" hover:text-blue-300 transition duration-300">Accounts</a>
			<a href="{{ route('sysadmin.course.index') }}"
				class="text-blue-700 hover:text-blue-300 transition duration-300">Courses</a>
			<!-- Add more navigation links as needed -->
		</div>
	</div>
</nav>
