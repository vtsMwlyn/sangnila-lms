@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<div class="rounded-3xl w-full py-5 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
		<div class="flex flex-col w-full px-8">
			<x-back-button href="{{ route('student.mycourse.index') }}"></x-back-button>
			<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
			@php
				$teacher = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher;
			@endphp
			<div class="flex gap-2 items-center">
				<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
				{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
			</div>
			<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

			@if($should_pay_soon)
				<div class="bg-yellow-400 text-orange-700 py-3 px-6 rounded-lg font-semibold my-4">
					<i class="bi bi-exclamation-square"></i>
					<span>Your progress in <span class="font-bold">{{ $course->course_name }}</span> course is reaching its maximum session. Please do the payment to extend your study in the course.</span>
				</div>
			@endif

			@if(!$max_session_reached)
				<!-- Horizontal Scroller -->
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
						@forelse ($activityProgresses as $ap)
							@php
								$is_unlocked = $ap["progress"]->status === 'unlocked';
							@endphp
							<div class="swiper-slide relative">
								<button type="button" id="{{ $loop->iteration }}" data-route="{{ route('student.mycourse.preview', $ap['activity']->id) }}"
									class="flex items-center justify-center session-buttons px-2 py-2.5 mb-8 mt-4 rounded-xl text-base font-extrabold
									@if($is_unlocked) unlocked-session @else locked-session @endif @if($loop->iteration == 1) selected-session @endif"
									style="border-width: 3px; min-width: 110px;">
									Session {{ $loop->iteration }}
								</button>
								<div class="absolute top-6 right-3 rounded-full h-2.5 w-2.5 @if(!$is_unlocked || $ap['progress']->already_opened == 'yes' || $loop->iteration == 1) hidden @endif" style="background: linear-gradient(180deg, #1EB8CD 0%, #BEE2DB 100%);"></div>
							</div>
						@empty

						@endforelse
					</div>
				</div>

				<script>
					document.addEventListener('DOMContentLoaded', function () {
						const matprog = @json($activityProgresses);

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
							const index = parseInt($(this).attr("id")) - 1;

							if (matprog[index].progress.status == "unlocked") {
								// Remove selected-session class from all unlocked buttons
								$(".unlocked-session").each(function () {
									$(this).removeClass("selected-session");
								});

								// Add selected-session class to the clicked button
								$(this).addClass("selected-session");

								// Update the content with topic and activity info
								$("#topic").text(matprog[index].topic.title);
								$("#activity").text(matprog[index].activity.title);
								$("#preview-link").attr("href", $(this).data('route'));
								$("#num").text(index + 1);

								// Update learning outcome
								$("#learning-outcomes").html('');

								for(let lo of JSON.parse(matprog[index].learning_outcomes)){
									const loContainer = $("<div>").addClass("flex gap-2 items-center");
									const loBullet = $("<img>").attr({"alt": "icon", "src": "{{ asset('img/bullet.svg') }}"}).addClass("w-3 h-3");
									const loText = $("<div>").text(`LO${lo.number}: ${lo.title}`);
									loContainer.append(loBullet).append(loText);
									$("#learning-outcomes").append(loContainer);
								}

								// Scroll to the selected slide
								swiper.slideTo(index);
							}
						});
					});
				</script>


				<div class="mb-6">
					<h1 class="text-dark-blue font-bold text-xl"><span id="num">1</span>. <span id="topic">{{ count($activityProgresses) > 0 ? $activityProgresses[0]["topic"]->title : 'N/A' }}</span></h1>
					<div class="flex flex-col gap-1 mt-2" id="learning-outcomes">
						@if(count($activityProgresses) > 0)
							@foreach(json_decode($activityProgresses[0]["learning_outcomes"]) as $lo)
								<div class="flex gap-2 items-center">
									<img src="{{ asset('img/bullet.svg') }}" alt="icon" class="w-3 h-3">
									<div>LO{{ $lo->number }}: {{ $lo->title }}</div>
								</div>
							@endforeach
						@else
							N/A
						@endif
					</div>
				</div>
			@endif
		</div>

		@if(!$max_session_reached)
			<div class="w-full bg-white px-8 py-3">
				<div class="w-full flex items-center">
					<div class="flex w-1/2 items-start">
						<h1 class="text-dark-blue font-bold text-xl">Course Activity</h1>
					</div>
					<div class="flex w-1/2 items-start">
						<h1 class="text-dark-blue font-bold text-xl">Action</h1>
					</div>
				</div>
				<div class="w-full flex items-center mt-1">
					<div class="flex w-1/2 items-start">
						<h2 id="activity">{{ count($activityProgresses) > 0 ? $activityProgresses[0]["activity"]->title : 'N/A' }}</h2>
					</div>
					<div class="flex w-1/2 items-start ">
						<div class="flex gap-1">
							@if(count($activityProgresses) > 0)
								<a href="{{ route('student.mycourse.preview', $activityProgresses[0]["activity"]->id) }}" id="preview-link"><img src="{{ asset('img/view.svg') }}" alt="icon" class="w-8 h-8 hover:scale-110"></a>
							@else
								<a href="#"></a><img src="{{ asset('img/download.svg') }}" alt="icon" class="w-8 h-8">
							@endif
						</div>
					</div>
				</div>
			</div>

			<div class="flex flex-col gap-8 mt-8 w-full px-8">
				<div class="flex flex-col">
					<h3 class="text-xs">Start time</h3>
					<h2>N/A</h2>
				</div>
				<div class="flex flex-col">
					<h3 class="text-xs">End time</h3>
					<h2>N/A</h2>
				</div>
				<div class="flex flex-col">
					<h3 class="text-xs">Delivery Mode</h3>
					<h2>Onsite/Online</h2>
				</div>
			</div>
		@else
			<div class="w-full py-8 flex items-center justify-center">
				<img src="{{ asset('img/access-locked.png') }}" class="w-80 h-80" alt="locked">
			</div>
		@endif
	</div>
@endsection
