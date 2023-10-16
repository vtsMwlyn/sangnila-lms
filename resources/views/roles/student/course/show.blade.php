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
		<x-navbar.student></x-navbar.student>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
			<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>

			<h2 class="text-xl font-semibold mb-2">Course Materials:</h2>
			<div class="h-fit mb-5">

			</div>
			<div class="mb-6">
				@forelse ($materials as $material)
					<div class="relative flex items-center py-2 group hover:bg-gray-100">
						@if ($material->status === 'unlocked')
							<span class="mr-2">
								<a href="{{ $material->material->link }}" class="text-black hover:text-blue-500 transition duration-300 ease-in-out"
									target="_blank">
									{{ $material->material->title }}
								</a>
							</span>
						@else
							<span class="mr-2 text-black">
								{{ $material->material->title }}
							</span>
						@endif

					</div>
				@empty
					<div class="text-gray-500">No materials</div>
				@endforelse

			</div>
		</div>
	</div>
</body>

</html>
