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

<body class="font-sans bg-cover h-screen bg-center bg-no-repeat"
    style="background-image: url({{ asset('img/background.png') }});">
    <x-navbar.sysadmin></x-navbar.sysadmin>

    <!-- Content Section -->
    <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-blue-900 mb-4">Create Course</h1>
        <form action="{{ route('sysadmin.course.store') }}" method="post" class="mx-auto">
            @csrf
            <!-- Course Name -->
            <div class="mb-4">
                <x-label for="course_name" :value="__('Course Name')" />
                <x-input id="course_name" class="block mt-1 w-full" type="text" name="course_name"
                    :value="old('course_name')" autofocus />
            </div>

            <!-- Course Description -->
            <div class="mb-4">
                <x-label for="course_description" :value="__('Course Description')" />
                <x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description"
                    :value="old('course_description')" />
            </div>

			<!-- Visibility Selection -->
			<div class="mb-4">
				<x-label for="visibility" :value="__('Visibility')" />
				<select name="visibility" id="visibility" class="block w-full max-w-full py-2 px-3 border border-gray-300 rounded-md max-h-screen">
					<option value="public">Public</option>
					<option value="private">Private</option>
				</select>
			</div>


            <div class="flex items-stretch gap-1 justify-end mt-6">
				<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Cancel
				</button>
                <x-button>
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </div>
</body>

</html>
