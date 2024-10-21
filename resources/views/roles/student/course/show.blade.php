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
			<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8" alt="back"></button>
			<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
			@php
				$teacher = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher;
			@endphp
			<div class="flex gap-2 items-center">
				<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
				{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
			</div>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

			@if($should_pay_soon)
				<div class="bg-yellow-400 text-orange-700 py-3 px-6 rounded-lg font-semibold mb-8">
					<i class="bi bi-exclamation-square"></i>
					<span>Your progress in <span class="font-bold">{{ $course->course_name }}</span> course is reaching its maximum session. Please do the payment to extend your study in the course.</span>
				</div>
			@endif

			<!-- Horizontal scroller -->
			<style>
				/* Container for horizontal scroll */
				#session-scroller {
					display: flex;
					overflow-x: auto; /* Enable horizontal scroll */
					scroll-behavior: smooth; /* Smooth scrolling */
					-webkit-overflow-scrolling: touch; /* Enables momentum scroll on iOS */
				}

				/* Hide scrollbar */
				#session-scroller::-webkit-scrollbar {
					display: none; /* Hide scrollbar for Chrome, Safari, Opera */
				}

				#session-scroller {
					-ms-overflow-style: none;  /* Hide scrollbar for Internet Explorer and Edge */
					scrollbar-width: none;     /* Hide scrollbar for Firefox */
				}

				button.unlocked-session {
					border-color: #9CA3AF;
					background: white;
				}

				button.locked-session {
					background: rgba(0, 0, 0, 0.5);
					border-color: #9CA3AF;
				}
			</style>

			<div class="flex gap-3 overflow-x-auto cursor-pointer pt-2 pb-4" id="session-scroller">
				@forelse ($materialProgresses as $mp)
					@php
						$is_unlocked = $mp["progress"]->status === 'unlocked';
					@endphp
					<button type="button" id="{{ $loop->iteration }}" class="flex items-center justify-center session-buttons px-2 py-2.5 rounded-xl text-base font-extrabold
						@if($is_unlocked) unlocked-session text-dark-blue
						@else text-gray-700 locked-session
						@endif" style="border-width: 3px; min-width: 110px;">Session {{ $loop->iteration }}</button>
				@empty

				@endforelse
			</div>

			<div class="mt-4 mb-6">
				<h1 class="text-dark-blue font-bold text-xl"><span id="num">1</span>. <span id="topic">{{ $materialProgresses[0]["topic"]->title }}</span></h1>
				<div class="flex flex-col gap-1 mt-2">
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/bullet.svg') }}" alt="icon" class="w-3 h-3">
						<div>N/A</div>
					</div>
				</div>
			</div>
		</div>

		<div class="w-full bg-white px-8 py-3">
			<div class="w-full flex items-center">
				<div class="flex w-1/2 items-start">
					<h1 class="text-dark-blue font-bold text-xl">Course Material</h1>
				</div>
				<div class="flex w-1/2 items-start">
					<h1 class="text-dark-blue font-bold text-xl">Action</h1>
				</div>
			</div>
			<div class="w-full flex items-center mt-1">
				<div class="flex w-1/2 items-start">
					<h2 id="material">{{ $materialProgresses[0]["material"]->title }}</h2>
				</div>
				<div class="flex w-1/2 items-start ">
					<div class="flex gap-1">
						<a href="{{ route('student.mycourse.preview', $materialProgresses[0]["material"]->id) }}" id="preview-link"><img src="{{ asset('img/view.svg') }}" alt="icon" class="w-8 h-8"></a>
						{{-- <a href="#"></a><img src="{{ asset('img/download.svg') }}" alt="icon" class="w-8 h-8"> --}}
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

		{{-- <p class="text-blue-950 font-semibold text-center mb-8">{{ $course->course_description }}</p> --}}

		{{-- <h2 class="text-xl text-white font-bold">Course Materials:</h2>

		<div class="mb-6 overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Course Topic</th>
					<th class="template-heads">Material Name</th>
					<th class="template-heads">Status</th>
					<th class="template-heads rounded-r-xl">Actions</th>
				</x-slot>

				@forelse ($materialProgresses as $progress)
					<tr>
						<td class="template-bodies rounded-l-xl" style="@if ($progress->status === 'locked') color: rgb(156 163 175); @endif">
							{{ $progress->material->topic->title }}
						</td>
						<td class="template-bodies">
							@if ($progress->status === 'unlocked' && !$max_session_reached)
								<a
									href="{{ route("student.mycourse.preview", $progress->material->id) }}" class="text-white hover:text-green-900 hover:underline font-bold">
									{{ $progress->material->title }}
								</a>
							@else
								<span class="text-gray-400 font-bold">
									{{ $progress->material->title }}
								</span>
							@endif
						</td>
						<td class="template-bodies">
							<span class="@if ($progress->status === 'unlocked') font-bold text-green-700 @else text-gray-400 @endif">
								{{ $progress->status }}
							</span>
						</td>
						<td class="template-bodies rounded-r-xl">
							@if ($progress->status === 'unlocked' && !$max_session_reached)
								<x-anchor-button class="bg-orange-500" href="{{ route('student.mycourse.preview', $progress->material->id) }}">View</x-anchor-button>
							@else
								<x-button class="bg-slate-800" type="button">View</x-button>
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="p-5 text-center rounded-xl bg-white font-semibold">- The teacher haven't uploaded any topics and materials yet -</td>
					</tr>
				@endforelse
			</x-table>
		</div> --}}
	</div>

	<script>
		$(document).ready(() => {
			const scrollContainer = document.querySelector('#session-scroller');

			let isDown = false; // Indicates if the mouse button is pressed
			let startX; // Starting X position of the mouse
			let scrollLeft; // Starting scroll position of the container

			// Mouse down event to initiate dragging
			scrollContainer.addEventListener('mousedown', (e) => {
				isDown = true; // Mouse button is pressed
				scrollContainer.style.userSelect = 'none'; // Prevent text selection
				startX = e.pageX; // Get the initial mouse position
				scrollLeft = scrollContainer.scrollLeft; // Get the current scroll position
			});

			// Mouse up event to stop dragging
			scrollContainer.addEventListener('mouseup', () => {
				isDown = false; // Mouse button is released
				scrollContainer.style.userSelect = ''; // Restore text selection
			});

			// Mouse leave event to stop dragging if the mouse leaves the container
			scrollContainer.addEventListener('mouseleave', () => {
				isDown = false; // Mouse has left the container
				scrollContainer.style.userSelect = ''; // Restore text selection
			});

			// Mouse move event to scroll the container
			scrollContainer.addEventListener('mousemove', (e) => {
				if (!isDown) return; // Only proceed if the mouse button is down

				e.preventDefault(); // Prevent default action to avoid text selection

				const x = e.pageX; // Current mouse position
				const walk = (x - startX); // Calculate the distance moved

				// Scroll the container based on the mouse movement
				scrollContainer.scrollLeft = scrollLeft - walk; // Update the scroll position
			});

			const matprog = @json($materialProgresses);

			$(".session-buttons").click(function(){
				const index = parseInt($(this).attr("id")) - 1;
				if(matprog[index].progress.status == "unlocked"){
					$("#topic").text(matprog[index].topic.title);
					$("#material").text(matprog[index].material.title);
					$("#preview-link").attr("href", `../../student/my-course/${matprog[index].material.id}/preview`);
					$("#num").text(index + 1);
				}

			});
		});

	</script>
@endsection
