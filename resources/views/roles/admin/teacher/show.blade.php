<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<title>Laravel</title>
</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">
		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $user->full_name }}</h1>
			<p class="text-gray-700 mb-8">{{ $user->email }}</p>
			<h2 class="text-xl font-semibold mb-2">Course(s) teached by this user:</h2>
			<ul class="list-disc pl-6 mb-6">
				@forelse ($user->teached_courses as $course)
					<li class="text-black">{{ $course->course_name }}</li>
				@empty
					<li class="text-gray-500">No course</li>
				@endforelse
			</ul>
		</div>
	</div>
</body>

</html>
