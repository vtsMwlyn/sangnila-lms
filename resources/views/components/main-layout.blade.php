<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<!-- Metas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- App title -->
		<title>Sangnila Academy | LMS</title>

		<!-- App icon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

		<!-- Manifest -->
		<link rel="manifest" href="{{ asset('site.webmanifest') }}">

		<!-- CSS -->
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
		<link rel="stylesheet" href="{{ asset('css/color-pallete.css') }}">
		<link rel="stylesheet" href="{{ asset("css/custom-styles.css") }}">

		<!--Font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">


		<!-- Bootstrap icons -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

		<!-- Tailwind CDN -->
		<script src="https://cdn.tailwindcss.com"></script>
		{{-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> --}}


		<!-- Include select2 CSS -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

		<!-- Swiper.js CDN Links -->
		<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
		<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

		<!-- Trix editor -->
		<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
		<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

		<!-- Include jQuery  -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- Include select2 JavaScript -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

		<!-- Include chart.js -->
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<!-- Include cropper.js -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

		<!-- FullCalendar -->
		<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
	</head>

	<body class="min-h-screen flex flex-col items-center text-xs sm:text-sm" data-tjzlptoheng="{{ csrf_token() }}">
		<!-- Other popups -->
		@yield("popup")

		<!-- Loading popup -->
		<div class="popup-container w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(5px); z-index: 100; background: rgba(0, 0, 0, 0.3);">
			<div class="rounded-3xl bg-white py-5 px-6 popup w-11/12 md:w-1/3 h-1/4 flex gap-3 items-center justify-center" id="loading-popup">
				<div class="loader w-12 h-12 border-8 border-t-transparent border-light-blue rounded-full animate-spin"></div>
				<p class="font-extrabold text-xl animate-pulse">Please Wait...</p>
			</div>
		</div>

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
				<button class="bg-light-blue py-2 px-4 rounded-xl text-white hover:bg-slate-600 fixed bottom-3 right-3" id="dismiss-announcements-btn" type="button" style="z-index: 70;">Dismiss all</button>
			@endif

			@forelse ($all_announcements as $announcement)
				@php $target = json_decode($announcement->sent_to); @endphp

				@if($target[Auth::user()->role_id - 1] == "on")
					@if($announcement->announce_from < now() && $announcement->announce_until > now())
						@php $n++; @endphp
						<div class="h-screen w-screen flex items-center justify-center fixed top-0 announcement-popup-container" style="@if($n == 1) backdrop-filter: blur(5px) brightness(0.5);@endif z-index: 60;">
							<div class="bg-white w-full md:w-1/2 h-4/5 flex flex-col gap-5 justify-between items-center p-8 rounded-3xl announcement-popup" >
								<h1 class="text-xl font-bold text-blue-900">{{ $announcement->title }}</h1>
								<div class="grow overflow-y-auto">
									@if($announcement->image_path)
										<div class="flex justify-center w-full mb-8">
											<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" alt="announcement_img" class="w-3/4">
										</div>
									@else
										<div class="w-full flex justify-center">
											<div class="flex bg-slate-200 items-center justify-center text-white font-extrabold w-3/4 h-[300px] mb-8">
												<i class="bi bi-megaphone-fill text-6xl"></i>
											</div>
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

				<button type="button" class="fixed bg-light-blue text-white px-1 h-12 rounded-r-full flex items-center hover:bg-slate-600" id="sidebar-toggler"><i class="bi bi-chevron-double-right"></i></button>

				<!-- Content Section -->
				<div class="flex flex-col" style="width: 83%;" id="content-container">
					<div class="flex flex-col" id="content-wrapper">
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
						</div>

						<div class="p-4 md:p-8 flex flex-col items-center grow" style="background: radial-gradient(circle at left top, rgb(175, 193, 221) 0%, #FFFFFF 100%);">
							@yield("content")
						</div>
					</div>

					<x-footer></x-footer>
				</div>
			</div>
		</div>

		<!-- Scripts -->
		<script src="{{ asset('js/custom-script.js') }}"></script>

		<script>
			function resetSidebarToggler(){
				if($(window).width() <= 2000 && $(window).width() >= 1024){
					$('#sidebar-toggler').show();
					$('#sidebar-toggler').css('left', $('#sidebar-container').outerWidth()).css('top', $(window).innerHeight() / 2);
				}
				else {
					$('#sidebar-toggler').hide();
				}
			}

			$(window).on('resize', resetSidebarToggler);

			$(document).ready(() => {
				resetSidebarToggler();

				$('#sidebar-toggler').click(function(){
					if($('#sidebar-container').is(':visible')){
						$('#sidebar-toggler').css({'left': 0});
						$('#sidebar-container').hide();
						$('#content-container').css({'width': '100%'});
					}
					else {
						$('#sidebar-toggler').css({'left': $('#sidebar-container').outerWidth()});
						$('#sidebar-container').show();
						$('#content-container').css({'width': '83%'});
					}
				});
			});
		</script>
	</body>
</html>
