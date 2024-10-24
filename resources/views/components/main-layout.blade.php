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
		<link rel="stylesheet" href="{{ asset('css/color-pallete.css') }}">

		<!--Font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

		<!-- Bootstrap icons -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

		<!-- Tailwind CDN -->
		<script src="https://cdn.tailwindcss.com"></script>

		<!-- Include Select2 CSS -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

		<!-- Swiper.js CDN Links -->
		<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
		<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


		<!-- Custom styles -->
		<link rel="stylesheet" href="{{ asset("css/custom_styles.css") }}">

		<style>
			body {
				background: white;
				font-family: "Geologica";
			}
		</style>

		<!-- Include jQuery  -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>
	</head>

	<body class="min-h-screen flex flex-col items-center sm:text-sm text-xs">
		<!-- Other popups -->
		@yield("popup")

		<!-- Announcements -->
		@if(session()->pull('show_announcement'))
		{{-- @if(true) --}}
			@php
				$n = 0;
				$m = 0;
				$all_announcements = App\Models\Announcement::all();

				foreach($all_announcements as $anc){
					$target = json_decode($anc->sent_to);

					if($target[Auth::user()->role_id - 1] == "on" && $anc->announce_from < now() && $anc->announce_until > now()){
						$m++;
					}
				}
			@endphp

			@if($m > 0)
				<button class="fixed bottom-3 right-3 font-bold text-blue py-2 px-3 border-2 border-blue rounded-xl hover:text-slate-500 hover:border-slate-500" id="dismiss-announcements-btn" type="button" style="z-index: 70;">Dismiss all</button>
			@endif

			@forelse ($all_announcements as $announcement)
				@php $target = json_decode($announcement->sent_to); @endphp

				@if($target[Auth::user()->role_id - 1] == "on")
					@php $n++; @endphp
					@if($announcement->announce_from < now() && $announcement->announce_until > now())
						<div class="h-screen w-screen flex items-center justify-center fixed top-0 announcement-popup-container" style="backdrop-filter: blur(5px); background: {{ ($n == 1)? 'rgba(0, 0, 0, 0.3)' : 'none' }}; z-index: 60;">
							<div class="bg-white w-1/2 h-4/5 flex flex-col gap-5 justify-between items-center p-8 rounded-3xl announcement-popup" >
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

		<div class="flex flex-col items-center w-full" style="max-width: 2500px;">
			<!-- Back to top button and version -->
			<div class="fixed z-50 bottom-0 left-0 m-2 lg:text-white text-slate-800">
				<div class="flex gap-1">{{ trans("strings.version") }}<h1 id="screen"></h1></div>
			</div>
			<div class="fixed bottom-0 right-0 m-2 opacity-0 transition-opacity duration-500 ease-in-out" id="back-to-top">
				<a href="#">
					<div class="bg-light-blue animate-bounce rounded-full w-full text-xl p-2.5 flex justify-center align-center font-bold" style="width: 50px; height: 50px;">
						<i class="text-white text-center bi bi-arrow-up"></i>
					</div>
				</a>
			</div>

			<!-- Navbar -->
			<x-navbar.student></x-navbar.student>

			<!-- Sidebar and content -->
			<div class="w-full flex">
				<!-- Sidebar -->
				{{ $slot }}

				<!-- Content Section -->
				<div class="flex flex-col" style="width: 83%;" id="content-container">
					<div class="min-h-screen flex flex-col">
						<!-- Page title -->
						<div class="py-3 px-6 w-full text-white font-bold flex items-center justify-between" style="background: linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%);">
							<div class="text-3xl">@yield("title")</div>

							<!-- "Sidebar" for mobile and tablet -->
							<div class="relative lg:hidden flex flex-col items-end">
								<button type="button" id="medsmallmenu-toggler">
									<div class="w-40 bg-blue py-1.5 px-3 rounded-xl flex items-center justify-between">
										Menu
										<i class="bi bi-chevron-down"></i>
									</div>
								</button>
								<div class="absolute z-10 text-white top-16 w-80 rounded-xl flex flex-col py-2" id="medsmallmenu-dropdown" style="display: none; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); background: url({{ asset('img/sidebar-bg.png') }}) no-repeat center; background-size: cover;">
									<a href="{{ route('home') }}"><div class="w-full px-5 py-1 font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-dashboard.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Dashboard</div></a>
									<a href="{{ route('student.mycourse.index') }}"><div class="w-full px-5 py-1 font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-courses.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Courses</div></a>
									<a href="{{ route('student.assignment.index') }}"><div class="w-full px-5 py-1 font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-assignment.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Assignment</div></a>
									<a href="{{ route('student.attendance.index') }}"><div class="w-full px-5 py-1 font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-attendance.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Attendance</div></a>
									<a href="#"><div class="w-full px-5 py-1 font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-schedule.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Schedule</div></a>
									<a href="#"><div class="w-full px-5 py-1 font-semibold flex items-center gap-1"><img src="{{ asset('img/sidebar-announcement.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Announcement</div></a>
								</div>
							</div>

							<script>
								$(document).ready(function () {
									// Toggle dropdown on button click
									$("#medsmallmenu-toggler").click(function (e) {
										e.stopPropagation(); // Prevent the click event from bubbling up to the document

										$("#medsmallmenu-dropdown").toggle();
									});

									// Close dropdown when clicking outside of it
									$(document).click(function (e) {
										if (!$(e.target).closest("#medsmallmenu-dropdown, #medsmallmenu-toggler").length) {
											$("#medsmallmenu-dropdown").hide();
										}
									});
								});
							</script>
						</div>

						<div class="p-8 flex flex-col items-center grow" style="background: radial-gradient(circle at left top, rgb(175, 193, 221) 0%, #FFFFFF 100%)">
							@yield("content")
						</div>
					</div>

					<x-footer></x-footer>
				</div>
			</div>

			<!-- Scripts -->
			<script>
				$(document).ready(() => {
					// Display back to top button on page scroll more than 100vh
					const backToTopButton = document.getElementById("back-to-top");

					window.addEventListener("scroll", function() {
						if (window.scrollY > window.innerHeight * 0.3) {
							backToTopButton.classList.remove("opacity-0");
							backToTopButton.classList.add("opacity-100");
						} else {
							backToTopButton.classList.remove("opacity-100");backToTopButton.classList.add("opacity-0");
						}
					});

					function adjustLayouts(){
						// Minimum height for sidebar
						$("#sidebar").css("height", ($(this).height() - $("#navbar").outerHeight()));

						// Set content and sidebar width
						if($(this).width() < 1024){
							$("#sidebar-container").css("display", "none");
							$("#content-container").css("width", "100%");
						}
						else {
							$("#sidebar-container").css("display", "block");
							$("#content-container").css("width", "83%");
						}

						$("#screen").text(`(Resolution: ${window.innerWidth}x${window.innerHeight})`);
					}

					adjustLayouts();

					$(window).on("resize", function(){
						adjustLayouts();
					});


					// Announcement popups
					let popups = $(".announcement-popup-container").length;

					function remove_dismiss_announcement_popup(){
						popups--;
						if(popups == 0){
							$("#dismiss-announcements-btn").fadeOut();
						}
					}

					$(".announcement-popup-container").click(function(e){
						if (!$(e.target).closest(".announcement-popup").length) {
							remove_dismiss_announcement_popup();
							$(this).fadeOut();
						}

					});

					$("#dismiss-announcements-btn").click(function(){
						$(".announcement-popup-container").fadeOut();
						$(this).fadeOut();
					});

					$(".announcementContent a").each((index, anchor) => {
						$(anchor).attr("target", "blank");
					});

					// Other popups
					$(".popup-dismiss").click(function(){
						$(this).closest(".popup-container").fadeOut();
					});

					// Dropdowns
					$(".dropdown-toggler").click(function (e) {
						e.stopPropagation();

						$(this).closest(".dropdown-container").find(".dropdown-menu").toggle();
					});

					$(document).click(function (e) {
						if (!$(e.target).closest(".dropdown-menu, .dropdown-toggler").length) {
							$(".dropdown-menu").hide();
						}
					});

				});

			</script>


			<!-- Include Select2 JavaScript -->
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		</div>
	</body>

</html>
