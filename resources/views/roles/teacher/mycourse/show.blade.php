@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Courses</h1>
@endsection


@section("popup")
	{{-- New topic --}}
	<x-popup popup_title="New Topic" class="w-11/12 xl:w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="new-topic">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf
				{{-- Topic Title --}}
				<div class="flex flex-col">
					<label for="title">Topic Title<span class="text-red">*</span></label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Topic title" autofocus />
				</div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class="w-full md:w-1/4">
						{{ __('Submit') }}
					</x-button>
				</div>

				{{-- Helper --}}
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
			</form>
		</div>
	</x-popup>

	{{-- Edit topic --}}
	<x-popup popup_title="Edit Topic" class="w-11/12 xl:w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-topic">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf
				@method('patch')
				{{-- Topic Title --}}
				<div class="flex flex-col">
					<label for="title">Topic Title<span class="text-red">*</span></label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Topic title" autofocus />
				</div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/4">
						{{ __('Submit') }}
					</x-button>
				</div>

				{{-- Helper --}}
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-topic" class="h-topic">
			</form>
		</div>
	</x-popup>

	{{-- Delete topic --}}
	<x-confirmation method="delete" popup_title="Delete Topic" id="delete-topic">
		Are you sure want to <span class="font-bold text-red">delete</span> the Topic <span class="font-bold text-light-blue" id="del-t-name"></span> from this course?
	</x-confirmation>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.mycourse.index') }}"></x-back-button>
		<x-page-title>{{ $course->course_name }} - {{ ucwords($course->level) }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<div class="flex w-full flex-wrap">
			<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id, 'content' => 'general information']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'general information' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				General Information
			</a>

			<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id ,'content' => 'topics and activities']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'topics and activities') border-bottom: 4px solid #1db9cf; @endif">
				Topics & Activities
			</a>

			<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id ,'content' => 'trial class']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'trial class') border-bottom: 4px solid #1db9cf; @endif">
				Trial Class
			</a>
		</div>

		@if(request('content') == 'general information' || !request('content'))
			<p class="font-bold my-4">Description:</p>
			<p class="text-blue-950 font-semibold">{{ $course->course_description }}</p>

			<p class="font-bold mt-6">Learning Outcomes:</p>
			<div class="flex flex-col gap-1 mt-2">
				@forelse($learning_outcomes as $lo)
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/bullet.svg') }}" alt="icon" class="w-3 h-3">
						<div>LO{{ $lo->number }}: {{ $lo->title }}</div>
					</div>
				@empty
					N/A
				@endforelse
			</div>

			<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Student List</h2>
			<div class="w-full bg-slate-400 " style="height: 2px;"></div>

			<div class="mt-4 xl:mt-8 flex flex-wrap">
				@forelse ($course_students as $index => $cs)
					<div class="flex flex-row xl:flex-col gap-2 xl:gap-0 items-center w-1/2 xl:w-1/6 mb-6">
						@if($cs->student->details->profpic)
							<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-10 h-10 md:w-14 md:h-14 xl:w-28 xl:h-28 mt-0 mb-0 xl:mt-2 xl:mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
						@else
							<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-10 h-10 md:w-14 md:h-14 xl:w-28 xl:h-28 mt-0 mb-0 xl:mt-2 xl:mb-4" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
						@endif
						<h1 class="text-sm xl:text-lg font-bold text-start xl:text-center">{{-- explode(" ", $cs->student->full_name)[0] --}}{{ $cs->student->full_name }}</h1>
					</div>
				@empty
				@endforelse
			</div>
		@endif

		@if(request('content') == 'topics and activities')
			<div class="flex flex-col-reverse xl:flex-row gap-8 xl:gap-0 justify-between items-stretch w-full mt-6">
				<div class="relative">
					<x-button type="button" data-route="{{ route('teacher.mycourse.topic.store', $course->id) }}" id="new-topic-btn"><i class="bi bi-plus-lg"></i> Add New Topic</x-button>
					@if($topics->count() == 0)
						<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
					@endif
				</div>

				<div class="flex gap-2 xl:gap-5">
					<x-anchor-button
						href="{{ route('teacher.mycourse.import-excel-topicandactivities', $course->id) }}">
						<i class="bi bi-file-earmark-arrow-up"></i> Import from Excel
					</x-anchor-button>

					@if($has_curriculum > 0)
						<div class="relative flex flex-col items-end dropdown-container">
							<x-button  type="button" class="dropdown-toggler">
								<i class="bi bi-arrow-repeat"></i> Generate from Syllabus
							</x-button>
							<div class="absolute z-10 overflow-hidden bg-white top-12 w-80 rounded-3xl text-sm font-semibold flex flex-col py-2 dropdown-menu" style="display: none; ">
								<a href="{{ route('teacher.mycourse.pick-course', $course->id) }}" class="hover:bg-slate-300">
									<div class="w-full px-5 py-1 text-black flex items-center gap-1"><i class="bi bi-check2-square text-slate-400"></i> Pick from Syllabus</div>
								</a>
								<form method="POST" action="{{ route('teacher.mycourse.synchronize', $course->id) }}" class="hover:bg-slate-300 grow flex items-center gap-2">
									@csrf
									<button class="w-full px-5 py-1 text-black flex items-center gap-1" onclick="return confirm('Synchronizing with topics and activity in syllabus will erase all of your posted topics and activities. Are your sure want to proceed?');">
										<i class="bi bi-arrow-repeat text-slate-400"></i> Sync with Syllabus
									</button>
								</form>
							</div>
						</div>
					@endif
				</div>
			</div>

			<div class="w-full overflow-x-auto hidden xl:block mt-4">
				<table class="w-full">
					<thead>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activities</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody >
						@php
							$iterasus = 1;
						@endphp

						@forelse ($topics as $topic)
							@if($topic->activities->count())
								<tr class="@if($iterasus % 2 == 1) bg-white @endif">
									<td class="py-3 px-4 text-center">
										@php
											if($topic->activities->count()){
												echo $topic->activities->min('session') . '-' . $topic->activities->max('session');
											} else {
												echo 'N/A';
											}
										@endphp
									</td>

									<td class="py-3 px-4">{{ $topic->title }}</td>

									<td class="py-3 px-4">
										<ul class="h-full w-full flex flex-col list-disc list-inside">
											@foreach ($topic->activities as $activity)
												<li>{{ $activity->title }}</li>
											@endforeach
										</ul>
									</td>

									<td class="py-3 px-4">
										@php
											$lolist = [];
											foreach ($topic->activities as $activity) {
												foreach ($activity->learning_outcomes as $leaout) {
													if (!in_array($leaout->number, $lolist)) {
														$lolist[] = $leaout->number;
													}
												}
											}

											sort($lolist);
										@endphp

										@forelse($lolist as $los)
											LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif
										@empty
											N/A
										@endforelse
									</td>

									<td class="py-3 px-4">
										<div class="flex w-full items-center gap-1">
											<a href="{{ route('teacher.mycourse.topic.show', [$course->id, $topic->id]) }}" title="View list of activities in this topic">
												<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</a>
											<button type="button" data-topic="{{ $topic }}" data-route="{{ route('teacher.mycourse.topic.update', [$course->id, $topic->id]) }}" class="edit-topic-btn" title="Edit this topic">
												<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</button>
											<button type="button" data-del_t_name="{{ $topic->title }}" data-route="{{ route('teacher.mycourse.topic.destroy', [$course->id, $topic->id]) }}" class="delete-topic-btn" title="Delete this topic">
												<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</button>
										</div>
									</td>
								</tr>

								@php
									$iterasus++;
								@endphp
							@else
								<tr class="@if($iterasus % 2 == 1) bg-white @endif">
									<td class="py-3 px-4">N/A</td>
									<td class="py-3 px-4">{{ $topic->title }}</td>
									<td class="py-3 px-4">- No activities added yet to this topic -</td>
									<td class="py-3 px-4">N/A</td>
									<td class="py-3 px-4">
										<div class="flex w-full items-center gap-2">
											<div class="relative">
												<x-anchor-button
													href="{{ route('teacher.mycourse.topic.show', [$course->id, $topic->id]) }}">
													<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110" title="View list of activities in this topic">
												</x-anchor-button>
												<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
											</div>
											<x-button type="button" data-topic="{{ $topic }}" data-route="{{ route('teacher.mycourse.topic.update', [$course->id, $topic->id]) }}" class="edit-topic-btn" title="Edit this topic">
												<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</x-button>
											<x-button type="button" data-del_t_name="{{ $topic->title }}" data-route="{{ route('teacher.mycourse.topic.destroy', [$course->id, $topic->id]) }}" class="delete-topic-btn" title="Delete this topic">
												<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</x-button>
										</div>
									</td>
								</tr>

								@php
									$iterasus++;
								@endphp
							@endif
						@empty
							<tr>
								<td colspan="5" class="text-center p-5 bg-white w-full font-semibold">- No topics and activities added yet to this course -</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			{{-- For smaller screen --}}
			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
				@forelse ($topics as $topic)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
						<button class="flex flex-col items-start dropdown-toggler w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<strong class="text-base text-start">{{ $topic->title }}</strong>
								<i class="bi bi-chevron-down"></i>
							</div>
						</button>
						<div class="flex flex-col w-full dropdown-menu" style="display: none;">
							<ul class="list-disc list-inside">
								@foreach ($topic->activities as $activity)
									<li>{{ $activity->title }}</li>
								@endforeach
							</ul>

							<div class="mt-4">
								Learning Outcomes: 
								@forelse($lolist as $los)
									LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif
								@empty
									N/A
								@endforelse
							</div>

							<strong class="mt-5">Actions</strong>
							<div class="flex gap-3 items-start my-3">
								<a href="{{ route('teacher.mycourse.topic.show', [$course->id, $topic->id]) }}">
									<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
								</a>
								<button type="button" data-topic="{{ $topic }}" data-route="{{ route('teacher.mycourse.topic.update', [$course->id, $topic->id]) }}" class="edit-topic-btn">
									<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
								</button>
								<button type="button" data-del_t_name="{{ $topic->title }}" data-route="{{ route('teacher.mycourse.topic.destroy', [$course->id, $topic->id]) }}" class="delete-topic-btn">
									<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
								</button>
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		@if(request('content') == 'trial class')

		@endif
	</x-section-container>

	<script>
		function initializeNewTopicPopup(route, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(`#${whichpopup}`).find('form').attr("action", route);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		function initializeEditTopicPopup(route, topic, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(".h-topic").val(JSON.stringify(topic));

			// Retrieve old values
			const oldTitle = '{{ old('title') }}';

			// If old values exist, fill them in
			$('input[name="title"]').val(oldTitle ?  oldTitle : topic.title);

			$(`#${whichpopup}`).find('form').attr("action", route);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		$(document).ready(() => {
			// Create new topic
			$('#new-topic-btn').on('click', function(){
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "new-topic";

				initializeNewTopicPopup(route, whichpopup);
			});

			// Edit topic
			$('.edit-topic-btn').on('click', function(){
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "edit-topic";
				const topic = $(this).data("topic");

				initializeEditTopicPopup(route, topic, whichpopup);
			});

			// Delete topic
			$('.delete-topic-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-topic").find('form').attr("action", $(this).data('route'));
				$("#del-t-name").text($(this).data('del_t_name'));

				// Show the popup
				$("#delete-topic").parent().show();
			});

			// Redisplay popup and fill with prev data (for invalidated data)
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_popup = @json(old('h-last-popup'));

				if(old_popup == "new-topic"){
					const old_route = @json(old('h-route'));

					initializeNewTopicPopup(old_route, old_popup);
				}
				else if(old_popup == "edit-topic"){
					const old_route = @json(old('h-route'));
					const old_topic = @json(old('h-topic'));

					initializeEditTopicPopup(old_route, JSON.parse(old_topic), old_popup);
				}
			@endif
		});
	</script>
@endsection
