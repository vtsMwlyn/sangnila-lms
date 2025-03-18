@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	{{-- @dd($activityProgresses) --}}

	<div class="rounded-3xl w-full py-5 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
		<div class="flex flex-col w-full px-5 lg:px-8">
			@php
				$courseStudent = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first();
				$teacher = $courseStudent->teacher;
				$progress_data_exists = count($activityProgresses) > 0 && count($activityProgresses->first()["progresses"]) > 0;
			@endphp

			<x-back-button href="{{ route('student.mycourse.index') }}"></x-back-button>
			<div class="w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
				<x-page-title>{{ $course->course_name }} - {{ ucwords($course->level) }}</x-page-title>

				@if(!$max_session_reached)
					<div class="flex items-center gap-3 my-4 lg:my-0">
						@if(!$unfinishedSelfAttendance)
							<x-anchor-button href="{{ route('student.mycourse.check-in', $course->id) }}">
								<i class="bi bi-stopwatch"></i> Check In
							</x-anchor-button>
						@else
							<button disabled class="bg-gray-800 text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold disabled:opacity-50">
								<i class="bi bi-stopwatch"></i> {{ $unfinishedSelfAttendance->check_in_time }}
							</button>
						@endif

						{{-- Already checked in but haven't checked out --}}
						@if($unfinishedSelfAttendance && !$unfinishedSelfAttendance->check_out_time)
							<form action="{{ route('student.mycourse.check-out.store', $course->id) }}" method="post">
								@csrf
								<x-button onclick="return confirm('Are you sure want to check out now?');">
									<i class="bi bi-stopwatch"></i> Check Out
								</x-button>
							</form>

						{{-- Already checked in and checked out --}}
						@elseif($unfinishedSelfAttendance && $unfinishedSelfAttendance->check_out_time)
							<button disabled class="bg-gray-800 text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold disabled:opacity-50">
								<i class="bi bi-stopwatch"></i> {{ $unfinishedSelfAttendance->check_out_time }}
							</button>

						{{-- Haven't checked in and haven't checked out --}}
						@else
							<button disabled class="bg-gray-800 text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold disabled:opacity-50">
								<i class="bi bi-stopwatch"></i> Check Out
							</button>
						@endif
					</div>
				@endif
			</div>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

			<div class="flex gap-2 items-center">
				<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
				{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
			</div>
			<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

			@if(session()->has("success"))
				<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
			@elseif(session()->has("warning"))
				<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
			@elseif(session()->has("danger"))
				<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
			@endif

			@if($should_pay_soon)
				<div class="bg-yellow-400 text-orange-700 py-3 px-6 rounded-lg font-semibold my-4">
					<i class="bi bi-exclamation-square"></i>
					<span>Your progress in <span class="font-bold">{{ $course->course_name }}</span> course is reaching its maximum session. Please do the payment to extend your study in the course.</span>
				</div>
			@endif

			@if(!$max_session_reached)
				{{-- Horizontal Scroller --}}
				<style>
					/* Swiper container for horizontal scroll */
					.swiper {
						width: 100%;
						padding-top: 10px;
						padding-bottom: 10px;
					}

					/* Style the session buttons */
					button.unlocked-session {
						border-color: #9CA3AF;
						background: white;
						color: #012967;
					}

					button.locked-session {
						background: rgba(0, 0, 0, 0.5);
						border-color: #9CA3AF;
						color: rgb(51 65 85);
					}

					button.selected-session {
						background: linear-gradient(90deg, #1EB8CD 0%, #354D9B 100%);
						color: white;
						border: none;
					}

					.swiper-slide {
						display: flex;
						justify-content: center;
						align-items: center;
					}
				</style>

				<div class="swiper" style="padding: 0;">
					<div class="swiper-wrapper">
						@if(count($activityProgresses) > 0)
							@foreach ($activityProgresses as $groupedAP)
								@php
									$is_unlocked = false;
									if(count($groupedAP["progresses"]) > 0){
										foreach($groupedAP["progresses"] as $ap){
											if($ap["progress"]->status == 'unlocked'){
												$is_unlocked = true;
												break;
											}
										}
									}

									$all_opened = true;
									if(count($groupedAP["progresses"]) > 0){
										foreach($groupedAP["progresses"] as $ap){
											if($ap["progress"]->already_opened == 'no'){
												$all_opened = false;
												break;
											}
										}
									}
								@endphp

								<div class="swiper-slide relative">
									<button type="button" id="{{ $loop->iteration }}" data-session="{{ $groupedAP['session'] }}" data-is_unlocked="{{ $is_unlocked }}"
										class="flex items-center justify-center session-buttons px-2 py-2.5 mb-8 mt-4 rounded-xl text-base font-extrabold hover:scale-105
										@if($is_unlocked) unlocked-session @else locked-session @endif @if($loop->iteration == 1) selected-session @endif"
										style="border-width: 3px; min-width: 110px;">
										Session {{ $loop->iteration }}
									</button>
									<div class="absolute top-6 right-3 rounded-full h-2.5 w-2.5 @if(!$is_unlocked || $loop->iteration == 1 || $all_opened) hidden @endif" style="background: linear-gradient(180deg, #1EB8CD 0%, #BEE2DB 100%);"></div>
								</div>
							@endforeach
						@else
							<div class="my-6">
								Your teacher haven't unlocked any activities for you. Please ask him/her to unlock activities for you.
							</div>
						@endif
					</div>
				</div>

				{{-- @dd($activityProgresses->first()["progresses"][0]['topic']) --}}

				@if($progress_data_exists)
					<div class="mb-6">
						<h1 class="text-dark-blue font-bold text-xl"><span id="num">1</span>. <span id="topic">{{ $activityProgresses->first()["progresses"][0]["topic"]->title }}</span></h1>
						<div class="flex flex-col gap-1 mt-2" id="learning-outcomes">
							@if(count($activityProgresses) > 0)
								@foreach(json_decode($activityProgresses->first()["progresses"][0]["learning_outcomes"]) as $lo)
									<div class="flex gap-2 items-center">
										<img src="{{ asset('img/bullet.svg') }}" alt="icon" class="w-3 h-3">
										<div>LO{{ $lo->number }}: {{ $lo->title }}</div>
									</div>
								@endforeach
							@endif
						</div>
					</div>
				@endif
			@endif
		</div>

		{{-- This part is dynamically generated by JavaScript everytime session button is clicked, these are for placeholder --}}
		@if(!$max_session_reached)
			@if($progress_data_exists)
				<div id="activities-container" class="w-full">
					@foreach($activityProgresses->first()['progresses'] as $ap)
						@if($ap['progress']->status == 'unlocked')
							<div class="w-full bg-white px-8 py-3">
								<div class="w-full flex items-center">
									<div class="flex w-3/4 md:w-1/2 items-start">
										<h1 class="text-dark-blue font-bold text-xl">Course Activity #{{ $loop->iteration }}</h1>
									</div>
									<div class="flex w-1/4 md:w-1/2 items-start justify-end md:justify-start">
										<h1 class="text-dark-blue font-bold text-xl">Action</h1>
									</div>
								</div>
								<div class="w-full flex items-center mt-1">
									<div class="flex w-3/4 md:w-1/2 items-start">
										<h2>{{ $ap["activity"]->title }}</h2>
									</div>
									<div class="flex w-1/4 md:w-1/2 items-start justify-end md:justify-start">
										<div class="flex gap-1">
											<a href="{{ route('student.mycourse.preview', $ap["activity"]->id) }}"><img src="{{ asset('img/view.svg') }}" alt="icon" class="w-8 h-8 hover:scale-110"></a>
											@if($ap["progress"]->meeting_link)
												<a href="{{ $ap["progress"]->meeting_link }}" target="_blank" class="h-8 w-8 flex items-center justify-center hover:scale-110"><img src="{{ asset('img/online-meeting.svg') }}"></a>
											@else
												<a href="#" class="h-8 w-8 flex items-center justify-center hover:scale-110"><img src="{{ asset('img/online-meeting.svg') }}"></a>
											@endif
										</div>
									</div>
								</div>
							</div>

							<div class="flex flex-col gap-8 my-8 w-full px-8">
								<div class="flex flex-col">
									<h3 class="text-xs">Description</h3>
									<h2>{!! nl2br($ap["activity"]->desc) !!}</h2>
								</div>
								<div class="flex flex-col">
									<h3 class="text-xs">Delivery Mode</h3>
									<h2>{{ $ap["progress"]->meeting_link ? 'Online' : 'Onsite' }}</h2>
								</div>
							</div>
						@endif
					@endforeach
				</div>
			@endif
		@else
			<div class="w-full py-8 flex items-center justify-center">
				<img src="{{ asset('img/access-locked.png') }}" class="w-80 h-80" alt="locked">
			</div>
		@endif
	</div>

	<script>
		$(document).ready(() => {
			const actprog = @json($activityProgresses);

			// Initialize Swiper
			const swiper = new Swiper('.swiper', {
				slidesPerView: $(window).width() / 165,
				spaceBetween: 0,
				freeMode: true,
				mousewheel: true, // Allow scrolling with mouse wheel
				grabCursor: true, // Allow grabbing on desktop for dragging
			});

			// Handle session button click
			$(".session-buttons").click(function () {
				const sessionNumber = $(this).data('session');
				const is_unlocked = $(this).data('is_unlocked');

				// console.log(actprog[sessionNumber].progresses);

				if (is_unlocked) {
					// Remove selected-session class from all unlocked buttons
					$(".unlocked-session").each(function () {
						$(this).removeClass("selected-session");
					});

					// Add selected-session class to the clicked button
					$(this).addClass("selected-session");
					$("#activities-container").html('');

					// Fill other data dynamically
					const lerningautkam = [];
					let i = 1;

					actprog[sessionNumber].progresses.forEach(ap => {
						if(ap.progress.status == 'unlocked'){
							ap.activity.learning_outcomes.forEach(lo => {
								if (!lerningautkam.some(existingLo => existingLo.id === lo.id)) {
									lerningautkam.push(lo);
								}
							});

							const activityNumber = i;
							const activityTitle = ap.activity.title;
							const activityDesc = ap.activity.desc;
							const materialPreviewLink = `{{ route('student.mycourse.preview', ':id') }}`.replace(':id', ap.activity.id);
							const meetingLink = ap.progress.meeting_link ? ap.progress.meeting_link : '#';
							const anchorTarget = ap.progress.meeting_link ? '_blank' : '_self';
							const topicTitle = ap.topic.title;
							$("#topic").text(topicTitle);
							$("#num").text(sessionNumber);

							const whiteLongBox = $("<div>").addClass("w-full bg-white px-8 py-3")
								.append(
									$("<div>").addClass("w-full flex items-center")
										.append(
											$("<div>").addClass("flex w-1/2 items-start").append(
												$("<h1>").addClass("text-dark-blue font-bold text-xl").text(`Course Activity #${activityNumber}`)
											)
										)
										.append(
											$("<div>").addClass("flex w-1/2 items-start").append(
												$("<h1>").addClass("text-dark-blue font-bold text-xl").text("Action")
											)
										)
								)
								.append(
									$("<div>").addClass("w-full flex items-center mt-1")
										.append(
											$("<div>").addClass("flex w-1/2 items-start").append(
												$("<h2>").text(activityTitle)
											)
										)
										.append(
											$("<div>").addClass("flex w-1/2 items-start").append(
												$("<div>").addClass("flex gap-1")
													.append(
														$('<a>').attr('href', materialPreviewLink).html('<img src="{{ asset('img/view.svg') }}" alt="icon" class="w-8 h-8 hover:scale-110">')
													)
													.append(
														$('<a>').attr({'href': meetingLink, 'target': anchorTarget}).addClass('h-8 w-8 border-slate-400 rounded-lg flex items-center justify-center hover:scale-110').css('border-width', '3px').html('<i class="bi bi-camera-video text-slate-400"></i>')
													)
											)
										)
								);

							const deliveryMode = ap.progress.meeting_link ? 'Online' : 'Onsite';							
							const activityDetails = $("<div>").addClass("flex flex-col gap-8 my-8 w-full px-8")
								.append(
									$("<div>").addClass("flex flex-col")
										.append(
											$("<h3>").addClass("text-xs").text("Description")
										)
										.append(
											$("<h2>").html(activityDesc.replace(/\n/g, '<br>'))
										)
								).append(
									$("<div>").addClass("flex flex-col")
										.append(
											$("<h3>").addClass("text-xs").text("Delivery Mode")
										)
										.append(
											$("<h2>").text(deliveryMode)
										)
								);

							$("#activities-container").append(whiteLongBox).append(activityDetails);
						}

						i++;
					});

					// Update learning outcome
					$("#learning-outcomes").html('');

					for(let lo of lerningautkam){
						const loContainer = $("<div>").addClass("flex gap-2 items-center");
						const loBullet = $("<img>").attr({"alt": "icon", "src": "{{ asset('img/bullet.svg') }}"}).addClass("w-3 h-3");
						const loText = $("<div>").text(`LO${lo.number}: ${lo.title}`);
						loContainer.append(loBullet).append(loText);
						$("#learning-outcomes").append(loContainer);
					}

					// Scroll to the selected slide
					swiper.slideTo(sessionNumber - 2);
				}
			});
		});
	</script>
@endsection
