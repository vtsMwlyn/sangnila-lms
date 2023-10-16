<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('site.webmanifest') }}">

	<title>Sangnila Academy| LMS</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">
		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
			<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>
			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('admin.course.edit', $course->id) }}">Edit Course</a>
			</div>
			<h2 class="text-xl font-semibold mb-2">Teacher List:</h2>
			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('admin.course.assign', $course->id) }}">Assign Teacher</a>
			</div>
			<ul class="list-disc pl-6 mb-6">
				@forelse ($course->teachers as $teacher)
					<li class="text-black">{{ $teacher->full_name }}</li>
				@empty
					<li class="text-gray-500">No teacher for this course</li>
				@endforelse
			</ul>
			<h2 class="text-xl font-semibold mb-2">Student List:</h2>
			<ul class="list-disc pl-6 mb-6">
				@forelse ($course->students as $student)
					<li class="text-black">{{ $student->full_name }}</li>
				@empty
					<li class="text-gray-500">No student enrolled in this course</li>
				@endforelse
			</ul>
			<h2 class="text-xl font-semibold mb-2">Course Materials</h2>
			<div class="mb-6">
				@forelse ($course->materials as $material)
					<div class="relative flex items-center py-2 group hover:bg-gray-100">
						<a href="{{ route('teacher.material.edit', $material->id) }}"
							class="text-blue-500 hover:text-blue-700  ml-2 mr-2 transition duration-300 ease-in-out transform group-hover:scale-105">
							Edit
						</a>
						<span class="mr-2">
							<a href="{{ $material->link }}" class="text-black hover:text-blue-500 transition duration-300 ease-in-out">
								{{ $material->title }}
							</a>
						</span>
					</div>
				@empty
					<div class="text-gray-500">No materials</div>
				@endforelse
			</div>
		</div>
	</div>
</body>

</html>
