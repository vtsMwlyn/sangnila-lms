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

		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Teachers</h1>

			@if(session()->has("successUpdateTeacherData"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successUpdateTeacherData") }}</p>
				</div>
			@endif

			<div class="overflow-x-auto rounded-md">
				<table class="min-w-full bg-white border-collapse">
					<thead>
						<tr>
							<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Full Name</th>
							<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Email</th>
							<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Assigned Courses</th>
							<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($accounts as $account)
							@if($account->status == "disabled")
								@continue
							@endif
							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<a href="{{ route('admin.teacher.show', ['teacher_id' => $account->id]) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
										{{ $account->full_name }}
									</a>
								</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">{{ $account->email }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									{{-- {{ $account->teached_courses }} --}}
									<ul class="flex flex-col items-center">
										@foreach ($account->teached_courses as $course)
											<li>{{ $course->course_name }}</li>
										@endforeach
									</ul>
								</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<div class="flex w-full justify-center gap-1">
										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("admin.teacher.show", $account->id) }}">
											View
										</a>
										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("admin.teacher.edit", $account->id) }}">
											Edit
										</a>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4" colspan="3">N/A</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

		</div>

</body>

</html>
