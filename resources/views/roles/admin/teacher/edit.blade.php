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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Teacher Data</h1>
			<form action="{{ route('admin.teacher.update', $teacher->id) }}" method="post">
				@csrf
				@method('PATCH')
				<!-- Teacher Name -->
				<div>
					<x-label for="full_name" :value="__('New Teacher Name')" />
					<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="$teacher->full_name"
						autofocus />
				</div>

				<!-- Teacher Email -->
				<div class="mt-3">
					<x-label for="email" :value="__('New Teacher Email')"/>
					<x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="$teacher->email"  />
				</div>

				<div class="flex items-stretch justify-end mt-4">
					<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
							Cancel
					</button>
					<x-button class="ml-4">
						{{ __('Save') }}
					</x-button>
				</div>
			</form>

		</div>
	</div>

</body>

</html>
