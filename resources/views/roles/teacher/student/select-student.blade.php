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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Pick a Student</h1>
			@if ($students->isNotEmpty())
				<div class="overflow-x-auto rounded-md">
					<table class="min-w-full bg-white border-collapse ">
						<thead>
							<tr>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Student Name</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($students as $student)
								@if($student->status != "disabled")
									<tr>
										<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
											<a href="{{ route('teacher.student.show.progress', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
												class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
												{{ $student->full_name }}
											</a>
										</td>
									</tr>
								@endif
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
