@extends("layouts.main-student")

@section("title")
	<h1>Dashboard</h1>
@endsection

@section("content")
	<div class="w-full flex flex-col md:flex-row gap-5">
		<div class="flex flex-col gap-5 w-full md:w-2/3">
			<!-- Main stats -->
			<div class="w-full flex flex-wrap md:flex-nowrap justify-center gap-5">
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-courseenrolled.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Courses Enrolled</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $courses_enrolled }}</p>
				</div>
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-materialsunlocked.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Materials Unlocked</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $activities_unlocked }}</p>
				</div>
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-sessionsattended.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Sessions Attended</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $sessions_attended }}</p>
				</div>
				<div class="bg-white rounded-3xl shadow-lg flex flex-col w-1/3 grow items-center gap-2 p-5">
					<img src="{{ asset('img/studentdashboard-assignmentsdone.svg') }}" class="w-8 h-8 md:w-12 md:h-12" alt="icon">
					<p class="font-bold text-black text-center">Assignments Done</p>
					<p class="text-xl md:text-3xl font-extrabold">{{ $assignments_done }}</p>
				</div>
			</div>

			<!-- Progress -->
			<div class="bg-white rounded-3xl p-5 shadow-lg grow">
				<p class="font-bold text-dark-blue">Courses Progress</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				@php
					$activity_bars = [];
					$attendance_bars = [];
					$course_names = [];

					foreach($course_students as $i => $cstudent){
						$mp_bar_percentage = 0;
						$ap_bar_percentage = 0;

						if($activity_progress[$i][1] != 0){
							$mp_bar_percentage = ($activity_progress[$i][0] / $activity_progress[$i][1]) * 100;
						}

						if($attendance_progress[$i][1] != 0){
							$ap_bar_percentage = ($attendance_progress[$i][0] / $attendance_progress[$i][1]) * 100;
						}

						array_push($activity_bars, $mp_bar_percentage);
						array_push($attendance_bars, $ap_bar_percentage);
						array_push($course_names, $cstudent->course->course_name);
					}
				@endphp

				<div class="flex flex-col justify-between" style="height: 400px;">
					<canvas id="myHorizontalBarChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Todo list -->
		<div class="w-full md:w-1/3 flex flex-col gap-5">
			<div class="w-full bg-white rounded-3xl p-5 shadow-lg">
				<p class="font-bold text-dark-blue">Assignment Reminder</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 250px;">
					<ul class="list-disc list-inside">
						@forelse($undone_assignment as $todoasg)
							<li class="mb-4" style="text-indent: -1.5rem; padding-left: 1.5rem;">
								Do and submit your work for assignment <span class="font-bold">"{{ $todoasg->assignment->title }}"</span> before <span class="font-bold">{{ Carbon\Carbon::parse($todoasg->assignment->deadline_date)->format('D, d M Y') }} {{ Carbon\Carbon::parse($todoasg->assignment->deadline_time)->format('H:i') }} GMT+7</span>
							</li>
						@empty
							<div class="w-full h-full flex items-center justify-center">- There's nothing to do for now -</div>
						@endforelse
					</ul>
				</div>
			</div>

			<div class="w-full bg-white rounded-3xl p-5 shadow-lg">
				<p class="font-bold text-dark-blue">Self Attendance Reminder</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 250px;">
					<ul class="list-disc list-inside">
						@foreach(Auth::user()->enrolled_courses as $course)
							@php
								$selfAttendance = App\Models\SelfAttendance::where("user_id", Auth::user()->id)->where('course_id', $course->id)->where("self_attendance_date", Carbon\Carbon::today()->format('Y-m-d'))->latest()->first();
							@endphp

							@if(!$selfAttendance)
								<li class="mb-4" style="text-indent: -1.2rem; padding-left: 1.5rem; line-spacing: 10px;">
									Have you checked in to <strong>{{ $course->course_name }} - {{ ucwords($course->level) }}</strong> today?
									<a href="{{ route('student.mycourse.check-in', $course->id) }}" class="bg-indigo-600 hover:bg-slate-700 py-0.5 px-1.5 rounded-lg font-bold text-white">Check In</a>
								</li>
							@endif
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
							@php
								$target = json_decode($announcement->sent_to);
							@endphp
							@if($announcement->announce_from < now() && $announcement->announce_until > now() && $target[Auth::user()->role_id - 1] == "on")
								<a class="card-img flex flex-col items-stretch swiper-slide" href="{{ route('view-announcement', $announcement->id) }}">
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

			const ctx = document.getElementById('myHorizontalBarChart').getContext('2d');
		
			const data = {
				labels: {!! json_encode($course_names) !!},
				datasets: [
					{
						label: 'Activity',
						data: {!! json_encode($activity_bars) !!},
						backgroundColor: [],
					},
					{
						label: 'Attendance',
						data: {!! json_encode($attendance_bars) !!},
						backgroundColor: [],
					},
				]
			};

			const config = {
				type: 'bar',
				data: data,
				options: {
					indexAxis: 'y',
					scales: {
						x: {
							beginAtZero: true,
							min: 0,
							max: 100,
						}
					},
					barThickness: 20,
				}
			};

			const chart = new Chart(ctx, config);

			const gradient1 = ctx.createLinearGradient(0, 0, chart.width, 0);
			gradient1.addColorStop(0, "#1EB8CD");
			gradient1.addColorStop(1, "#BEE2DB");

			const gradient2 = ctx.createLinearGradient(0, 0, chart.width, 0);
			gradient2.addColorStop(0, "#212F63");
			gradient2.addColorStop(1, "#354D9B");

			chart.data.datasets[0].backgroundColor = gradient1;
			chart.data.datasets[1].backgroundColor = gradient2;
			chart.update();
		});
	</script>
@endsection
