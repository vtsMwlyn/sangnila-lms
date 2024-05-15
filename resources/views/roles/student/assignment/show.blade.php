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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<title>Sangnila Academy | LMS</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.student></x-navbar.student>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">My Assignments</h1>
			<h1 class="text-xl font-semibold text-blue-900 mb-4">Course: {{ $course->course_name }}</h1>

			@if(session()->has("successSubmitAssignment"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successSubmitAssignment") }}</p>
				</div>
			@elseif(session()->has("successEditSubmission"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successEditSubmission") }}</p>
				</div>
			@elseif(session()->has("maximumSubmission"))
				<div class="w-full bg-red-400 px-5 py-3 mb-5 rounded-lg">
					<p class="text-red-900">{{ session("maximumSubmission") }}</p>
				</div>
			@endif

			@forelse ($assignments as $asg)
				<div class="rounded-md w-full mt-5 my-5 p-5 border">
					<h3 class="text-xl font-semibold">{{ $asg->title }}</h3>
					@if($asg->submissions->count())
						<p class="text-green-700 mt-2 mb-2"><i class="bi bi-check-circle-fill"></i> Submitted</p>
						<a class="text-blue-500" href="{{ route("student.assignment.detail", [$course->id, $asg->id]) }}">Submission history and feedback</a>
					@endif

					<p class="mt-3 italic">Assignment Description:</p>
					<p>{{ $asg->desc }}</p>
					<p class="mt-3">Please submit before <span class="font-semibold">{{ $asg->deadline_date }} {{ $asg->deadline_time }}</span></p>


					@if($asg->submissions->count() != 10)
						<p class="font-semibold text-blue-700">New submissions allowed: {{ 10 - $asg->submissions->count() }} time(s)</p>
					@elseif($asg->submissions->count() == 10)
						<p class="font-semibold text-red-500">Number of new submissions reached its limit!</p>
					@endif

					<div class="flex items-center gap-1 mt-4">
						<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
							href="{{ $asg->link }}">
							Download
						</a>
						@if($asg->submissions->count())
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route("student.assignment.submit", [$course->id, $asg->id]) }}">
								New submission
							</a>
						@else
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route("student.assignment.submit", [$course->id, $asg->id]) }}">
								Upload
							</a>
						@endif
					</div>
				</div>
			@empty
				<p class="italic text-slate-500">- No assignments given yet -</p>
			@endforelse

		</div>
	</div>

</body>

</html>
