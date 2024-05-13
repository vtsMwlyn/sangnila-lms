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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Student Attendance for {{ $attendanceData[0]->course->course_name }}</h1>

			@if(session()->has("successUploadAttendance"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successUploadAttendance") }}</p>
				</div>
			@elseif(session()->has("successEditAttendance"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successEditAttendance") }}</p>
				</div>
			@endif

			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
				href="{{ route('teacher.attendance.upload', $attendanceData[0]->course->id) }}">
				Upload New Attendance
			</a>

			{{-- @for($i = 0; $i < $attendanceData->count() / $course->students->count(); $i++)
				<table class="w-full">
					<thead>
						<th class="border px-3">Timestamp</th>
						<th class="border px-3">Uploaded by</th>
						<th class="border px-3">Students</th>
						<th class="border px-3">Actions</th>
					</thead>
					<tbody>
						@for($j = 0; $j < $course->students->count(); $j++)
							<tr>
								<td class="border px-3">{{ $attendanceData[$j + ($i * $attendanceData->count() / $course->students->count())]->created_at }}</td>
								<td class="border px-3">{{ $attendanceData[$j + ($i * $attendanceData->count() / $course->students->count())]->teacher->full_name }}</td>
								<td class="border px-3">{{ $attendanceData[$j + ($i * $attendanceData->count() / $course->students->count())]->student->full_name }}</td>
								<td class="border px-3">Action buttons here</td>
							</tr>
						@endfor
					</tbody>
				</table>
			@endfor --}}

			@for($i = 0; $i < $attendanceData->count(); $i += $course->students->count())
				<div class="border rounded-lg p-5 mb-5 mt-5">
					<p>Date/Time: {{ $attendanceData[$i]->created_at }}</p>
					<p>Uploaded by: {{ $attendanceData[$i]->teacher->full_name }}</p>
					<div class="mt-5">
						<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
							href="{{ route("teacher.attendance.edit", $attendanceData[$i]->id) }}">
							Edit
						</a>
					</div>
					<table class="w-full mt-5 mb-5">
						<thead>
							<th class="border px-3">Students</th>
							<th class="border px-3">Attendance status</th>
							<th class="border px-3">Attendance detail</th>
						</thead>
						<tbody>
							@for($j = $i; $j < min($attendanceData->count(), $i + $course->students->count()); $j++)
								<tr>
									<td class="border px-3">{{ $attendanceData[$j]->student->full_name }}</td>
									<td class="border px-3">{{ ($attendanceData[$j]->is_attend == 1)? "Attended" : "Absent" }}</td>
									<td class="border px-3">{{ $attendanceData[$j]->attendance_detail }}</td>
								</tr>
							@endfor
						</tbody>
					</table>
				</div>
			@endfor

			<div class="">
				{{ $attendanceData->links() }}
			</div>

		</div>
	</div>

</body>

</html>
