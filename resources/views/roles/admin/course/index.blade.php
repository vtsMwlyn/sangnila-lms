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

<body class="bg-cover min-h-screen flex flex-col items-center"
    style="background-image: url({{ asset('img/background.png') }});">

    <x-navbar.admin></x-navbar.admin>

    <!-- Content Section -->
    <div class="container mx-auto my-6 p-4 bg-white rounded-lg shadow-lg">
		<div class="flex w-full items-center justify-between mb-4">
			<h1 class="text-3xl font-semibold text-blue-900">Courses</h1>

			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('sysadmin.course.create') }}">Add New Course</a>
		</div>

        @if ($courses->isNotEmpty())
        <div class="overflow-x-auto rounded-md">
			<table class="min-w-full bg-white border-collapse sm:table">
				<thead>
					<tr>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Course Name</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Description</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Visibility</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($courses as $course)
					<tr>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
							<a href="{{ route('admin.course.show', ['course_id' => $course->id]) }}"
								class="text-blue-600 hover:text-blue-800 font-semibold hover:underline block">
								{{ $course->course_name }}
							</a>
						</td>

						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
							{{ $course->course_description }}
						</td>

						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4 text-center">
							{{ $course->visibility }}
						</td>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4 text-center">
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route('admin.course.show', ['course_id' => $course->id]) }}">
								View
							</a>
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route('admin.course.edit', $course->id) }}">
								Edit
							</a>
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="#">
								Delete
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

</body>

</html>
