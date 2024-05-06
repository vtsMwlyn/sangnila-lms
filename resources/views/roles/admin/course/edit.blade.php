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
		<!-- Content Section -->
		<x-navbar.admin></x-navbar.admin>
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Course</h1>
			<form action="{{ route('admin.course.update', $course->id) }}" method="post">
				@csrf
				@method('PATCH')
				<!-- Course Name -->
				<div>
					<x-label for="course_name" :value="__('Course Name')" />
					<x-input id="course_name" class="block mt-1 w-full" type="text" name="course_name" :value="$course->course_name"
						autofocus />
				</div>

				<!-- Course Description -->
				<div class="mt-4">
					<x-label for="course_description" :value="__('Course Description')" />
					<x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description" :value="$course->course_description" />
				</div>

				<!-- Course Visibility -->
				<div class="mt-4">
					<x-label for="visibility" :value="__('Course Visibility')" />
					{{-- <x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description" :value="$course->visibility" required /> --}}
					<select name="visibility" id="visibility" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full">
						<option value="public" @if($course->visibility == "public") selected @endif>Public</option>
						<option value="private" @if($course->visibility == "private") selected @endif>Private</option>
					</select>
				</div>


				<div class="flex items-center justify-end mt-4">
					<x-button class="ml-4">
						{{ __('Save') }}
					</x-button>
				</div>
			</form>

		</div>
	</div>

</body>

</html>
