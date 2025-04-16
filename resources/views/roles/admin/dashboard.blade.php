@extends("layouts.main-admin")

@section("title")
	<h1>Dashboard</h1>
@endsection

@section("content")
	<div class="w-full flex flex-col xl:flex-row gap-5">
		<div class="flex flex-col gap-5 w-full xl:w-2/3">
			{{-- Main stats --}}
			<div class="w-full flex flex-wrap xl:flex-nowrap justify-center gap-5">
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/studentdashboard-materialsunlocked.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Active Courses</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $active_courses }}</p>
				</div>
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/studentdashboard-courseenrolled.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Active Students</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $active_students }}</p>
				</div>
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/dashboard-activeteachers.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Active Teachers</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $active_teachers }}</p>
				</div>
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/studentdashboard-assignmentsdone.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Max Session Students</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $max_session_students }}</p>
				</div>
			</div>

			{{-- Popular Courses --}}
			<div class="rounded-3xl p-5 shadow-lg grow" style="background-color: #FEFEFEB2;">
				<div class="flex w-full justify-between">
					<p class="font-bold text-dark-blue text-base">Popular Courses</p>
				</div>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="overflow-y-auto h-[220px] md:h-[400px] w-full flex justify-center">
					<canvas id="myBarChart" class="w-fit"></canvas>
				</div>
			</div>
		</div>

		{{-- Recent Activities --}}
		<div class="w-full xl:w-1/3 flex flex-col md:flex-row xl:flex-col gap-5">
			<div class="w-full rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
				<p class="font-bold text-dark-blue text-base">Recent Teacher Activities</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 250px;">
					<ul class="list-disc list-inside">
						@forelse($recent_attendances as $recent_attendance)
							<li class="mb-4">
								<strong>@if($recent_attendance->posted_by->role_id == 2){{ ($recent_attendance->posted_by->details->gender == 1)? 'Mr. ' : 'Ms. ' }}@endif {{ $recent_attendance->posted_by->full_name }}</strong> has uploaded new attendance report for course <strong>{{ $recent_attendance->course->course_name }}</strong> at {{ Carbon\Carbon::parse($recent_attendance->created_at)->format('D, d M Y H:i:s') }} GMT+7
							</li>
						@empty
							- No recent teacher activities -
						@endforelse
					</ul>
				</div>
			</div>

			<div class="w-full rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
				<p class="font-bold text-dark-blue text-base">Recent Self Attendances</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 250px;">
					<ul class="list-disc list-inside">
						@forelse($recent_self_attendances as $recent_self_attendance)
							<li class="mb-4">
								<strong>@if($recent_self_attendance->user->role_id == 2){{ ($recent_self_attendance->user->details->gender == 1)? 'Mr. ' : 'Ms. ' }}@endif {{ $recent_self_attendance->user->full_name }}</strong> has checked in to course <strong>{{ $recent_self_attendance->course->course_name }}</strong> at {{ Carbon\Carbon::parse($recent_self_attendance->created_at)->format('D, d M Y H:i:s') }} GMT+7
							</li>
						@empty
							- No recent self attendances -
						@endforelse
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="w-full flex xl:flex-row flex-col gap-5 mt-5">
		{{-- News and announcement --}}
		<div class="w-full xl:w-1/2 rounded-3xl py-5 shadow-lg" style="background-color: #FEFEFEB2;">
			<div class="px-5">
				<p class="font-bold text-dark-blue text-base">News and Announcement</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
			</div>

			@php
				$n_announcement = 0;
			@endphp

			<div class="relative flex sm:flex-row flex-col justify-center items-center">
				{{-- Navigation and Sliders --}}
				<div class="swiper w-10/12">
					<div class="swiper-wrapper">
						@forelse (App\Models\Announcement::all() as $announcement)
							@php
								$target = json_decode($announcement->sent_to);
							@endphp
							@if($announcement->announce_from < now() && $announcement->announce_until > now() && in_array(Auth::user()->id, $target))
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

		{{-- Calendar --}}
		<div class="w-full xl:w-1/2 rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
			<p class="font-bold text-dark-blue text-base">Calendar</p>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

			<iframe src="https://calendar.google.com/calendar/embed?src=id.indonesian%23holiday%40group.v.calendar.google.com&ctz=Asia%2FJakarta&bgcolor=%23ffffff&showTabs=0&showPrint=0&showTitle=0&showCalendars=0" style="border: 0" height="380" scrolling="no" class="w-full mt-5" frameborder="0" scrolling="no"></iframe>
		</div>
	</div>

	<script>
		$(document).ready(() => {
			// Initialize Swiper
			const swiper = new Swiper('.swiper', {
				slidesPerView: 1,
				spaceBetween: 0,
				freeMode: true,
				mousewheel: false, // Allow scrolling with mouse wheel
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
				labels: {!! json_encode($course_names) !!}.slice(0, 10),
				datasets: [
					{
						label: 'Students Enrolled',
						data: {!! json_encode($nums_total) !!}.slice(0, 10),
						backgroundColor: [],
					},
					{
						label: 'Students Learning',
						data: {!! json_encode($nums_learning) !!}.slice(0, 10),
						backgroundColor: [],
					},
					{
						label: 'Students Completed',
						data: {!! json_encode($nums_complete) !!}.slice(0, 10),
						backgroundColor: [],
					},
				]
			};

			// Chart configuration
			const config = {
				type: 'bar', // Bar chart type
				data: data,
				options: {
					responsive: true,
					scales: {
						y: {
							beginAtZero: true,
						}
					},
					plugins: {
						legend: {
							labels: {
								generateLabels: function (chart) {
									const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart);
									labels.forEach((label, i) => {
										const dataset = chart.data.datasets[i];

										if (dataset.backgroundColor instanceof CanvasGradient) {
											// Set legend color to the first color stop manually
											const firstStopColors = ["#212F63", "#1EB8CD", "rgb(22,163,74)"];
											label.fillStyle = firstStopColors[i]; 
										}
									});
									return labels;
								}
							}
						}
					}
				}
			};

			// Create the chart
			const chart = new Chart(ctx, config);
			
			const gradient1 = ctx.createLinearGradient(0, chart.height, 0, 0);
			gradient1.addColorStop(0, "#212F63");
			gradient1.addColorStop(1, "#354D9B");

			const gradient2 = ctx.createLinearGradient(0, chart.height, 0, 0);
			gradient2.addColorStop(0, "#1EB8CD");
			gradient2.addColorStop(1, "#BEE2DB");

			const gradient3 = ctx.createLinearGradient(0, chart.height, 0, 0);
			gradient3.addColorStop(0, "rgb(22 163 74)");
			gradient3.addColorStop(1, "rgb(109, 218, 147)");

			chart.data.datasets[0].backgroundColor = gradient1;
			chart.data.datasets[1].backgroundColor = gradient2;
			chart.data.datasets[2].backgroundColor = gradient3;
			chart.update();
		});
	</script>
@endsection

