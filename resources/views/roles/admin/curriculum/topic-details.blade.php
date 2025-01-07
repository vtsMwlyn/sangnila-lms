@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <span>{{ $curriculum_topic->title }}</span>
@endsection

@section('popup')
	<!-- Edit topic -->
	<x-popup popup_title="Edit Curriculum Topic" class="w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-curriculum-topic">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf
				<!-- Curriculum Topic Title -->
				<div class="flex flex-col">
					<label for="topic_title">Curriculum Topic Title</label>
					<x-input id="topic_title" class="w-full mt-1" type="text" name="topic_title" style="border-width: 3px;" value="{{ old('topic_title') }}" placeholder="Curriculum topic title" autofocus />
				</div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/5">
						{{ __('Submit') }}
					</x-button>
				</div>

				<!-- Helper -->
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">

				<input type="hidden" name="h-curriculum_title" class="h-curriculum_title">
			</form>
		</div>
	</x-popup>

	<!-- Delete curriculum topic -->
	<x-confirmation popup_title="Delete Curriculum Topic" id="delete-curriculum-topic">
		Are you sure want to <span class="font-bold text-red">delete</span> the Curriculum Topic <span class="font-bold text-light-blue" id="del-ct-name"></span> from this course?
	</x-confirmation>

	<!-- Delete curriculum activity -->
	<x-confirmation popup_title="Delete Curriculum Activity" id="delete-curriculum-activity">
		Are you sure want to <span class="font-bold text-red">delete</span> the Curriculum Activity <span class="font-bold text-light-blue" id="del-ca-name"></span> from this topic?
	</x-confirmation>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('admin.course.show', $curriculum_topic->course->id) }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">Syllabus</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Topic and Activities Details</h1>
		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		@if(session()->has("successEditCurriculumTopic"))
			<x-badge-success badge_text="{{ session('successEditCurriculumTopic') }}"></x-badge-success>
		@elseif(session()->has("successAddCurriculumTopic"))
			<x-badge-success badge_text="{{ session('successAddCurriculumTopic') }}"></x-badge-success>
		@elseif(session()->has('successAddCurriculumActivity'))
			<x-badge-success badge_text="{{ session('successAddCurriculumActivity') }}"></x-badge-success>
		@elseif(session()->has('successEditCurriculumActivity'))
			<x-badge-success badge_text="{{ session('successEditCurriculumActivity') }}"></x-badge-success>
		@elseif(session()->has("successDeleteCurriculumActivity"))
			<x-badge-warning badge_text="{{ session('successDeleteCurriculumActivity') }}"></x-badge-warning>
		@endif

		<h2 class="mt-4 mb-2 font-extrabold text-xl text-dark-blue">Topic</h2>

		<div class="w-full flex flex-col gap-4">
			<div class="flex flex-col grow">
				<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $curriculum_topic->title }}</div>
			</div>

			<div class="flex gap-2 w-full justify-end">
				<x-button type="button" data-curriculum_topic="{{ $curriculum_topic }}" data-route="{{ route('admin.course.curriculum.topic.update', [$curriculum_topic->course->id, $curriculum_topic->id]) }}" class=" edit-curriculum-topic-btn"><i class="bi bi-pencil-square"></i> Edit</x-button>
				<x-button type="button"  id="delete-curriculum-topic-btn" data-del_ct_name="{{ $curriculum_topic->title }}" data-route="{{ route('admin.course.curriculum.topic.destroy', [$curriculum_topic->course->id, $curriculum_topic->id]) }}"><i class="bi bi-trash3"></i> Delete</x-button>
			</div>
		</div>

		<h2 class="my-4 font-extrabold text-xl text-dark-blue">List of Activities</h2>

		<div class="flex">
			<x-anchor-button  href="{{ route('admin.course.curriculum.activity.store', [$curriculum_topic->course->id, $curriculum_topic->id]) }}"><i class="bi bi-plus-lg"></i> Add New Activity</x-anchor-button>
		</div>

		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Description</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Link</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($curriculum_topic->curriculum_activities()->orderBy('session')->get() as $activity)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-2 px-4 text-center">{{ $activity->session }}</td>
							<td class="py-2 px-4 w-1/4">{{ $activity->title }}</td>
							<td class="py-2 px-4 w-1/2">
								@if(strlen($activity->desc) > 120)
									<div class="">{{ substr($activity->desc, 0, 120) }}... <button type="button" class="show-more-button text-blue font-semibold text-xs">[Show More]</button></div>
									<div class="hidden">{{ $activity->desc }} <button type="button" class="show-less-button text-blue font-semibold text-xs">[Show Less]</button></div>
								@else
									{{ $activity->desc }}
								@endif
							</td>
							<td class="py-2 px-4 text-center">
								@forelse ($activity->learning_outcomes as $leaout)
									LO{{ $leaout->number }}@if($activity->learning_outcomes->count() > 1 && $loop->index != $activity->learning_outcomes->count() - 1), @endif
								@empty
									N/A
								@endforelse
							</td>
							<td class="py-2 px-4" style="max-width: 18vw; word-wrap: break-word;">
								@if($activity->link)
									<a href="{{ $activity->link }}" target="blank" class="font-bold text-blue-600 hover:underline">{{ $activity->link }}</a>
								@else
									N/A
								@endif
							</td>

							<td class="py-2 px-4">
								<div class="flex gap-1">
									<x-anchor-button
										href="{{ route('admin.course.curriculum.activity.edit', [$curriculum_topic->course->id, $curriculum_topic->id, $activity->id]) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
									<x-button type="button"
										data-route="{{ route('admin.course.curriculum.activity.destroy', [$curriculum_topic->course->id, $curriculum_topic->id, $activity->id]) }}" data-del_ca_name="{{ $activity->title }}" class="delete-curriculum-activity-btn">
										<i class="bi bi-trash3"></i>
									</x-button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No activities added yet to this course topic -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>

	<script>
		function initializeEditCurriculumTopicPopup(route, curriculum_topic, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);

			// Retrieve old values
			const oldCurriculumTitle = '{{ old('curriculum_title') }}';

			// If old values exist, fill them in
			$('input[name="curriculum_title"]').val(oldCurriculumTitle ?  oldCurriculumTitle : curriculum_topic.title);

			// Fill the other popup data
			$(".h-curriculum_title").val(curriculum_topic.title);
			$(`#${whichpopup}`).find('form').attr("action", route);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		$(document).ready(() => {
			$(".show-more-button").click(function(){
				$(this).closest("div").next().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});

			$(".show-less-button").click(function(){
				$(this).closest("div").prev().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});

			// Edit curriculum topic
			$('.edit-curriculum-topic-btn').on('click', function(){
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "edit-curriculum-topic";
				const curriculum_topic = $(this).data("curriculum_topic");

				initializeEditCurriculumTopicPopup(route, curriculum_topic, whichpopup);
			});

			// Delete curriculum topic
			$('#delete-curriculum-topic-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-curriculum-topic").find('form').attr("action", $(this).data('route'));
				$("#del-ct-name").text($(this).data('del_ct_name'));

				// Show the popup
				$("#delete-curriculum-topic").parent().show();
			});

			// Delete curriculum activity
			$('.delete-curriculum-activity-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-curriculum-activity").find('form').attr("action", $(this).data('route'));
				$("#del-ca-name").text($(this).data('del_ca_name'));

				// Show the popup
				$("#delete-curriculum-activity").parent().show();
			});

			// Redisplay popup and fill with prev data (for invalidated data)
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_popup = @json(old('h-last-popup'));

				if(old_popup == "edit-curriculum-topic"){
					const old_route = @json(old('h-route'));

					initializeEditCurriculumTopicPopup(old_route, old_popup);
				}
			@endif
		});
	</script>
@endsection
