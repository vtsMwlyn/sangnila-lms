<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<!-- Metas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">

		<!-- App icon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

		<!-- Manifest(?) -->
		<link rel="manifest" href="{{ asset('site.webmanifest') }}">

		<!-- Bootstrap icons -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>

	</head>

	<body class="bg-cover min-h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.student></x-navbar.student>

		<!-- Content Section -->
		<div class="container mx-auto my-6 p-4 bg-white rounded-lg shadow-lg">
			@yield("content")
		</div>

	</body>

</html>
