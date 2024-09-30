@extends("layouts.main-student")

@section("title")
	<h1>Dashboard</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Dashboard</x-page-title>

		<div class="w-full flex sm:flex-row flex-col gap-5">
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-book"></i> Courses Enrolled</p>
				<p class="text-2xl font-extrabold">{{ $courses_enrolled }}</p>
			</div>
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-clipboard-check"></i> Materials Unlocked</p>
				<p class="text-2xl font-extrabold">{{ $materials_unlocked }}</p>
			</div>
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-calendar2-check"></i> Sessions Attended</p>
				<p class="text-2xl font-extrabold">{{ $sessions_attended }}</p>
			</div>
			<div class="bg-white rounded-xl shadow-lg flex flex-col w-full sm:w-1/3 items-center gap-5 p-5">
				<p class="font-bold text-blue-800 text-xl"><i class="bi bi-clipboard-check"></i> Assignments Done</p>
				<p class="text-2xl font-extrabold">{{ $assignments_done }}</p>
			</div>
		</div>

		<div class="w-full flex md:flex-row flex-col gap-5 mt-5">
			<!-- Progress -->
			<div class="w-full md:w-2/3 bg-white rounded-xl p-5 shadow-lg">
				<p class="font-semibold text-blue-800">Course Progress</p>
				<hr class="mt-3">
				<div class="flex flex-col justify-between" style="height: 400px;">
					<div class="w-full flex flex-col overflow-y-auto py-3" style="height: 360px;">
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
					</div>
					<div class="flex md:flex-row flex-col gap-2 md:gap-5 w-full justify-end">
						<div class="flex items-center gap-3">
							<div class="h-4 w-4 bg-orange-500"></div>
							<p>Attendance</p>
						</div>
						<div class="flex items-center gap-3">
							<div class="h-4 w-4 bg-blue-600"></div>
							<p>Material</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Todo list -->
			<div class="w-full md:w-1/3 bg-white rounded-xl p-5 shadow-lg">
				<p class="font-semibold text-blue-800">To Do List</p>
				<hr class="mt-3">
				<div class="w-full flex flex-col overflow-y-auto" style="height: 400px;">
					@forelse($undone_assignment as $todoasg)
						<div class="flex items-start gap-2 my-3">
							<i class="bi bi-clipboard"></i>
							<p class="">Do and submit your work for assignment <span class="font-semibold">"{{ $todoasg->assignment->title }}"</span> before <span class="italic font-bold">{{ $todoasg->assignment->deadline_date }} {{ $todoasg->assignment->deadline_time }}</span></p>
						</div>
					@empty
						<div class="w-full h-full flex items-center justify-center">- There's nothing to do for now -</div>
					@endforelse
				</div>
			</div>
		</div>

		{{-- @php
			$n_announcement = 0;
		@endphp --}}

		<div class="w-full flex md:flex-row flex-col gap-5 mt-5">
			<!-- Progress -->
			<div class="w-full md:w-1/2 bg-white rounded-xl p-5 shadow-lg">
				<p class="font-semibold text-blue-800">News and Announcement</p>
				<hr class="mt-3">

				<div class="relative flex sm:flex-row flex-col justify-center items-center">
					<!-- Sliders -->
					<div class="relative z-10 mt-5 overflow-hidden w-full sm:w-10/12">
						<div id="cardSlider" class="flex transition-transform duration-300 ease-in-out">
							@forelse (App\Models\Announcement::all() as $announcement)
								@if($announcement->announce_from < now() && $announcement->announce_until > now())
									<a class="card-img flex flex-col items-stretch" href="{{ route('view-announcement', $announcement->id) }}">
										@php
											$target = json_decode($announcement->sent_to);
											// $n_announcement++;
										@endphp

										@if($target[Auth::user()->role_id - 1] == "on")
											@if($announcement->image_path)
												<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" alt="announcement_img" class="w-full" style="object-fit: cover; object-position: center; height: 340px;">
											@else
												<div class="flex bg-slate-200 items-center justify-center text-white font-extrabold grow">
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

					<!-- Navigation buttons -->
					<div class="slider-controls flex gap-2 sm:justify-between justify-center sm:absolute w-full" style="top: 45%;">
						<button id="prevBtn" class="transition-all duration-200 flex items-center justify-center font-semibold rounded-full px-3 py-2 bg-blue-950 text-white">
							<i class="bi bi-arrow-left"></i>
						</button>
						<button id="nextBtn" class="transition-all duration-200 flex items-center justify-center font-semibold rounded-full px-3 py-2 bg-blue-950 text-white">
							<i class="bi bi-arrow-right"></i>
						</button>
					</div>
					{{-- @if($n_announcement > 1)
						<div class="slider-controls flex gap-2 justify-between absolute w-full" style="top: 45%;">
							<button id="prevBtn" class="transition-all duration-200 flex items-center justify-center font-semibold rounded-full px-3 py-2 bg-blue-950 text-white">
								<i class="bi bi-arrow-left"></i>
							</button>
							<button id="nextBtn" class="transition-all duration-200 flex items-center justify-center font-semibold rounded-full px-3 py-2 bg-blue-950 text-white">
								<i class="bi bi-arrow-right"></i>
							</button>
						</div>
					@endif --}}
				</div>
			</div>

			<!-- Calendar -->
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
			// Card sliders
			let currentCard = 0;
			let helper = 0;
			if($(window).width() > 576){
				helper = 10;
			}

			const cardSlider = $('#cardSlider');
			const cardWidth = cardSlider.parent().width();
			// const cardWidth = parseFloat(cardWidthStr.replace("px", ''));

			console.log("Container width: " + cardWidth);

			$(".card-img").each(function(){
				$(this).css({"min-width": cardWidth + helper, "max-width": cardWidth + helper});
				console.log("Cards width: " + $(this).width());
			});

			const totalCards = cardSlider.children().length;
			const sliderWidth = totalCards * cardWidth;
			cardSlider.css({"min-width": sliderWidth, "max-width": sliderWidth});
			console.log(`Slider width: ${totalCards} x ${cardWidth} = ${cardSlider.width()}`);

			function nextCard() {
				const visibleCards = cardSlider.parent().width() / cardWidth;
				if (currentCard < totalCards - visibleCards) {
					currentCard++;
					const translateValue = -currentCard * (cardWidth + helper);
					cardSlider.css('transform', `translateX(${translateValue}px)`);
					updateNavButtons();
				}
			}

			function prevCard() {
				if (currentCard > 0) {
					currentCard--;
					const translateValue = -currentCard * (cardWidth + helper);
					cardSlider.css('transform', `translateX(${translateValue}px)`);
					updateNavButtons();
				}
			}

			function updateNavButtons() {
				if (currentCard === 0) {
					$('#prevBtn').prop('disabled', true).css({
						"background-color": "lightgray",
						"color": "rgb(23 37 84)"
					});
				} else {
					$('#prevBtn').prop('disabled', false).css({
						"background-color": "rgb(23 37 84)",
						"color": "white"
					});
				}

				const visibleCards = cardSlider.parent().width() / cardWidth;
				if (currentCard >= totalCards - visibleCards) {
					$('#nextBtn').prop('disabled', true).css({
						"background-color": "lightgray",
						"color": "rgb(23 37 84)"
					});
				} else {
					$('#nextBtn').prop('disabled', false).css({
						"background-color": "rgb(23 37 84)",
						"color": "white"
					});
				}
			}

			$('#nextBtn').on('click', nextCard);
			$('#prevBtn').on('click', prevCard);

			updateNavButtons();

			$(window).resize(function() {
				cardSlider.css('width', sliderWidth);
				updateNavButtons();
			});
		});
	</script>
@endsection
