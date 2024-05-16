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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Unassign Teacher</h1>
			<form method="POST" action="{{ route('admin.teacher.unassign.destroy', ['teacher_id' => $teacher->id, 'course_id' => $course->id]) }}" class="bg-white rounded-2xl p-5 border-blue-300 border-2">
				@csrf
				@method('delete')
				<!-- Confirmation Text -->
				<div class="mb-6">
					<h1 class="text-xl font-semibold text-blue-900">
						Are you sure you want to unassign
						<span class="text-red-500 font-bold">{{ $teacher->full_name }}</span>
						from
						<span class="text-green-500 font-bold">{{ $course->course_name }}</span>?
					</h1>
				</div>

				<!-- Yes/No Buttons -->
				<div class="flex space-x-4">
					<button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-red-500 transition duration-300">Yes</button>
					<button type="button" onclick="history.back()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-900 transition duration-300">No</button>
				</div>
			</form>
		</div>

	</div>
</body>

</html>
