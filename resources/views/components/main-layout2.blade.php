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
		<style>
			html {
				scroll-behavior: smooth;
			}

			/* Custom Cursor */
			a {
				cursor: url("{{ asset('img/cursor2.cur') }}"), pointer;
			}

			button[type="button"], button[type="submit"] {
				cursor: url("{{ asset('img/cursor2.cur') }}"), pointer;
			}

			body {
				background: url("{{ asset('img/background.jpg') }}") no-repeat center center / cover;
				cursor: url("{{ asset('img/kursor.cur') }}"), auto;
			}

			/* Thin Scrollbar */
			* {
				scrollbar-width: thin;
				scrollbar-color: #888 #f7f7f7;
			}

			/* Select2 */
			.select2-selection__arrow {
				margin-top: 7px;
				margin-right: 10px;
			}

			/* Import student table */
			table#student-to-import thead th.fixed1 {
				min-width: 200px;
				max-width: 200px;
			}

			table#student-to-import thead th.fixed2 {
				min-width: 150px;
				max-width: 150px;
			}

			table#student-to-import tbody td.fixed1 {
				min-width: 200px;
				max-width: 200px;
				word-wrap: break-word;
			}

			table#student-to-import tbody td.fixed2 {
				min-width: 150px;
				max-width: 150px;
				word-wrap: break-word;
			}

		</style>

		<!-- Include jQuery  -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>
	</head>

	<body class="bg-cover min-h-screen flex flex-col">
		<!-- Back to top button and version -->
		<div class="fixed z-50 bottom-0 left-0 m-2 text-black md:text-white">
			<div class="">{{ trans("strings.version") }}</div>
		</div>
		<div class="fixed bottom-0 right-0 m-2 opacity-0 transition-opacity duration-500 ease-in-out" id="back-to-top">
			<a href="#">
				<div class="bg-orange-500 rounded-full w-full text-xl p-2.5 flex justify-center align-center font-bold" style="width: 50px; height: 50px;">
					<i class="text-white text-center bi bi-arrow-up"></i>
				</div>
			</a>
		</div>

		<!-- Content Section -->
		{{ $slot }}

		<div class="p-10">
			@yield("content")
		</div>

		<x-footer></x-footer>

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
