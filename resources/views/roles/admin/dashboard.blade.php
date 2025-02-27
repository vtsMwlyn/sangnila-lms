@extends("layouts.main-admin")

@section("title")
	<h1>Dashboard</h1>
@endsection

@section("content")
	<div class="w-full flex flex-col md:flex-row gap-5">
		<div class="flex flex-col gap-5 w-full md:w-2/3">
			<!-- Main stats -->
			<div class="w-full flex flex-wrap md:flex-nowrap justify-center gap-5">
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-materialsunlocked.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Active Courses</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $active_courses }}</p>
				</div>
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-courseenrolled.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Active Students</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $active_students }}</p>
				</div>
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-courseenrolled.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Active Teachers</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $active_teachers }}</p>
				</div>
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-courseenrolled.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Max Session Students</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $max_session_students }}</p>
				</div>
			</div>

			<!-- Something -->
			<div class="bg-white rounded-3xl p-5 shadow-lg">
				<div class="flex w-full justify-between">
					<p class="font-bold text-dark-blue">Popular Courses</p>
					{{-- <p class="italic text-slate-600">Server time: <span id="server-time"></span> GMT+7</p> --}}
				</div>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="flex flex-col justify-between overflow-y-auto" style="height: 400px;">
					<canvas id="myBarChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Todo list -->
		<div class="w-full md:w-1/3 flex flex-col gap-5">
			<div class="w-full bg-white rounded-3xl p-5 shadow-lg">
				<p class="font-bold text-dark-blue">Recent Teacher Activities</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 250px;">
					<ul class="list-disc list-inside">
						@foreach(App\Models\Attendance::where('created_at', 'like', '%'. Carbon\Carbon::today()->format('Y-m-d') .'%')->orderBy('created_at', 'desc')->get() as $recent_attendance)
							<li class="mb-4">
								<strong>{{ $recent_attendance->posted_by->details->gender == 1? 'Mr. ' : 'Ms. ' }} {{ $recent_attendance->posted_by->full_name }}</strong> has uploaded new attendance report for course <strong>{{ $recent_attendance->course->course_name }}</strong> at {{ Carbon\Carbon::parse($recent_attendance->created_at)->format('H:i:s') }} GMT+7
							</li>
						@endforeach
					</ul>
				</div>
			</div>

			<div class="w-full bg-white rounded-3xl p-5 shadow-lg">
				<p class="font-bold text-dark-blue">Recent Self Attendances</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 250px;">
					<ul class="list-disc list-inside">
						@foreach(App\Models\SelfAttendance::where('created_at', 'like', '%'. Carbon\Carbon::today()->format('Y-m-d') .'%')->orderBy('created_at', 'desc')->get() as $recent_self_attendance)
							<li class="mb-4">
								<strong>{{ $recent_self_attendance->user->details->gender == 1 && $recent_self_attendance->user->role_id == 2? 'Mr. ' : 'Ms. ' }} {{ $recent_self_attendance->user->full_name }}</strong> has checked in to course <strong>{{ $recent_self_attendance->course->course_name }}</strong> at {{ Carbon\Carbon::parse($recent_self_attendance->created_at)->format('H:i:s') }} GMT+7
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="w-full flex md:flex-row flex-col gap-5 mt-5">
		<!-- News and announcement -->
		<div class="w-full md:w-1/2 bg-white rounded-3xl py-5 shadow-lg">
			<div class="px-5">
				<p class="font-bold text-dark-blue">News and Announcement</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
			</div>

			@php
				$n_announcement = 0;
			@endphp

			<div class="relative flex sm:flex-row flex-col justify-center items-center">
				<!-- Navigation and Sliders -->
				<div class="swiper w-10/12">
					<div class="swiper-wrapper">
						@forelse (App\Models\Announcement::all() as $announcement)
							@if($announcement->announce_from < now() && $announcement->announce_until > now())
								<a class="card-img flex flex-col items-stretch swiper-slide" href="{{ route('view-announcement', $announcement->id) }}">
									@php
										$target = json_decode($announcement->sent_to);
									@endphp

									@if($target[Auth::user()->role_id - 1] == "on")
										@php
											$n_announcement++;
										@endphp
										@if($announcement->image_path)
											<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" alt="announcement_img" class="w-full rounded-3xl" style="object-fit: cover; object-position: center; height: 340px;">
										@else
											<div class="flex bg-slate-400 items-center justify-center text-white font-extrabold rounded-3xl grow" style="height: 340px;">
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

				@if($n_announcement > 1)
					<div class="slider-controls flex gap-2 sm:justify-between justify-center absolute w-full px-2" style="top: 36%;">
						<button id="prevBtn" class="absolute left-3">
							<img src="{{ asset('img/arrow-left.svg') }}" class="w-6" alt="icon">
						</button>
						<button id="nextBtn" class="absolute right-3">
							<img src="{{ asset('img/arrow-right.svg') }}" class="w-6" alt="icon">
						</button>
					</div>
				@endif
			</div>

			@if($n_announcement < 1)
				<div class="h-full w-full justify-center items-center flex">
					- There are no announcements -
				</div>
			@endif
		</div>

		<!-- Calendar -->
		<div class="w-full md:w-1/2 bg-white rounded-3xl p-5 shadow-lg">
			<p class="font-bold text-dark-blue">Calendar</p>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

			<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=2&ctz=Asia%2FJakarta&bgcolor=%23ffffff&showTabs=0&showPrint=0&showTitle=0&showCalendars=0&src=YmQ3NzMyZWY2NjMwMjc5ZDRkYTM0YmZmZWRlOGUwMWFlOTAzNDVmZWVlM2MxNWNkMzk0NGU3NTk4OGJhYzBjY0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&src=ZW4uaW5kb25lc2lhbiNob2xpZGF5QGdyb3VwLnYuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%23C0CA33&color=%230B8043" height="380" frameborder="0" scrolling="no" class="w-full mt-5"></iframe>
		</div>
	</div>

	@php
		$num = [];
		$course_names = [];
		$courses = App\Models\Course::where('status', 'active')->get();
		foreach($courses as $c){
			array_push($course_names, $c->course_name);
			array_push($num, App\Models\CourseStudent::where('course_id', $c->id)->get()->count());
		}
	@endphp

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

			setInterval(function(){
				$('#prevBtn').animate({'left': '0.5rem'}, 800);
				$('#prevBtn').animate({'left': '1rem'}, 800);
				$('#nextBtn').animate({'right': '0.5rem'}, 800);
				$('#nextBtn').animate({'right': '1rem'}, 800);
			}, 1000);
			
			// Get the canvas element
			const ctx = document.getElementById('myBarChart').getContext('2d');
			
			// Data for the bar chart
			const data = {
				labels: {!! json_encode($course_names) !!},
				datasets: [{
					label: 'Students Enrolled',
					data: {!! json_encode($num) !!},
					backgroundColor: 'rgba(54, 162, 235, 0.5)', // Bar color
					borderColor: 'rgba(54, 162, 235, 1)', // Border color
					borderWidth: 1 // Border thickness
				}]
			};

			// Chart configuration
			const config = {
				type: 'bar', // Bar chart type
				data: data,
				options: {
					responsive: true,
					scales: {
						y: {
							beginAtZero: true // Ensure y-axis starts at 0
						}
					}
				}
			};

			// Create the chart
			new Chart(ctx, config);
		});
	</script>

@endsection

