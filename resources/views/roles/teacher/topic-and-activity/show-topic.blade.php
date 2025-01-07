@extends("layouts.main-teacher")

@section("title")
	<h1>Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $topic->course->id) }}" class="font-bold text-yellow-500">{{ $topic->course->course_name }}</a>
	> <span>{{ $topic->title }}</span>
@endsection

@section("popup")
	<!-- Delete topic -->
	<x-confirmation method="delete" popup_title="Delete Topic" id="delete-topic">
		Are you sure want to <span class="font-bold text-red">delete</span> the Topic <span class="font-bold text-light-blue" id="del-t-name"></span> from this course?
	</x-confirmation>

	<!-- Delete activity -->
	<x-confirmation method="delete" popup_title="Delete Activity" id="delete-activity">
		Are you sure want to <span class="font-bold text-red">delete</span> the Activity <span class="font-bold text-light-blue" id="del-a-name"></span> from this topic?
	</x-confirmation>

	<!-- Edit topic -->
	<x-popup popup_title="Edit Topic" class="w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-topic">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf
				@method('patch')
				<!-- Topic Title -->
				<div class="flex flex-col">
					<label for="title">Topic Title</label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Topic title" autofocus />
				</div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/5">
						{{ __('Submit') }}
					</x-button>
				</div>

				<!-- Helper -->
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-topic" class="h-topic">
			</form>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.mycourse.show', $topic->course->id) }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $topic->course->course_name }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Topic and Activities Details</h1>
		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		@if(session()->has("successUpdateTopic"))
			<x-badge-success badge_text="{{ session('successUpdateTopic') }}"></x-badge-success>
		@elseif(session()->has("successAddTopic"))
			<x-badge-success badge_text="{{ session('successAddTopic') }}"></x-badge-success>
		@elseif(session()->has("successEditTopic"))
			<x-badge-success badge_text="{{ session('successEditTopic') }}"></x-badge-success>
		@elseif(session()->has('successUploadActivity'))
			<x-badge-success badge_text="{{ session('successUploadActivity') }}"></x-badge-success>
		@elseif(session()->has('successEditActivity'))
			<x-badge-success badge_text="{{ session('successEditActivity') }}"></x-badge-success>
		@elseif(session()->has("successDeleteActivity"))
			<x-badge-warning badge_text="{{ session('successDeleteActivity') }}"></x-badge-warning>
		@endif

		<h2 class="mt-4 mb-2 font-extrabold text-xl text-dark-blue">Topic</h2>

		<div class="w-full flex flex-col gap-4">
			<div class="flex flex-col grow">
				<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $topic->title }}</div>
			</div>

			<div class="flex gap-2 w-full justify-end">
				<x-button type="button" id="edit-topic-btn" data-route="{{ route('teacher.mycourse.topic.update', [$topic->course->id, $topic->id]) }}" data-topic="{{ $topic }}"><i class="bi bi-pencil-square"></i> Edit</x-button>
				<x-button type="button" id="delete-topic-btn" data-route="{{ route('teacher.mycourse.topic.destroy', [$topic->course->id, $topic->id]) }}" data-del_t_name="{{ $topic->title }}"><i class="bi bi-trash3"></i> Delete</x-button>
			</div>
		</div>


		<h2 class="my-4 font-extrabold text-xl text-dark-blue">List of Activities</h2>

		<div class="flex">
			<x-anchor-button href="{{ route('teacher.mycourse.activity.upload', $topic->id) }}"><i class="bi bi-plus-lg"></i> Add New Activity</x-anchor-button>
		</div>

		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Description</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Link</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($topic->activities()->orderBy('session', 'asc')->get() as $activity)
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
							<td class="py-2 px-4">
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
										href="{{ route('teacher.mycourse.activity.edit', $activity->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
									<x-button type="button" class="delete-activity-btn" data-del_a_name="{{ $activity->title }}"
										data-route="{{ route('teacher.mycourse.activity.destroy', $activity->id) }}">
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
			// Edit topic
			$('#edit-topic-btn').on('click', function(){
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "edit-topic";
				const topic = $(this).data("topic");

				initializeEditTopicPopup(route, topic, whichpopup);
			});

			// Delete curriculum topic
			$('#delete-topic-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-topic").find('form').attr("action", $(this).data('route'));
				$("#del-t-name").text($(this).data('del_t_name'));

				// Show the popup
				$("#delete-topic").parent().show();
			});

			// Delete curriculum activity
			$('.delete-activity-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-activity").find('form').attr("action", $(this).data('route'));
				$("#del-a-name").text($(this).data('del_a_name'));

				// Show the popup
				$("#delete-activity").parent().show();
			});

			// Redisplay popup and fill with prev data (for invalidated data)
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_popup = @json(old('h-last-popup'));

				if(old_popup == "edit-topic"){
					const old_route = @json(old('h-route'));
					const old_topic = @json(old('h-topic'));

					initializeEditTopicPopup(old_route, JSON.parse(old_topic), old_popup);
				}
			@endif

			$(".show-more-button").click(function(){
				$(this).closest("div").next().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});

			$(".show-less-button").click(function(){
				$(this).closest("div").prev().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});
		});
	</script>
@endsection
