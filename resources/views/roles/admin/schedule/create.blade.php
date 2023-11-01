<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
	<link rel="icon" type="image-png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('site.webmanifest') }}">

	<title>Sangnila Academy | LMS</title>
</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">
		<!-- Content Section -->
		<x-navbar.admin></x-navbar.admin>

		{{-- Schedule --}}
		{{-- <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Create Schedule</h1>
			<form action="{{ route('admin.schedule.store') }}" method="post">
				@csrf
				<!-- Course Select -->
				<div class="mt-4">
					<x-label for="course_id" :value="__('Course')" />
					<select name="course_id" id="course_id"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
						@foreach ($courses as $course)
							<option value="{{ $course->id }}">{{ $course->course_name }}</option>
						@endforeach
					</select>
				</div>

				<!-- Day of Week Select -->
				<div class="mt-4">
					<x-label for="day_of_week" :value="__('Day of the Week')" />
					<select name="day_of_week" id="day_of_week"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
						<option value="monday">Monday</option>
						<option value="tuesday">Tuesday</option>
						<option value="wednesday">Wednesday</option>
						<option value="thursday">Thursday</option>
						<option value="friday">Friday</option>
						<option value="saturday">Saturday</option>
						<option value="sunday">Sunday</option>
					</select>
				</div>

				<!-- Start Time Select -->
				<div class="mt-4">
					<x-label for="start_time" :value="__('Start Time')" />
					<input type="time" id="start_time" name="start_time"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
				</div>

				<!-- End Time Select -->
				<div class="mt-4">
					<x-label for="end_time" :value="__('End Time')" />
					<input type="time" id="end_time" name="end_time"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
				</div>

				<!-- Submit Button -->
				<div class="flex items-center justify-end mt-4">
					<x-button class="ml-4">
						{{ __('Submit') }}
					</x-button>
				</div>
			</form>
		</div> --}}
	</div>
</body>

</html>
