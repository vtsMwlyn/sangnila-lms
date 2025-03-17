<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		{{-- Metas --}}
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		{{-- CSS --}}
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
		<link rel="stylesheet" href="{{ asset('css/color-pallete.css') }}">

		{{-- Scripts --}}
        <script src="{{ asset('js/app.js') }}" defer></script>

		{{-- App icon --}}
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

		{{-- Manifest(?) --}}
		<link rel="manifest" href="{{ asset('site.webmanifest') }}">

		{{-- Bootstrap icons --}}
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

		{{-- Tailwind CDN --}}
		<script src="https://cdn.tailwindcss.com"></script>

		{{-- Include jQuery  --}}
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		{{-- Font --}}
		<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

		{{-- Custom styles --}}
		<style>
			body {
				font-family: "Geologica";
			}
		</style>

		{{-- App title --}}
		<title>Sangnila Academy | LMS</title>

	</head>

	<body class="bg-cover min-h-screen flex items-center justify-center"
		style="background: url({{ asset('img/loginbg.png') }}) no-repeat right center; background-size: cover;">
		<div class="fixed text-white bottom-0 left-0 m-2">
			{{ trans("strings.version") }}
		</div>

		{{-- Loading popup --}}
		<div class="popup-container hidden w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(5px); z-index: 100; background: rgba(0, 0, 0, 0.3);">
			<div class="rounded-3xl bg-white py-5 px-6 popup w-11/12 md:w-1/3 h-1/4 flex gap-3 items-center justify-center" id="loading-popup">
				<div class="loader w-12 h-12 border-8 border-t-transparent border-light-blue rounded-full animate-spin"></div>
				<p class="font-extrabold text-xl animate-pulse">Please Wait...</p>
			</div>
		</div>

		<div class="w-full flex flex-col items-center">
			@yield("content")

			<x-footer></x-footer>
		</div>
	</body>

	{{-- Custom script --}}
	<script src="{{ asset('js/custom-script-logreg.js') }}"></script>
</html>
