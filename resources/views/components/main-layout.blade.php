<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Sangnila Academy | LMS</title>

		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

		<link rel="manifest" href="{{ asset('site.webmanifest') }}">

		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
		<link rel="stylesheet" href="{{ asset('css/color-pallete.css') }}">
		<link rel="stylesheet" href="{{ asset("css/custom-styles.css") }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
		<script src="https://cdn.tailwindcss.com"></script>

		<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
		<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
		<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
		<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
	</head>

	<body class="min-h-screen flex flex-col items-center text-xs sm:text-sm">
		{{-- Other popups --}}
		@yield("popup")

		{{-- Loading popup --}}
		<div class="popup-container w-full h-full hidden fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(10px); z-index: 100; background: rgba(0, 0, 0, 0.3);">
			<div class="rounded-3xl bg-white py-5 px-6 popup w-11/12 xl:w-1/3 h-1/4 flex gap-3 items-center justify-center" id="loading-popup">
				<div class="loader w-12 h-12 border-8 border-t-transparent border-light-blue rounded-full animate-spin"></div>
				<p class="font-extrabold text-xl animate-pulse">Please Wait...</p>
			</div>
		</div>

		{{-- Announcents popup --}}
		@if(session()->pull('show_announcement'))
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
						<div class="h-screen w-screen flex items-center justify-center fixed top-0 announcement-popup-container" style="@if($n == 1) backdrop-filter: blur(10px); background: rgba(0, 0, 0, 0.3); @endif z-index: 60;">
							<div class="bg-white w-11/12 xl:w-1/2 h-4/5 flex flex-col gap-5 justify-between items-center p-8 rounded-3xl announcement-popup" >
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
			{{-- Back to top --}}
			<div class="fixed bottom-0 right-0 m-2 flex flex-col items-end">
				<a href="#" class="opacity-0 transition-opacity duration-500 ease-in-out mb-2" id="back-to-top">
					<div class="bg-light-blue animate-bounce rounded-full w-full text-xl p-2.5 flex justify-center align-center font-bold" style="width: 50px; height: 50px;">
						<i class="text-white text-center bi bi-arrow-up"></i>
					</div>
				</a>
				{{-- <div class="w-[300px] min-h-[100px] border-2 border-slate-400 bg-white rounded-xl z-40 shadow-3xl status-notif relative right-[-300px] overflow-hidden" style="backdrop-filter: blur(10px); background-color: rgb(255, 255, 255, 0.8)">
					<div class="flex flex-col w-full items-start p-5">
						<p>Tes notification, this is showing the status of the performed action</p>
						<div class="flex justify-end w-full mt-3">
							<button type="button" class="dismiss-status-notif font-bold text-light-blue">Dismiss</button>
						</div>
						<div class="w-0 h-1 bg-light-blue mt-4 progress-bar"></div>
					</div>
				</div> --}}
				@if(session()->has("success"))
					<div class="hidden xl:block w-[300px] min-h-[100px] border-2 border-slate-400 bg-white rounded-xl z-40 shadow-3xl status-notif relative right-[-300px] overflow-hidden" style="backdrop-filter: blur(10px); background-color: rgb(255, 255, 255, 0.8)">
						<div class="flex flex-col w-full items-start p-5">
							<p>{{ session('success') }}</p>
							<div class="flex justify-end w-full mt-3">
								<button type="button" class="dismiss-status-notif font-bold text-light-blue">Dismiss</button>
							</div>
							<div class="w-0 h-1 bg-light-blue mt-4 progress-bar"></div>
						</div>
					</div>
				@elseif(session()->has("warning"))
					<div class="hidden xl:block w-[300px] min-h-[100px] border-2 border-slate-400 bg-white rounded-xl z-40 shadow-3xl status-notif relative right-[-300px] overflow-hidden" style="backdrop-filter: blur(10px); background-color: rgb(255, 255, 255, 0.8)">
						<div class="flex flex-col w-full items-start p-5">
							<p>{{ session('warning') }}</p>
							<div class="flex justify-end w-full mt-3">
								<button type="button" class="dismiss-status-notif font-bold text-light-blue">Dismiss</button>
							</div>
							<div class="w-0 h-1 bg-light-blue mt-4 progress-bar"></div>
						</div>
					</div>
				@elseif(session()->has("danger"))
					<div class="hidden xl:block w-[300px] min-h-[100px] border-2 border-slate-400 bg-white rounded-xl z-40 shadow-3xl status-notif relative right-[-300px] overflow-hidden" style="backdrop-filter: blur(10px); background-color: rgb(255, 255, 255, 0.8)">
						<div class="flex flex-col w-full items-start p-5">
							<p>{{ session('danger') }}</p>
							<div class="flex justify-end w-full mt-3">
								<button type="button" class="dismiss-status-notif font-bold text-red">Dismiss</button>
							</div>
							<div class="w-0 h-1 bg-red mt-4 progress-bar"></div>
						</div>
					</div>
				@endif
			</div>

			{{-- Navbar --}}
			<x-navbar></x-navbar>

			{{-- Sidebar and content --}}
			<div class="w-full flex">
				{{-- Sidebar --}}
				{{ $slot }}

				<button type="button" class="fixed bg-light-blue text-white px-1 h-12 rounded-r-full flex items-center hover:bg-slate-600" id="sidebar-toggler-larger"><i class="bi bi-caret-left-fill"></i></button>

				{{-- Content --}}
				<div class="flex flex-col w-full xl:w-[83%]" id="content-container">
					<div class="flex flex-col" id="content-wrapper" style="background: radial-gradient(circle at left top, rgb(175, 193, 221) 0%, #FFFFFF 100%);">
						{{-- Page title --}}
						<div class="py-3 px-6 w-full text-white font-bold flex items-center justify-between" style="background: linear-gradient(90deg, #1EB8CD 31%, rgba(53, 77, 155, 0) 100%);" id="page-title">
							<div class="md:text-3xl text-lg">@yield("title")</div>
						</div>

						<div class="p-4 md:p-8 flex flex-col items-center grow">
							@yield("content")
						</div>
					</div>

					{{-- Footer --}}
					<x-footer></x-footer>
				</div>
			</div>
		</div>

		{{-- Scripts --}}
		<script src="{{ asset('js/custom-script.js') }}"></script>
	</body>
</html>
