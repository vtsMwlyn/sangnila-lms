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

		<!-- Trix editor -->
		<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
		<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>


		<!-- Custom styles -->
		<link rel="stylesheet" href="{{ asset("css/custom_styles.css") }}">

		<style>
			body {
				background: white;
				font-family: "Geologica";
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

			.select2-container .select2-selection {
				display: flex !important;
				align-items: center !important;
				height: 2.45rem !important;
				border: solid 2px rgb(148 163 184) !important;
				width: 100% !important;
				padding-top: 0.25rem !important;
				padding-bottom: 0.25rem !important;
				padding-left: 0.75rem !important;
				padding-right: 0.75rem !important;
				min-height: 2.45rem !important;
				border-radius: 0.85rem !important;
			}

			@media screen and (max-width: 1024px){
				#page-title {
					z-index: 15;
				}
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

		<div class="flex flex-col items-center w-full" style="max-width: 2000px;">
			<!-- Back to top button and version -->
			<div class="fixed bottom-0 right-0 m-2 opacity-0 transition-opacity duration-500 ease-in-out" id="back-to-top">
				<a href="#">
					<div class="bg-light-blue animate-bounce rounded-full w-full text-xl p-2.5 flex justify-center align-center font-bold" style="width: 50px; height: 50px;">
						<i class="text-white text-center bi bi-arrow-up"></i>
					</div>
				</a>
			</div>

			<!-- Navbar -->
			<x-navbar></x-navbar>

			<!-- Sidebar and content -->
			<div class="w-full flex">
				<!-- Sidebar -->
				{{ $slot }}

				<!-- Content Section -->
				<div class="flex flex-col" style="width: 83%;" id="content-container">
					<div class="min-h-screen flex flex-col">
						<!-- Page title -->
						<div class="py-3 px-6 w-full text-white font-bold flex items-center justify-between lg:static sticky top-16" style="background: linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%);" id="page-title">
							<div class="md:text-3xl text-lg">@yield("title")</div>

							<!-- "Sidebar" for mobile and tablet -->
							<div class="relative lg:hidden flex flex-col items-end">
								<button type="button" id="medsmallmenu-toggler">
									<div class="w-40 bg-blue py-1.5 px-3 rounded-xl flex items-center justify-between">
										Menu
										<i class="bi bi-chevron-down"></i>
									</div>
								</button>
								<div class="absolute z-10 text-white top-16 w-80 rounded-xl flex flex-col py-2" id="medsmallmenu-dropdown" style="display: none; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); background: url({{ asset('img/sidebar-bg.png') }}) no-repeat center; background-size: cover;">
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


					// Select2 initialization
					$('.select2').select2({
						allowClear: false
					});

					// Apply resize observer to each container with class 'container_select2'
					$('.container_select2').each(function () {
						const container = this;
						const resizeObserver = new ResizeObserver(() => {
							$(container).find('.select2').each(function () {
								$(this).select2('destroy').select2({
									allowClear: false
								});
							});

							// stylingSelect2();
						});

						resizeObserver.observe(container);
					});


					// Datepicker mechanique
					const testinput = document.createElement('input');
					testinput.setAttribute('type', 'date');

					// If native date input is not supported, use jQuery UI Datepicker
					if (testinput.type !== 'date') {
						$('.date-input').datepicker({
							dateFormat: "yy-mm-dd", // Set the desired date format
							changeMonth: true, // Enable month dropdown
							changeYear: true,  // Enable year dropdown
							yearRange: "1900:+10", // Set the range of years
						});
					}
					else {
						$('.date-input').on({
							"focus": function(){
								this.showPicker();
							},
							"click": function(){
								this.showPicker();
							}
						});
					}


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

					const testus = $("#large-sidebar").clone();
					$("#medsmallmenu-dropdown").empty().append(testus);
				});

			</script>


			<!-- Include Select2 JavaScript -->
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		</div>
	</body>

</html>
