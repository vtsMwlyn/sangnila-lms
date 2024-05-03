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

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.teacher></x-navbar.teacher>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			{{-- Attendance --}}
			{{-- <h1 class="text-3xl font-semibold text-blue-900 mb-4">Attendance for {{ $course->course_name }}</h1>

			@if ($course->students->isNotEmpty())
				<form method="POST" action="{{ route('teacher.attendance.store') }}">
					@csrf
					<input type="text" name="course" id="course" value="{{ $course->id }}" hidden>
					@if ($course->schedules->isNotEmpty())
					<select name="schedule" id="schedule">
						@foreach ($course->schedules as $schedule)
							<option value="{{ $schedule->id }}">{{ $schedule->day_of_week }}: {{ $schedule->start_time }} - {{ $schedule->end_time }}</option>
						@endforeach
					</select>
					@else
					<div>No Schedule for this course</div>
					@endif

					<div class="overflow-x-auto">
						<table class="min-w-full bg-white border-collapse border border-blue-400">
							<thead>
								<tr>
									<th class="bg-blue-300 border border-blue-400 px-4 py-2">Student Name</th>
									<th class="bg-blue-300 border border-blue-400 px-4 py-2">Present (?)</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($course->students as $student)
									<tr>
										<td class="border border-blue-400 px-4 py-2">
											{{ $student->full_name }}
										</td>

										<td class="border border-blue-400 px-4 py-2">
											<div class="inline-flex items-center">
												<input type="checkbox" name="attendance_{{ $student->id }}" id="attendance_{{ $student->id }}"
													class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300b bg-gray-300">
											</div>
										</td>

									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<x-button>
						Submit
					</x-button>
				</form>
			@else
				<div class="text-blue-900">N/A</div>
			@endif --}}

		</div>
	</div>

</body>

</html>
