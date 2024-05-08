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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Students Profress for {{ $course->course_name }}</h1>

			@if ($course->students->isNotEmpty())
				<div class="overflow-x-auto rounded-md">
					<table class="min-w-full bg-white border-collapse">
						<thead>
							<tr>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Student Name</td>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Progress</td>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Action</td>
							</tr>
						</thead>
						<tbody>
							@foreach ($course->students as $student)
								<tr>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
											{{ $student->full_name }}
									</td>

									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										{{-- Calculate the progress --}}
										@php
											$totalMaterials = count($course->course_topics);
											$unlockedMaterials = $student->progress->where('status', 'unlocked')->count();
											$progress = $unlockedMaterials . '/' . $totalMaterials;
										@endphp

										<h1>Progress: {{ $progress }}</h1>
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										<a class="px-5 py-2 bg-indigo-400 rounded-lg  text-white hover:bg-gray-700 transition duration-300"
											href="{{ route('teacher.student.index', ['student_id' => $student->id, 'course_id' => $course->id]) }}">
											Update Progress
										</a>
									</td>
								</tr>
							@endforeach

						</tbody>
					</table>
				</div>
			@else
				<div class="text-blue-900">N/A</div>
			@endif
		</div>
	</div>

</body>

</html>
