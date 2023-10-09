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

		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
					href="{{ route('admin.student.assign.create', $student->id) }}">Assign Student to course</a>
			</div>
			@if ($student->enrolled_courses->isNotEmpty())
				<div class="overflow-x-auto">
					<table class="min-w-full bg-white border-collapse border border-blue-400">
						<thead>
							<tr>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Course Name</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($student->enrolled_courses as $course)
								<tr>
									<td class="border border-blue-400 px-4 py-2">
										<a href="{{ route('admin.course.show', ['course_id' => $course->id]) }}"
											class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
											{{ $course->course_name }}
										</a>
									</td>
									<td class="border border-blue-400 px-4 py-2">
										<a
											href="{{ route('admin.student.unassign.delete', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
											class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
											Unassign
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
