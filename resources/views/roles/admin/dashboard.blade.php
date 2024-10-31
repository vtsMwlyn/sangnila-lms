@extends("layouts.main-admin")

@section("title")
	<h1>Dashboard</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Dashboard</x-page-title>

		<div class="w-full flex sm:flex-row flex-col gap-5">
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-book"></i> Active Courses</p>
				<p class="text-2xl font-extrabold">N/A</p>
			</div>
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-person-fill"></i> Active Students</p>
				<p class="text-2xl font-extrabold">N/A</p>
			</div>
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-person-video3"></i> Active Teachers</p>
				<p class="text-2xl font-extrabold">N/A</p>
			</div>
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-person-exclamation"></i> Max-Session Students</p>
				<p class="text-2xl font-extrabold">N/A</p>
			</div>
		</div>

		<div class="w-full flex md:flex-row flex-col gap-5 mt-5">
			<!-- Progress -->
			<div class="w-full md:w-2/3 bg-white rounded-xl p-5 shadow-lg">
				<p class="font-semibold text-blue-800">Popular Courses</p>
				<hr class="mt-3">
				<div class="flex flex-col justify-between" style="height: 400px;">
					{{-- <div class="w-full flex flex-col overflow-y-auto py-3" style="height: 360px;">
						@forelse ($course_students as $i => $cstudent)
							<div class="w-full flex md:flex-row flex-col md:items-center gap-0">
								<p class="w-full md:w-1/4 font-semibold"><a href="{{ route('student.mycourse.show', $cstudent->course->id) }}" class="hover:underline hover:text-indigo-600">{{ $cstudent->course->course_name }}</a></p>
								<div class="grow flex flex-col border-l py-3">
									<div class="h-6 bg-orange-500" style="width: {{ ($attendance_progress[$i][0] / $attendance_progress[$i][1]) * 100 }}%"></div>
									<div class="h-6 bg-blue-600" style="width: {{ ($material_progress[$i][0] / $material_progress[$i][1]) * 100 }}%"></div>
								</div>
							</div>
						@empty

						@endforelse
					</div> --}}
					N/A
					<div class="flex md:flex-row flex-col gap-2 md:gap-5 w-full justify-end">
						<div class="flex items-center gap-3">
							<div class="h-4 w-4 bg-orange-500"></div>
							<p>Item 1</p>
						</div>
						<div class="flex items-center gap-3">
							<div class="h-4 w-4 bg-blue-600"></div>
							<p>Item 2</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Todo list -->
			<div class="w-full md:w-1/3 bg-white rounded-xl p-5 shadow-lg">
				<p class="font-semibold text-blue-800">To Do List</p>
				<hr class="mt-3">
				<div class="w-full flex flex-col overflow-y-auto" style="height: 400px;">
					<div class="w-full h-full flex items-center justify-center">- There's nothing to do for now -</div>
					{{-- @forelse($undone_assignment as $todoasg)
						<div class="flex items-start gap-2 my-3">
							<i class="bi bi-clipboard"></i>
							<p class="">Do and submit your work for assignment <span class="font-semibold">"{{ $todoasg->assignment->title }}"</span> before <span class="italic font-bold">{{ $todoasg->assignment->deadline_date }} {{ $todoasg->assignment->deadline_time }}</span></p>
						</div>
					@empty
						<p>- There's nothing to do for now -</p>
					@endforelse --}}
				</div>
			</div>
		</div>

		{{-- @php
			$n_announcement = 0;
		@endphp --}}

		<div class="w-full flex md:flex-row flex-col gap-5 mt-5">
			<!-- News and Announcement -->
			<div class="w-full md:w-1/2 bg-white rounded-3xl py-5 shadow-lg">
				<div class="px-5">
					<p class="font-bold text-dark-blue">News and Announcement</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
				</div>

				<div class="relative flex sm:flex-row flex-col justify-center items-center">
					<!-- Navigation and Sliders -->
					<div class="slider-controls flex gap-2 sm:justify-between justify-center sm:absolute w-full px-2" style="top: 36%;">
						<button id="prevBtn">
							<img src="{{ asset('img/arrow-left.svg') }}" class="w-8" alt="icon">
						</button>
						<button id="nextBtn">
							<img src="{{ asset('img/arrow-right.svg') }}" class="w-8" alt="icon">
						</button>
					</div>

					<div class="swiper w-10/12">
						<div class="swiper-wrapper">
							@forelse (App\Models\Announcement::all() as $announcement)
								@if($announcement->announce_from < now() && $announcement->announce_until > now())
									<a class="card-img flex flex-col items-stretch swiper-slide" href="{{ route('view-announcement', $announcement->id) }}">
										@php
											$target = json_decode($announcement->sent_to);
										@endphp

										@if($target[Auth::user()->role_id - 1] == "on")
											@if($announcement->image_path)
												<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" alt="announcement_img" class="w-full rounded-3xl" style="object-fit: cover; object-position: center; height: 340px;">
											@else
												<div class="flex bg-slate-200 items-center justify-center text-white font-extrabold grow rounded-3xl" style="height: 340px;">
													<i class="bi bi-megaphone-fill text-6xl"></i>
												</div>
											@endif
										@endif

										<div class="text-blue-900 text-center py-5 font-extrabold">{{ $announcement->title }}</div>
									</a>
								@endif
							@empty

							@endforelse
						</div>
					</div>
				</div>
			</div>

			<!-- Progress -->
			<div class="w-full md:w-1/2 bg-white rounded-xl p-5 shadow-lg">
				<p class="font-semibold text-blue-800">Calendar</p>
				<hr class="mt-3">
				{{-- <iframe src="https://calendar.google.com/calendar/embed?src=vannestheo.sangnila%40gmail.com&ctz=Asia%2FJakarta&no_cache=1&showNav=0&showTitle=0" style="border: 0" width="800" height="600" frameborder="0" scrolling="no" class="w-full"></iframe> --}}
				<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=2&ctz=Asia%2FJakarta&bgcolor=%23ffffff&showTabs=0&showPrint=0&showTitle=0&showCalendars=0&src=YmQ3NzMyZWY2NjMwMjc5ZDRkYTM0YmZmZWRlOGUwMWFlOTAzNDVmZWVlM2MxNWNkMzk0NGU3NTk4OGJhYzBjY0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&src=ZW4uaW5kb25lc2lhbiNob2xpZGF5QGdyb3VwLnYuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%23C0CA33&color=%230B8043" height="380" frameborder="0" scrolling="no" class="w-full mt-5"></iframe>
			</div>
		</div>
	</x-section-container>

	<script>
		$(document).ready(() => {
			// Initialize Swiper
			const swiper = new Swiper('.swiper', {
				slidesPerView: 1,
				spaceBetween: 0,
				freeMode: true,
				mousewheel: true, // Allow scrolling with mouse wheel
				grabCursor: true, // Allow grabbing on desktop for dragging
				loop: true,
				autoplay: {
					delay: 3000, // 3000ms = 3 seconds between slides
					disableOnInteraction: false // Continue autoplay after user interactions
				}
			});

			// Custom navigation buttons
			$('#prevBtn').click(() => swiper.slidePrev());
			$('#nextBtn').click(() => swiper.slideNext());
		});
	</script>
@endsection
