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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h2 class="text-2xl font-semibold text-blue-900 mb-4">Student Details</h2>
			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
					href="#">Edit</a>
			</div>
			<table class="mb-8 border">
				<tr>
					<td class="border px-5 font-bold">Full name</td>
					<td class="border px-5">{{ $student->full_name }}</td>
				</tr>
				<tr>
					<td class="border px-5 font-bold">Email</td>
					<td class="border px-5">{{ $student->email }}</td>
				</tr>
			</table>

			{{-- @if ($student->enrolled_courses->isNotEmpty())
			<h2 class="text-xl font-semibold mb-5">Course(s) enrolled by this user:</h2>
			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
					href="{{ route('admin.student.assign.create', $student->id) }}">Assign Student to course</a>

				<div class="overflow-x-auto rounded-md">
					<table class="min-w-full bg-white border-collapse">
						<thead>
							<tr>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Course Name</td>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($student->enrolled_courses as $course)
								<tr>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										<a href="{{ route('admin.course.show', ['course_id' => $course->id]) }}"
											class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
											{{ $course->course_name }}
										</a>
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4 text-center">
										<a
											href="{{ route('admin.student.unassign.delete', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
											class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-red-500 transition duration-300">
											Unassign
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="text-blue-900">N/A</div>
			@endif --}}

			<h2 class="text-xl font-semibold mb-5">Course(s) enrolled by this user:</h2>

			<form action="{{ route('admin.student.assign.store', $student->id) }}" method="post" class="border rounded-lg p-5">
				@csrf
				<x-label for="visibility" :value="__('Select a course to assign')" />
				<select name="course_name" id="course_name" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full mt-4">
					@foreach ($courses as $course)
						<option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
					@endforeach
				</select>

				<a href="{{ route("admin.teacher.show", $student->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Back</a>

				<x-button class="mt-4">
					{{ __('Save') }}
				</x-button>

			</form>

			<ul class="list-disc pl-6 mb-6 mt-5">
				@forelse ($student->enrolled_courses as $course)
					<li class="text-black">{{ $course->course_name }}</li>
				@empty
					<li class="text-gray-500">No course</li>
				@endforelse
			</ul>

			{{-- Schedule --}}
			{{-- <h2 class="text-xl font-semibold mb-2">Student Schedules:</h2>

			@if ($student->schedules->isNotEmpty())
				<div class="overflow-x-auto">
					<table class="min-w-full bg-white border-collapse">
						<thead>
							<tr>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Course Name</th>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Day of Week</th>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Start Time</th>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">End Time</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($student->schedules as $schedule)
								<tr>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										<a href="{{ route('admin.course.show', ['course_id' => $schedule->schedule->course->id ]) }}"
											class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
											{{ $schedule->schedule->course->course_name }}
										</a>
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										{{ $schedule->schedule->day_of_week }}
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										{{ $schedule->schedule->start_time }}
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										{{ $schedule->schedule->end_time }}
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="text-center py-2">No schedules found for this student.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			@else
				<div class="text-blue-900">N/A</div>
			@endif --}}
		</div>
	</div>

</body>

</html>
