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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Notice</h1>
			<div class="bg-white rounded-2xl p-5 border-blue-300 border-2">
				@csrf
				@method('DELETE')
				<!-- Confirmation Text -->
				<div class="mb-4">
					<h1 class="text-xl font-semibold text-blue-900">
						Sorry, your account is disabled.
					</h1>
					<h3 class="italic text-gray-500 mt-4">Please contact admin to enable your account.</h3>
				</div>
			</div>
		</div>

	</div>
</body>

</html>
