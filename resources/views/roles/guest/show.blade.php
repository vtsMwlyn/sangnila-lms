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
	<div class="bg-cover h-screen" style="background-image: url({{ asset('img/background.png') }}) ; width: 100%;">
		<div class="flex justify-center align-items-center h-screen">
			Show Course
		</div>
	</div>

</body>

</html>
