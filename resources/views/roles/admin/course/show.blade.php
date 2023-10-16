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

	<title>Sangnila Academy | LMS</title>

</head>

<body class="bg-cover h-screen flex flex-col items-center bg-gray-100">
	<x-navbar.admin></x-navbar.admin>
	<!-- Content Section -->
	<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
		<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
		<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>
		<div class="flex space-x-4 mb-6">
			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('admin.course.edit', $course->id) }}">Edit
				Course</a>
			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
				href="{{ route('admin.course.assign', $course->id) }}">Assign Teacher</a>
		</div>
		<h2 class="text-xl font-semibold mb-2">Teacher List:</h2>
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

		<h2 class="text-xl font-semibold mb-2">Schedule List:</h2>
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('admin.schedule.create', $course->id) }}">Add
			New Schedule</a>
		@if ($course->schedules->isNotEmpty())
			<div class="overflow-x-auto mt-5">
				<table class="min-w-full bg-white border-collapse border border-blue-400">
					<thead>
						<tr>
							<th class="bg-blue-300 border border-blue-400 px-4 py-2">Day of Week</th>
							<th class="bg-blue-300 border border-blue-400 px-4 py-2">Start Time</th>
							<th class="bg-blue-300 border border-blue-400 px-4 py-2">End Time</th>
							<th class="bg-blue-300 border border-blue-400 px-4 py-2">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($course->schedules as $schedule)
							<tr>

								<td class="border border-blue-400 px-4 py-2">
									<div>{{ $schedule->day_of_week }}</div>
								</td>

								<td class="border border-blue-400 px-4 py-2">
									<div>{{ $schedule->start_time }}</div>
								</td>

								<td class="border border-blue-400 px-4 py-2">
									<div>{{ $schedule->end_time }}</div>
								</td>

								<td class="border border-blue-400 px-4 py-2">
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
										href="{{ route('admin.schedule.edit', $schedule->id) }}">Edit Schedule</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			<div class="text-blue-900">N/A</div>
		@endif

		<h2 class="text-xl font-semibold mb-2 mt-8">Course Materials</h2>
		<div class="mb-6">
			@forelse ($course->materials as $material)
				<div class="relative flex items-center py-2 group hover:bg-gray-100">
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
</body>

</html>
