<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<!-- Metas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- App icon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

		<!-- Manifest(?) -->
		<link rel="manifest" href="{{ asset('site.webmanifest') }}">

		<!-- CSS -->
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">

		<!-- Bootstrap icons -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

		<!-- Tailwind CDN -->
		<script src="https://cdn.tailwindcss.com"></script>

		<!-- Include Select2 CSS -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


		<!-- Custom styles -->
		<link rel="stylesheet" href="{{ asset("css/custom_styles.css") }}">

		<style>
			/* Custom Cursor */
			a {
				cursor: url("{{ asset('img/cursor2.cur') }}"), pointer;
			}

			button[type="button"], button[type="submit"] {
				cursor: url("{{ asset('img/cursor2.cur') }}"), pointer;
			}

			body {
				cursor: url("{{ asset('img/kursor.cur') }}"), auto;
			}
		</style>

		<!-- Include jQuery  -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>
	</head>

	<body class="bg-cover min-h-screen flex flex-col">
		<!-- Fixed background image -->
		<img src="{{ asset("img/background.jpg") }}" alt="" height="100vh" class="w-screen h-screen fixed top-0 z-0" style="object-fit: cover; object-position: center;">

		<!-- Page -->
		<div class="absolute z-10 w-full min-h-full flex flex-col justify-between" style="background-color: rgba(0, 0, 0, 0.4);">
			<!-- Back to top button and version -->
			<div class="fixed z-60 bottom-1 left-1 m-2 text-white">
				<div class="">{{ trans("strings.version") }}</div>
			</div>
			<div class="fixed bottom-1 right-1 m-2 opacity-0 transition-opacity duration-500 ease-in-out animate-bounce" id="back-to-top">
				<a href="#">
					<div class="bg-orange-500 rounded-full w-full text-xl p-2.5 flex justify-center align-center font-bold" style="width: 50px; height: 50px;">
						<i class="text-white text-center bi bi-arrow-up"></i>
					</div>
				</a>
			</div>

			<!-- Navbar/sidebar -->
			{{ $slot }}

			<!-- Content Section -->
			<div class="p-10 grow">
				<div class="flex flex-col items-center w-full">
					@yield("content")
				</div>
			</div>

			<!-- Footer -->
			<x-footer></x-footer>
		</div>

		<!-- Scripts -->
		<!-- Back to top button -->
		<script>
			const backToTopButton = document.getElementById("back-to-top");

			window.addEventListener("scroll", function() {
				if (window.scrollY > window.innerHeight * 0.3) {
					backToTopButton.classList.remove("opacity-0");
					backToTopButton.classList.add("opacity-100");
				} else {
					backToTopButton.classList.remove("opacity-100");backToTopButton.classList.add("opacity-0");
				}
			});
		</script>

		<!-- Include Select2 JavaScript -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	</body>
</html>
