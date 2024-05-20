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
			<h2 class="text-2xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Attendance</h2>
			<h3 class="text-xl font-semibold">Course: {{ $course->course_name }}</h3>

			<div class="overflow-x-auto">
				<table class="min-w-full bg-white border border-black mt-3" style="border-radius: 0;">
					<thead>
						<tr>
							<th class="border border-black px-3">Date and time</th>
							<th class="border border-black px-3">Attendance status</th>
							<th class="border border-black px-3">Attendance detail</th>
							<th class="border border-black px-3">Uploaded by</th>
						</tr>
					</thead>
					<tbody>
						@if ($attendances->count())
							@foreach ($attendances as $attendance)
								@if($attendance->attendance_detail == "Account disabled")
									@continue
								@endif
								<tr class="@if($attendance->is_attend == 1) bg-green-500 @else bg-red-400 @endif">
									<td class="border border-black px-3">{{ $attendance->created_at }}</td>
									<td class="border border-black px-3">@if($attendance->is_attend == 1) Present @else Absent @endif</td>
									<td class="border border-black px-3">{{ $attendance->attendance_detail }}</td>
									<td class="border border-black px-3">{{ $attendance->teacher->full_name }}</td>
								</tr>
							@endforeach
						@else
							<tr><td colspan="4" class="border border-black px-3 text-center">- The teacher haven't uploaded any attendance data yet -</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>
