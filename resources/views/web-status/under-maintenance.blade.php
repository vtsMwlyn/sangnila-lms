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

		<!-- Poppins font -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
				font-family: "Poppins";
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

			<!-- Content Section -->
			<div class="grow flex w-full items-center justify-center">
				@auth
					<form action="{{ route("sysadmin.logout") }}" method="post" class="absolute top-5 right-5">
						@csrf
						<x-button class="bg-red-600"><i class="bi bi-box-arrow-in-left"></i> Logout</x-button>
					</form>
				@endauth
				<x-section-container>
					<x-page-title>Notice</x-page-title>
					<div class="bg-blue-900 rounded-2xl p-5">
						<!-- Confirmation Text -->
						<div class="mb-4 p-5 text-white">
							<h1 class="text-xl font-semibold">
								<i class="bi bi-exclamation-circle"></i> Sorry, Sangnila LMS is currently under maintenance.
							</h1>
							<p class="mt-5 py-3 px-6 border rounded-lg bg-red-800"><span class="font-bold">Maintenance Detail: </span>Applying update and fixes for upcoming version.</p>
							<h3 class="italic  mt-8">The maintenance is held to perform update and fixes. During maintenance period, there are no activities can be done. So please wait and try again later, we appreciate your patience.</h3>
						</div>
					</div>
				</x-section-container>
			</div>

			<!-- Footer -->
			<x-footer></x-footer>
		</div>

		<!-- Scripts -->
		<!-- Back to top button -->
		<script>
			$(document).ready(() => {
				const backToTopButton = document.getElementById("back-to-top");
				const navbar = document.getElementById("navbar-container");

				window.addEventListener("scroll", function() {
					if (window.scrollY > window.innerHeight * 0.3) {
						backToTopButton.classList.remove("opacity-0");
						backToTopButton.classList.add("opacity-100");

						navbar.style.backdropFilter = "none";
						navbar.style.backgroundColor = "rgb(17, 41, 102)";

					} else {
						backToTopButton.classList.remove("opacity-100");backToTopButton.classList.add("opacity-0");

						navbar.style.backdropFilter = "blur(3px)";
						navbar.style.backgroundColor = "rgba(17, 41, 102, 0.5)";
					}
				});
			});
		</script>

		<!-- Include Select2 JavaScript -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	</body>
</html>
