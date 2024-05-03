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

		<x-navbar.sysadmin></x-navbar.sysadmin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('sysadmin.course.create') }}">Create new
					course</a>
			</div>
			@if ($courses->isNotEmpty())
				<div class="overflow-x-auto rounded-md">
					<table class="min-w-full bg-white border-collapse ">
						<thead>
							<tr>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Course Name</td>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Visibility</td>
								<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Actions</td>
							</tr>
						</thead>
						<tbody>
							@foreach ($courses as $course)
								<tr>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										<a href="{{ route('sysadmin.course.show', ['course_id' => $course->id]) }}">
											{{ $course->course_name }}
										</a>
									</td>

									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										{{ $course->visibility }}
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										<form method="POST" action="{{ route('sysadmin.course.update.archive', $course->id) }}">
											@csrf
											@method('PATCH')
											<div class="inline-flex items-center">
												<input type="checkbox" name="visibility" id="material_checkbox_{{ $course->id }}"
													class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 {{ $course->visibility === 'public' ? 'bg-blue-500' : 'bg-gray-300' }}"
													@if ($course->visibility === 'public') checked @endif>
												<x-button>Submit</x-button>
											</div>
										</form>
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
