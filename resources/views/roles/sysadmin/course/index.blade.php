<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<title>Laravel</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.sysadmin></x-navbar.sysadmin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
			<div class="h-fit mb-10">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('sysadmin.course.create') }}">Create new course</a>
			</div>
			@if ($courses->isNotEmpty())
				<div class="overflow-x-auto">
					<table class="min-w-full bg-white border-collapse border border-blue-400">
						<thead>
							<tr>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Course Name</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Visibility</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Blank</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($courses as $course)
								<tr>
									<td class="border border-blue-400 px-4 py-2">{{ $course->course_name }}</td>
									<td class="border border-blue-400 px-4 py-2">{{ $course->visibility }}</td>
									<td class="border border-blue-400 px-4 py-2">Blank</td>
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
