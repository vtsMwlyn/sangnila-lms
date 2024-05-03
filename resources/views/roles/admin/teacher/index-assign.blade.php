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
			<h2 class="text-2xl font-semibold text-blue-900 mb-4">Teacher's Detail</h2>
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

			<form action="{{ route("admin.teacher.assign.store", $user->id) }}" method="post" class="border rounded-lg p-5">
				@csrf
				<x-label for="visibility" :value="__('Select a course to assign')" />
				<select name="course_name" id="course_name" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full mt-4">
					@foreach ($courses as $course)
						<option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
					@endforeach
				</select>

				<a href="{{ route("admin.teacher.show", $user->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Back</a>

				<x-button class="mt-4">
					{{ __('Save') }}
				</x-button>

			</form>

			<ul class="list-disc pl-6 mb-6 mt-5">
				@forelse ($user->teached_courses as $course)
					<li class="text-black">{{ $course->course_name }}</li>
				@empty
					<li class="text-gray-500">No course</li>
				@endforelse
			</ul>
		</div>
	</div>
</body>

</html>
