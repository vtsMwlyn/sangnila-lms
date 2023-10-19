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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Course Schedule</h1>
			<form action="{{ route('admin.schedule.update', $schedule->id) }}" method="post">
				@csrf
				@method('PATCH')
				<!-- Course Select -->
				<div class="mt-4">
					<x-label for="course_id" :value="__('Course')" />
					<select name="course_id" id="course_id"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
						@foreach ($courses as $course)
							<option value="{{ $course->id }}" {{ $course->id == $schedule->course_id ? 'selected' : '' }}>
								{{ $course->course_name }}
							</option>
						@endforeach
					</select>
				</div>

				<!-- Day of Week Select -->
				<div class="mt-4">
					<x-label for="day_of_week" :value="__('Day of the Week')" />
					<select name="day_of_week" id="day_of_week"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
						<option value="monday" {{ $schedule->day_of_week == 'monday' ? 'selected' : '' }}>Monday</option>
						<option value="tuesday" {{ $schedule->day_of_week == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
						<option value="wednesday" {{ $schedule->day_of_week == 'wednesday' ? 'selected' : '' }}>Wednesday
						</option>
						<option value="thursday" {{ $schedule->day_of_week == 'thursday' ? 'selected' : '' }}>Thursday</option>
						<option value="friday" {{ $schedule->day_of_week == 'friday' ? 'selected' : '' }}>Friday</option>
						<option value="saturday" {{ $schedule->day_of_week == 'saturday' ? 'selected' : '' }}>Saturday</option>
						<option value="sunday" {{ $schedule->day_of_week == 'sunday' ? 'selected' : '' }}>Sunday</option>
					</select>
				</div>

				<!-- Start Time Select -->
				<div class="mt-4">
					<x-label for="start_time" :value="__('Start Time')" />
					<input type="time" id="start_time" name="start_time"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
						value="{{ $schedule->start_time }}" required>
				</div>

				<!-- End Time Select -->
				<div class="mt-4">
					<x-label for="end_time" :value="__('End Time')" />
					<input type="time" id="end_time" name="end_time"
						class="block w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
						value="{{ $schedule->end_time }}" required>
				</div>

				<!-- Submit Button -->
				<div class="flex items-center justify-end mt-4">
					<x-button class="ml-4">
						{{ __('Update') }}
					</x-button>
				</div>
			</form>
		</div>
	</div>
</body>

</html>
