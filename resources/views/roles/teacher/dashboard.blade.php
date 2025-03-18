@extends("layouts.main-teacher")

@section("title")
	<h1>Dashboard</h1>
@endsection

@section("content")
	<style>
		.fc-toolbar-title {
			font-size: 16px !important;
		}

		.fc-button {
			text-transform: capitalize !important;
			padding: 2px 4px !important;
		}

		.fc-header-toolbar {
			margin-bottom: 10px !important;
		}
	</style>

	<div class="w-full flex flex-col lg:flex-row gap-5">
		<div class="flex flex-col gap-5 w-full lg:w-2/3">
			{{-- Main stats --}}
			<div class="w-full flex flex-wrap lg:flex-nowrap justify-center gap-5">
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/studentdashboard-materialsunlocked.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Courses Assigned</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $courses_assigned }}</p>
				</div>
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/dashboard-studentsteached.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Students Teached</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $students_teached }}</p>
				</div>
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/studentdashboard-sessionsattended.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Activities Created</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $activities_created }}</p>
				</div>
				<div class="rounded-3xl shadow-lg flex flex-col justify-between w-1/3 grow items-center gap-2 p-5" style="background-color: #FEFEFEB2;">
					<div class="flex flex-col items-center gap-2">
						<img src="{{ asset('img/studentdashboard-assignmentsdone.svg') }}" class="h-8 md:h-10" alt="icon">
						<p class="font-bold text-black text-center text-base">Assignments Given</p>
					</div>
					<p class="text-xl md:text-3xl font-extrabold">{{ $assignments_given }}</p>
				</div>
			</div>

			{{-- My Attendances --}}
			<div class="rounded-3xl p-5 shadow-lg grow" style="background-color: #FEFEFEB2;">
				<div class="flex w-full justify-between">
					<p class="font-bold text-dark-blue text-base">My Attendances</p>
					<p class="italic text-slate-600">Server time: <span id="server-time"></span> GMT+7</p>
				</div>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="flex flex-col justify-between overflow-y-auto w-full" style="height: 400px;">
					<ul class="list-disc list-inside">
						@foreach(Auth::user()->teached_courses as $course)
							@php
								$selfAttendances = App\Models\SelfAttendance::where("user_id", Auth::user()->id)->where('course_id', $course->id)->where("self_attendance_date", Carbon\Carbon::today()->format('Y-m-d'))->latest()->first();
							@endphp

							<div class="flex items-center justify-between font-bold w-full">
								<div>
									{{ $course->course_name }} - {{ ucwords($course->level) }}
									@if($selfAttendances && $selfAttendances->check_in_time && $selfAttendances->check_out_time)
										<i class="bi bi-check-circle-fill text-green-600"></i>
									@elseif($selfAttendances && $selfAttendances->check_in_time && !$selfAttendances->check_out_time)
										<i class="bi bi-clock-fill text-slate-500"></i>
									@else
										<i class="bi bi-x-lg text-red"></i>
									@endif
								</div>

								@if($selfAttendances && $selfAttendances->check_in_time && !$selfAttendances->check_out_time)
									<form action="{{ route('teacher.attendance.check-out.store', $course->id) }}" method="post">
										@csrf
										<button type="submit" href="{{ route('teacher.attendance.show', $course->id) }}" class="bg-indigo-600 hover:bg-slate-700 py-0.5 px-1.5 rounded-lg font-bold text-white" onclick="return confirm('Are you sure want to check out now?');">Check Out</button>
									</form>
								@else
									<a href="{{ route('teacher.attendance.check-in', $course->id) }}" class="bg-indigo-600 hover:bg-slate-700 py-0.5 px-1.5 rounded-lg font-bold text-white">Check In</a>
								@endif
							</div>

							<div class="flex gap-2 lg:gap-5 w-full mb-4 mt-2">
								<div class="w-1/2 flex flex-col">
									<x-label>Last check in time</x-label>
									<x-input type="text" disabled value="{{ $selfAttendances->check_in_time ?? 'N/A' }}"></x-input>
								</div>
								<div class="w-1/2 flex flex-col">
									<x-label>Last check out time</x-label>
									<x-input type="text" disabled value="{{ $selfAttendances->check_out_time ?? 'N/A' }}"></x-input>
								</div>
							</div>
						@endforeach
					</ul>
				</div>
			</div>
		</div>

		{{-- Todo list --}}
		<div class="w-full lg:w-1/3 flex gap-5">
			<div class="w-full rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
				<p class="font-bold text-dark-blue text-base">To Do List</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="w-full flex flex-col overflow-y-auto" style="height: 550px;">
					<ul class="list-disc list-inside">
						@foreach(Auth::user()->teached_courses as $course)
							@php
								$attendances = App\Models\Attendance::where("uploader_id", Auth::user()->id)->where('course_id', $course->id)->where("attendance_date", Carbon\Carbon::today()->format('Y-m-d'))->get();
							@endphp

							@if($attendances->count() < 1)
								<li class="mb-4" style="text-indent: -1.2rem; padding-left: 1.5rem; line-spacing: 10px;">
									Have you uploaded attendance report for <strong>{{ $course->course_name }} - {{ ucwords($course->level) }}</strong> today?
									<a href="{{ route('teacher.attendance.select-students', $course->id) }}" class="bg-indigo-600 hover:bg-slate-700 py-0.5 px-1.5 rounded-lg font-bold text-white">Make Report</a>
								</li>
							@endif
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="w-full flex lg:flex-row flex-col gap-5 mt-5">
		{{-- News and announcement --}}
		<div class="w-full lg:w-1/2 rounded-3xl py-5 shadow-lg" style="background-color: #FEFEFEB2;">
			<div class="px-5">
				<p class="font-bold text-dark-blue text-base">News and Announcement</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
			</div>

			@php
				$n_announcement = 0;
			@endphp

			<div class="relative flex sm:flex-row flex-col justify-center items-center mt-6"  style="max-height: 450px;">
				{{-- Navigation and Sliders --}}
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

		{{-- Calendar --}}
		<div class="w-full lg:w-1/2 rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
			<div class="flex justify-between w-full items-center">
				<p class="font-bold text-dark-blue text-base">Calendar</p>
				@if(!session('google_user_info.name'))
					<a href="{{ route('teacher.google.redirect') }}" class="bg-white shadow-lg py-1 px-3 flex items-center justify-center">
						<img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" style="width: 20px; margin-right: 10px;">
						Sync with Google
					</a>
				@else
					<div class="flex items-center gap-3">
						<div>Logged in as {{ session('google_user_info.name') }}</div>
						<form action="{{ route('teacher.google.logout') }}">
							@csrf
							<button class="shadow-lg py-1 px-3 flex items-center justify-center">
								<img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" style="width: 20px; margin-right: 10px;">
								Unsync from Google
							</button>
						</form>
					</div>
				@endif
			</div>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

			@if(session('google_user_info.name'))
				<div id="calendar" class="w-full"  style="max-height: 450px;"></div>
				{{-- <iframe src="https://calendar.google.com/calendar/embed?src={{ session('google_user_info.email') }}&ctz=Asia%2FJakarta&bgcolor=%23ffffff&showTabs=0&showPrint=0&showTitle=0" style="border: 0" height="380" scrolling="no" class="w-full mt-5" frameborder="0" scrolling="no"></iframe> --}}
			@else
				<iframe src="https://calendar.google.com/calendar/embed?src=id.indonesian%23holiday%40group.v.calendar.google.com&ctz=Asia%2FJakarta&bgcolor=%23ffffff&showTabs=0&showPrint=0&showTitle=0&showCalendars=0" style="border: 0" height="380" scrolling="no" class="w-full mt-5" frameborder="0" scrolling="no"></iframe>
			@endif
		</div>
	</div>

	<script>
		const allEventData = @json($allEventData ?? []);
		const allTaskData = @json($allTaskData ?? []);

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
			
			// Show server time
			let serverTime = @json(\Carbon\Carbon::now()->toDateTimeString());
			let currentTime = new Date(serverTime);

			function updateTime() {
				currentTime.setSeconds(currentTime.getSeconds() + 1);

				let hours = currentTime.getHours();
				let minutes = currentTime.getMinutes();
				let seconds = currentTime.getSeconds();

				hours = (hours < 10) ? '0' + hours : hours;
				minutes = (minutes < 10) ? '0' + minutes : minutes;
				seconds = (seconds < 10) ? '0' + seconds : seconds;

				$('#server-time').text(hours + ':' + minutes + ':' + seconds);
			}

			updateTime();
            setInterval(updateTime, 1000);

			@if(session('google_user_info.name'))
				// Handle calendar data
				const ibento = allEventData.map(ev => ({
					title: ev.summary,
					start: ev.start,
					end: ev.end,
					backgroundColor: ev.calendar != 'Holidays in Indonesia' ? '#16a34a' : '#ff5733',
					textColor: '#fff',
					borderColor: ev.calendar != 'Holidays in Indonesia' ? '#15803d' : '#ff0000'
				}));

				const tasuku = allTaskData.map(tsk => ({
					title: tsk.title,
					start: tsk.due,
					end: tsk.due,
					backgroundColor: '#337ab7', // Different color for tasks
					textColor: '#fff'
				}));

				const events = [...ibento, ...tasuku];

				var calendarEl = document.getElementById('calendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
					initialView: 'dayGridMonth',
					events: events,
					eventContent: function(info) {
						return {
							html: `<div style="background-color:${info.event.backgroundColor}; padding: 2px 1px; border-radius: 5px; color: white; font-size: 10px; text-wrap: wrap;">
									${info.event.title}
								</div>`
						};
					}
				});

				calendar.render();
			@endif
		});
	</script>
@endsection

