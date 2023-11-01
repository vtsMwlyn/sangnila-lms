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

		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			{{-- Schedule --}}
			{{-- <h1 class="text-3xl font-semibold text-blue-900 mb-4">Schedules</h1>
			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('admin.schedule.create') }}">Create new
					schedule</a>
			</div>

			@if ($schedules->isNotEmpty())
				<div class="overflow-x-auto">
					<table class="min-w-full bg-white border-collapse border border-blue-400">
						<thead>
							<tr>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Course Name</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Day of Week</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Start Time</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">End Time</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($schedules as $schedule)
								<tr>
									<td class="border border-blue-400 px-4 py-2">
										<div>{{ $schedule->course->course_name }}</div>
									</td>

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

										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
											href="{{ route('admin.schedule.assign', $schedule->id) }}">Assign
											Schedule</a>
									</td>
								</tr>
							@endforeach
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
