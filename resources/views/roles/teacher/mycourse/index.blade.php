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

		{{-- TODO, change to teacher navbar --}}
		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
			@if ($courses->isNotEmpty())
				<div class="overflow-x-auto">
					<table class="min-w-full bg-white border-collapse border border-blue-400">
						<thead>
							<tr>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Course Name</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Description</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($courses as $course)
								<tr>
									<td class="border border-blue-400 px-4 py-2">
										<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id]) }}"
											class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
											{{ $course->course_name }}
										</a>
									</td>

									<td class="border border-blue-400 px-4 py-2">
										{{ $course->course_description }}
									</td>
									<td class="border border-blue-400 px-4 py-2 ">
										<a class="px-5 py-2 bg-indigo-400 rounded-lg  text-white"
											href="#">
											View Students Progress
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
