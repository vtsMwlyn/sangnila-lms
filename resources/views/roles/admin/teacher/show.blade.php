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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<title>Sangnila Academy | LMS</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">
		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h2 class="text-2xl font-semibold text-blue-900 mb-4">Teacher's Detail</h2>

			@if(session()->has("successAssignToCourse"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successAssignToCourse") }}</p>
				</div>
			@elseif(session()->has("successUnassignFromCourse"))
				<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
					<p class="text-yellow-600" >{{ session("successUnassignFromCourse") }}</p>
				</div>
			@endif

			<div class="h-fit mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
					href="{{ route("admin.teacher.edit", $user->id) }}">Edit</a>
			</div>
			<table class="mb-8 border">
				<tr>
					<td class="border px-5 font-bold">Full name</td>
					<td class="border px-5">{{ $user->full_name }}</td>
				</tr>
				<tr>
					<td class="border px-5 font-bold">Email</td>
					<td class="border px-5">{{ $user->email }}</td>
				</tr>
			</table>
			{{-- <p class="text-gray-700"><span class="font-bold">Full name:</span> {{ $user->full_name }}</p>
			<p class="text-gray-700 mb-8"><span class="font-bold">Email:</span> {{ $user->email }}</p> --}}
			<h2 class="text-xl font-semibold mb-5">Course(s) teached by this user:</h2>
			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
				href="{{ route("admin.teacher.assign", $user->id) }}">
				Assign to course
			</a>

			<ul class="mb-6 mt-6 flex flex-wrap gap-5">
				@forelse ($user->teached_courses as $course)
					<li class="text-black">
						<form action="{{ route("admin.teacher.unassign", $user->id) }}" method="post" class="bg-gray-300 px-3 py-1 border rounded-lg">
							@csrf
							{{ $course->course_name }}
							<input type="hidden" name="course_id" id="course_id" value={{ $course->id }}>
							<button type="submit" class="" href="#"><i class="bi bi-x-circle-fill"></i></button>
						</form>
					</li>
				@empty
					<li class="text-gray-500">No course</li>
				@endforelse
			</ul>
		</div>
	</div>
</body>

</html>
