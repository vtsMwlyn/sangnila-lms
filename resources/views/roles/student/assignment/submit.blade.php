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

		<x-navbar.student></x-navbar.student>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Assignment Submission</h1>

			<div class="rounded-md w-full mt-5 my-5 p-5 border">
				<h3 class="text-xl font-semibold">{{ $assignment->title }}</h3>
				<p class="mt-3 italic">Assignment Description:</p>
				<p>{{ $assignment->desc }}</p>
				<p class="mt-3">Please submit before <span class="font-semibold">{{ $assignment->deadline_date }} {{ $assignment->deadline_time }}</span></p>

				<form action="{{ route("student.assignment.submit", [$course->id, $assignment->id]) }}" method="post" class="mt-4">
					@csrf
					<!-- Submission Title -->
					<div>
						<x-label for="title" :value="__('Submission Title')" />
						<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')"
							autofocus />
					</div>

					<!-- Link -->
					<div class="mt-3">
						<x-label for="link" :value="__('Link of Your Answer/Work')" />
						<x-input id="link" class="block mt-1 w-full" type="text" name="link" :value="old('link')"/>
					</div>

					<div class="flex items-center justify-end mt-6 gap-1">
						<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
								Cancel
						</button>
						<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
								Submit
						</button>
					</div>
				</form>

			</div>


		</div>
	</div>

</body>

</html>
