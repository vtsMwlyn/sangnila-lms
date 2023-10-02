<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<title>Laravel</title>

</head>

<body>
	<div class="bg-cover h-screen" style="background-image: url({{ asset('img/background.png') }}) ; width: 100%;">
		<div class="flex justify-center align-items-center h-screen">
			@forelse ($courses as $course)
				<div>This is course</div>
			@empty
				<div>No Course Available</div>
			@endforelse
		</div>
	</div>

</body>

</html>
