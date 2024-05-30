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

		<!-- Tailwind CDN -->
		<script src="https://cdn.tailwindcss.com"></script>

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>
	</head>

	<body class="bg-cover min-h-screen flex flex-col md:flex-row"
		style="background: url({{ asset('img/background.jpg') }}) no-repeat; cursor: url({{ asset('img/kursor.cur') }}), auto;">

		{{-- <x-navbar.admin></x-navbar.admin> --}}
		<x-sidebar.admin></x-sidebar.admin>

		<!-- Content Section -->
		<div class="container w-full md:w-4/5 min-h-screen flex flex-col justify-between">

			<div class="my-10 text-4xl text-white font-bold flex items-center justify-center">
				@yield("title")
				<a href="{{ route("profile.show") }}" class="flex flex-col absolute items-center right-10 invisible md:visible text-white hover:text-yellow-400 transition ease-in-out hover:scale-105 duration-600" style="cursor: url({{ asset('img/cursor2.cur') }}), pointer;">
					<i class="bi bi-person-circle"></i>
					<span class="text-center text-sm mt-1">{{ Auth::user()->full_name }}</span>
				</a>
			</div>

			<div class="bg-white flex flex-col justify-between h-full">
				<div class="p-10">
					@yield("content")
				</div>

				<x-footer></x-footer>
			</div>
		</div>
	</body>

</html>
