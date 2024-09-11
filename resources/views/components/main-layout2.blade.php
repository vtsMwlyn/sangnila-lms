<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<!-- Metas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

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

		<!-- Push notification (temporary disabled because of tholol vapid key) -->
		{{-- <script src="{{ asset('js/push-notifications.js') }}" defer></script> --}}

		<!-- Poppins font -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

		<!-- Trix editor -->
		<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
		<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

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

			trix-toolbar [data-trix-button-group="file-tools"] {
				display: none;
			}

			/* Targeting unordered lists specifically within Trix editor */
			trix-editor[input="content"] ul {
				list-style-type: disc; /* Use the disc style for unordered lists */
				padding-left: 1.5em; /* Adjust the padding for proper indentation */
			}

			/* Targeting ordered lists specifically within Trix editor */
			trix-editor[input="content"] ol {
				list-style-type: decimal; /* Use decimal style for ordered lists */
				padding-left: 1.5em; /* Adjust the padding for proper indentation */
			}

			/* Targeting list items within Trix editor */
			trix-editor[input="content"] ul li,
			trix-editor[input="content"] ol li {
				margin-bottom: 0.5em; /* Adjust spacing between list items */
			}

			trix-editor[input="content"] a {
				font-weight: bold;
				color: rgb(30 58 138);
			}

			div.announcementContent ul {
				list-style-type: disc; /* Use the disc style for unordered lists */
				padding-left: 1.5em; /* Adjust the padding for proper indentation */
			}

			/* Targeting ordered lists specifically within Trix editor */
			div.announcementContent ol {
				list-style-type: decimal; /* Use decimal style for ordered lists */
				padding-left: 1.5em; /* Adjust the padding for proper indentation */
			}

			/* Targeting list items within Trix editor */
			div.announcementContent ul li,
			div.announcementContent ol li {
				margin-bottom: 0.5em; /* Adjust spacing between list items */
			}

			div.announcementContent a {
				font-weight: bold;
				color: rgb(30 58 138);
			}

			div.announcementContent a:hover {
				text-decoration: underline;
			}
		</style>

		<!-- Include jQuery  -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- Include Select2 JavaScript -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>
	</head>

	<body class="bg-cover min-h-screen flex flex-col">
		{{-- @dd(Storage::url("app/public/" . App\Models\Announcement::all()[1]->image_path)) --}}

		<!-- Fixed background image -->
		<img src="{{ asset("img/background.jpg") }}" alt="" height="100vh" class="w-screen h-screen fixed top-0 z-0" style="object-fit: cover; object-position: center;">

		<!-- Page -->
		<div class="absolute z-10 w-full min-h-full flex flex-col justify-between" style="background-color: rgba(0, 0, 0, 0.4);">
			<!-- Back to top button and version -->
			<div class="fixed z-50 bottom-1 left-1 m-2 text-white">
				<div class="">{{ trans("strings.version") }}</div>
			</div>
			<div class="fixed bottom-1 right-1 m-2 opacity-0 transition-opacity duration-500 ease-in-out animate-bounce z-50" id="back-to-top">
				<a href="#">
					<div class="bg-orange-500 rounded-full w-full text-xl p-2.5 flex justify-center align-center font-bold" style="width: 50px; height: 50px;">
						<i class="text-white text-center bi bi-arrow-up"></i>
					</div>
				</a>
			</div>

			@if(session()->pull('show_announcement'))
				@forelse (App\Models\Announcement::all() as $announcement)
					@php
						$target = json_decode($announcement->sent_to);
					@endphp

					@if($target[Auth::user()->role_id - 1] == "on")
						@if($announcement->announce_from < now() && $announcement->announce_until > now())
							<div class="h-screen w-screen flex items-center justify-center fixed top-0 z-50 popup-container" style="background: rgba(0, 0, 0, 0.5)">
								<div class="bg-white w-1/2 h-4/5 flex flex-col gap-5 justify-between items-center p-8 rounded-xl popup" >
									<h1 class="text-xl font-bold text-blue-900">{{ $announcement->title }}</h1>
									<div class="grow overflow-y-auto">
										@if($announcement->image_path)
											<div class="flex justify-center w-full mb-8">
												<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" alt="announcement_img" class="w-3/4">
											</div>
										@endif
										<div class="announcementContent">
											{!! $announcement->content !!}
										</div>
									</div>
									<p class="text-sm text-slate-500">- Click anywhere to close -</p>
								</div>
							</div>
						@endif
					@endif
				@empty

				@endforelse

			@endif

			<!-- Navbar/sidebar -->
			{{ $slot }}

			<!-- Content Section -->
			<div class="px-10 @auth pb-10 @else py-10 @endauth grow">
				<div class="flex flex-col items-center w-full">
					<div class="w-full lg:w-5/6 flex justify-between gap-3 items-center">
						<!-- Breadcrumbs -->
						@auth
							<x-breadcrumbs>
								@yield("breadcrumbs-extension")
							</x-breadcrumbs>
						@endauth

						@if(Auth::user()->role_id == 1)
							<x-anchor-button class="bg-yellow-600" href="{{ route('admin.announcement.index') }}"><i class="bi bi-megaphone"></i> Manage Announcement</x-anchor-button>
						@endif

						<x-inbox></x-inbox>
					</div>

					@yield("content")
				</div>
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

				$(".popup-container").click(function(e){
					if (!$(e.target).closest(".popup").length) {
						$(this).fadeOut();
					}

				});

				$(".announcementContent a").each((index, anchor) => {
					$(anchor).attr("target", "blank");
				});
			});
		</script>
	</body>
</html>
