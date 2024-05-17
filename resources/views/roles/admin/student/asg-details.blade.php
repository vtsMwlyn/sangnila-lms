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
			<h2 class="text-2xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Assignments</h2>
			<h3 class="text-xl font-semibold">Course: {{ $course->course_name }}</h3>

			<table class="mt-3 w-full">
				<thead>
					<th class="border px-3">Assignment Title</th>
					<th class="border px-3">Assignment Description</th>
					<th class="border px-3">Submission Status</th>
					<th class="border px-3">Latest Submission Time</th>
				</thead>
				<tbody>
					@forelse ($assignments as $asg)
						<tr>
							<td class="border px-3">{{ $asg->title }}</td>
							<td class="border px-3">{{ $asg->desc }}</td>
							@php
								$submissions = $asg->submissions;
								$found = false;
								$latest_submission = null;
								for($i = count($submissions) - 1; $i >= 0; $i--){
									if($submissions[$i]->student_id == $student->id){
										$found = true;
										$latest_submission = $submissions[$i];
										break;
									}
								}

								if($found){
									echo "<td class='border px-3'>Submitted</td><td class='border px-3'>". $latest_submission->created_at ."</td>";
								} else {
									echo "<td class='border px-3 text-center' colspan='2'>No Submissions Yet</td>";
								}
							@endphp

						</tr>
					@empty
						<tr>
							<td class="border px-3 text-center" colspan="4">No assignments assigned to the student yet</td>
						</tr>
					@endforelse
				</tbody>
			</table>


		</div>
	</div>

</body>

</html>
