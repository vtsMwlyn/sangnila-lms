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

	<title>Sangnila Academy| LMS</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">
		<!-- Content Section -->
		<x-navbar.admin></x-navbar.admin>
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Assign Teacher</h1>
			<form method="POST" action="{{ route('admin.course.assign_store', $course->id) }}"
				class="bg-white rounded-2xl p-5 border-blue-300 border-2">
				@csrf
				<!-- Course Name -->
				<div class="mb-6">
					<h1 class="text-xl font-semibold text-blue-900">Assign teacher to:</h1>
					<p class="text-lg text-gray-700">{{ $course->course_name }}</p>
				</div>

				<!-- Teacher Selection -->
				<div class="mt-4">
					<x-label for="teacher" :value="__('Teacher')" />
					@if ($teachers->isEmpty())
						<p class="text-lg text-gray-700">No Teacher to assign.</p>
						<a href="{{ route('admin.course.show', $course->id) }}"
							class="text-blue-500 hover:text-blue-700 underline cursor-pointer mt-2 block">Go back to previous page</a>
					@else
						<select name="teacher" id="teacher"
							class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
							@foreach ($teachers as $teacher)
								<option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
							@endforeach
						</select>
						@if ($teachers->isNotEmpty())
							<div class="flex items-center justify-end mt-4">
								<x-button class="ml-3">
									{{ __('ASSIGN!') }}
								</x-button>
							</div>
						@endif
					@endif
				</div>
			</form>

		</div>
	</div>
</body>

</html>
