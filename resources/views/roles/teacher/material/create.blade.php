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
		<!-- Content Section -->
		<x-navbar.teacher></x-navbar.teacher>
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Add Material to {{ $course->course_name }}</h1>
			<form action="{{ route('teacher.material.store', $course->id) }}" method="post">
				@csrf
				<!-- Material Title -->
				<div>
					<x-label for="title" :value="__('Material Title')" />
					<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('Material Title')" required
						autofocus />
				</div>

				<!-- Material Link -->
				<div class="mt-4">
					<x-label for="link" :value="__('Material Link')" />
					<x-input id="link" class="block mt-1 w-full" type="text" name="link" :value="old('Material Link')" required />
				</div>

				<div class="flex items-center justify-end mt-4">
					<x-button class="ml-4">
						{{ __('Submit') }}
					</x-button>
				</div>
			</form>
		</div>
	</div>

</body>

</html>
