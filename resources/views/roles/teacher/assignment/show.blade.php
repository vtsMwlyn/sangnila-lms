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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">List of Assignments in {{ $course->course_name }}</h1>

			<div class="mt-5 mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
					href="{{ route('teacher.assignment.upload', $course->id) }}">
					Upload New Assignment
				</a>
			</div>

			<div class="overflow-x-auto rounded-md">
				<table class="min-w-full bg-white">
					<thead>
						<tr>
							<th class="border px-3">Title</th>
							<th class="border px-3">Description</th>
							<th class="border px-3">Deadline</th>
							<th class="border px-3">Download link</th>
						</tr>
					</thead>
					<tbody>
						@if ($assignments->isNotEmpty())
							@foreach ($assignments as $asg)
								<tr>
									<td class="border px-3">{{ $asg->title }}</td>
									<td class="border px-3">{{ $asg->desc }}</td>
									<td class="border px-3">{{ $asg->deadline_date }}<br>{{ $asg->deadline_time }}</td>
									<td class="border px-3">{{ $asg->link }}</td>
								</tr>
							@endforeach
						@else
							<tr class="border px-3 text-center"><td colspan="4">- No assignments yet -</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>
