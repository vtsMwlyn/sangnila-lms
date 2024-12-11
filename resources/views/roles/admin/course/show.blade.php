@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("popup")
	<x-popup class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="new-learning-outcome">
		<!-- Popup header -->
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">New Learning Outcome</div>
			<button type="button" class="popup-dismiss">
				<img src="{{ asset('img/close.svg') }}" alt="history-icon" class="w-6 h-6 hover:scale-110">
			</button>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<!-- Popup content -->
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4" id="new-learning-outcome-form">
				@csrf
				<div class="flex flex-col">
					<label for="title">Learning Outcome Title</label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Learning Outcome Title" autofocus />
				</div>

				<div class="flex flex-col mt-4">
					<label for="number">Order/Number of Learning Outcome</label>
					<x-input id="number" class="w-full mt-1" type="text" name="number" style="border-width: 3px;" value="{{ old('number') }}" placeholder="Learning Outcome Order/Number" autofocus />
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-1/6">Submit</x-button>
					{{-- <x-button class="w-full md:w-1/6">Cancel</x-button> --}}
				</div>

				<!-- Helper -->
				<input type="hidden" name="h-last-popup" class="h-last-popup">

				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-title" class="h-title">
				<input type="hidden" name="h-number" class="h-number">
			</form>
		</div>
	</x-popup>

	<x-popup class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-learning-outcome">
		<!-- Popup header -->
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">Edit Learning Outcome</div>
			<button type="button" class="popup-dismiss">
				<img src="{{ asset('img/close.svg') }}" alt="history-icon" class="w-6 h-6 hover:scale-110">
			</button>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<!-- Popup content -->
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4" id="edit-learning-outcome-form">
				@csrf
				<div class="flex flex-col">
					<label for="title">Learning Outcome Title</label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Learning Outcome Title" autofocus />
				</div>

				<div class="flex flex-col mt-4">
					<label for="number">Order/Number of Learning Outcome</label>
					<x-input id="number" class="w-full mt-1" type="text" name="number" style="border-width: 3px;" value="{{ old('number') }}" placeholder="Learning Outcome Order/Number" autofocus />
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-1/6">Submit</x-button>
					{{-- <x-button class="w-full md:w-1/6">Cancel</x-button> --}}
				</div>

				<!-- Helper -->
				<input type="hidden" name="h-last-popup" class="h-last-popup">

				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-lo" class="h-lo">

				<input type="hidden" name="h-title" class="h-title">
				<input type="hidden" name="h-number" class="h-number">
			</form>
		</div>
	</x-popup>

	<x-popup class="w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="delete-learning-outcome">
		<!-- Popup header -->
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">Delete Learning Outcome</div>
			<button type="button" class="popup-dismiss">
				<img src="{{ asset('img/close.svg') }}" alt="history-icon" class="w-6 h-6 hover:scale-110">
			</button>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<!-- Popup content -->
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4" id="delete-learning-outcome-form">
				@csrf

				<p class="text-center">Are you sure want to delete the Learning Outcome <span class="font-bold text-light-blue" id="del-lo-name"></span> from this course?</p>

				<div class="flex items-stretch gap-3 justify-center mt-20 mb-3">
					<x-button class="bg-orange-500 w-full md:w-1/5">
						{{ __('Yes') }}
					</x-button>
					<x-cancel-button class="w-full md:w-1/5">
						No
					</x-cancel-button>
				</div>
			</form>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container class="mb-10">
		<x-page-title>{{ __("Course Details") }}</x-page-title>
		{{-- <h6 class="text-sm italic text-gray-500 text-center mb-4">(Visibility: {{ $course->visibility }})</h6> --}}
		@if(session()->has("successUpdateCourseData"))
			<x-badge-success badge_text="{{ session('successUpdateCourseData') }}"></x-badge-success>
		@elseif(session()->has("successBatchAssign"))
			<x-badge-success badge_text="{{ session('successBatchAssign') }}"></x-badge-success>
		@elseif(session()->has("successAddLearningOutcome"))
			<x-badge-success badge_text="{{ session('successAddLearningOutcome') }}"></x-badge-success>
		@elseif(session()->has("successEditLearningOutcome"))
			<x-badge-success badge_text="{{ session('successEditLearningOutcome') }}"></x-badge-success>
		@elseif(session()->has("successDeleteLearningOutcome"))
			<x-badge-warning badge_text="{{ session('successDeleteLearningOutcome') }}"></x-badge-warning>
		@elseif(session()->has("successImportStudent"))
			<x-badge-success badge_text="{{ session('successImportStudent') }}"></x-badge-success>
		@elseif(session()->has("successImportExcelCurriculum"))
			<x-badge-success badge_text="{{ session('successImportExcelCurriculum') }}"></x-badge-success>
		@elseif(session()->has("successDeleteCurriculumTopic"))
			<x-badge-warning badge_text="{{ session('successDeleteCurriculumTopic') }}"></x-badge-warning>
		@endif

		<div class="flex gap-5 mt-8">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.edit', $course->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.delete', $course->id) }}"><i class="bi bi-trash3"></i> Delete</x-anchor-button>
		</div>

		<div class="overflow-x-auto mt-5">
			<x-horizontal-table>
				<tr>
					<td class="template-hheads w-1/3">Course Name</td>
					<td class="template-hbodies">{{ $course->course_name }}</td>
				</tr>
				<tr>
					<td class="template-hheads w-1/3">Course Visibility</td>
					<td class="template-hbodies">{{ $course->visibility }}</td>
				</tr>
				<tr>
					<td class="template-hheads w-1/3">Course Description</td>
					<td class="template-hbodies">{{ $course->course_description }}</td>
				</tr>
			</x-horizontal-table>
		</div>

		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<h2 class="my-4 font-extrabold text-xl text-dark-blue">Learning Outcomes</h2>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-4">
			<x-button type="button" class="newlearningoutcome-popuptrigger" data-route="{{ route('admin.course.learning-outcome.store', $course->id) }}"><i class="bi bi-plus-lg"></i> New Learning Outcome</x-button>
		</div>

		<div class="w-full overflow-x-auto my-6">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">#</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Outcome Title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($learning_outcomes as $index => $lo)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4">{{ $lo->number }}</td>
							<td class="py-2 px-4">{{ $lo->title }}</td>
							<td class="py-2 px-4">
								<div class="flex justify-center gap-2 w-full">
									<x-button type="button" class="editlearningoutcome-popuptrigger" data-route="{{ route('admin.course.learning-outcome.update', [$course->id, $lo->id]) }}" data-learning_outcome="{{ $lo->toJSON() }}">
										<i class="bi bi-pencil-square"></i>
									</x-button>
									<x-button type="button" class="deletelearningoutcome-popuptrigger" data-route="{{ route('admin.course.learning-outcome.destroy', [$course->id, $lo->id]) }}" data-del_lo_name="{{ $lo->title }}">
										<i class="bi bi-trash3"></i>
									</x-button>
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-2 px-4 text-center" colspan="5">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

	</x-section-container>

	<x-section-container>
		<div class="flex flex-col items-stretch mt-5 w-full">
			<div class="rounded-2xl py-5 px-10 text-white bg-blue-950">List of Assigned Teachers</div>
			<div class="flex flex-wrap gap-x-10 overflow-y-auto py-3 mt-3" style="max-height: 300px;">
				@forelse ($course->teachers as $teacher)
					<a href="{{ route('admin.teacher.show', $teacher->id) }}">
						<div class="text-white hover:text-yellow-500 border-4 border-white hover:border-yellow-500 bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 220px; max-width: 220px; min-height: 100px; max-height: 100px;">{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}</div>
					</a>
				@empty
					<div class="flex w-full justify-center bg-white rounded-xl p-5 font-semibold">
						<span>- No teacher assigned in this course yet -</span>
					</div>
				@endforelse
			</div>
		</div>

		<div class="flex flex-col items-stretch mt-10 w-full">
			<div class="rounded-2xl py-5 px-10 text-white bg-blue-950 flex flex-col md:flex-row gap-5 md:gap-0 items-center justify-between">
				<div class="">List of Assigned Students</div>
				<div class="flex gap-5">
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.batch-assign', $course->id) }}"><i class="bi bi-ui-checks-grid"></i> Batch Assign</x-anchor-button>
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.import-student-data', $course->id) }}"><i class="bi bi-card-checklist"></i> Import Old Student</x-anchor-button>
				</div>
			</div>
			<div class="flex flex-wrap gap-x-10 overflow-y-auto py-3 mt-3" style="max-height: 300px;">
				@forelse ($course->students as $student)
					<a href="{{ route('admin.student.show', $student->id) }}">
						<div class="text-white hover:text-yellow-500 border-4 border-white hover:border-yellow-500 bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 220px; max-width: 220px; min-height: 100px; max-height: 100px;">
							{{ $student->full_name }}
							@if($student->status == "disabled")
								<span class="text-red-500">(Disabled)</span>
							@endif
						</div>
					</a>
				@empty
					<div class="flex w-full justify-center font-semibold bg-white rounded-xl p-5">
						<span>- No student enrolled in this course yet -</span>
					</div>
				@endforelse
			</div>
		</div>

		<div class="flex flex-col w-full mt-10" id="curriculum-section">
			<div class="rounded-2xl py-5 px-10 text-white bg-blue-950 flex items-center justify-between">
				<span>Course Curriculum</span>
				<div class="flex gap-5">
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.curriculum.import-excel', $course->id) }}"><i class="bi bi-file-earmark-arrow-up"></i> Import from Excel</x-anchor-button>
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.curriculum.topic.create', $course->id) }}"><i class="bi bi-plus-lg"></i> Add New Topic</x-anchor-button>
				</div>
			</div>

			<div class="overflow-x-auto mt-3">
				<x-table>
					<x-slot name="head">
						<th class="template-heads rounded-l-xl">Topic</th>
						<th class="template-heads">Activities</th>
						<th class="template-heads">Learning Outcomes</th>
						<th class="template-heads rounded-r-xl">Action</th>
					</x-slot>
					@forelse ($course->curriculum_topics as $topic)
						<tr>
							<td class="template-bodies rounded-l-xl"><a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $topic->id]) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $topic->title }}</a></td>
							<td class="template-bodies" style="text-align: start">
								@if($topic->curriculum_activities->count())
									<ul class="list-disc">
										@foreach ($topic->curriculum_activities as $activity)
											<li class="mb-2">{{ $activity->title }}</div>
										@endforeach
									</ul>
								@else
									<span class="text-gray-500 font-semibold">- No activities yet -</span>
								@endif
							</td>
							<td class="template-bodies">
								@php
									$lolist = [];
									foreach ($topic->curriculum_activities as $activity) {
										foreach ($activity->learning_outcomes as $leaout) {
											if (!in_array($leaout->number, $lolist)) {
												$lolist[] = $leaout->number;
											}
										}
									}

									sort($lolist);
								@endphp

								@foreach($lolist as $los)
									LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif
								@endforeach
							</td>
							<td class="template-bodies rounded-r-xl w-1/4">
								<div class="w-full flex flex-col items-center justify-center gap-3">
									<x-anchor-button href="{{ route('admin.course.curriculum.topic.edit', [$course->id, $topic->id]) }}" class="bg-orange-500 w-1/2"><i class="bi bi-pencil-square"></i> Edit Topic</x-anchor-button>
									<x-anchor-button href="{{ route('admin.course.curriculum.topic.delete', [$course->id, $topic->id]) }}" class="bg-orange-500 w-1/2"><i class="bi bi-trash3"></i> Delete Topic</x-anchor-button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="3" class="text-center font-semibold rounded-xl p-5 bg-white">- No curriculum topics and activities yet -</td></tr>
					@endforelse
				</x-table>
			</div>

		</div>
	</x-section-container>

	<script>
		function initializeNewLearningOutcomePopup(route, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$("#new-learning-outcome-form").attr("action", route);

			// Display the popup
			$("#new-learning-outcome").parent().show();
		}

		function initializeEditLearningOutcomePopup(route, learning_outcome, whichpopup){
			// Fill helpers popup data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(".h-lo").val(JSON.stringify(learning_outcome));

			// Retrieve old values
			const oldTitle = '{{ old('title') }}';
			const oldNumber = '{{ old('number') }}';

			// If old values exist, fill them in
			$('input[name="title"]').val(oldTitle ?  oldTitle : learning_outcome.title);
			$('input[name="number"]').val(oldNumber ? oldNumber : learning_outcome.number);

			// Fill the other popup data
			$(".h-title").val(learning_outcome.title);
			$(".h-number").val(learning_outcome.number);
			$("#edit-learning-outcome-form").attr("action", route);

			// Display the popup
			$("#edit-learning-outcome").parent().show();
		}

		$(document).ready(() => {
			// If create button is clicked
			$('.newlearningoutcome-popuptrigger').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "add-learning-outcome";

				initializeNewLearningOutcomePopup(route, whichpopup);
			});

			// If edit button is clicked
			$('.editlearningoutcome-popuptrigger').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "edit-learning-outcome";
				const learning_outcome = $(this).data("learning_outcome");

				initializeEditLearningOutcomePopup(route, learning_outcome, whichpopup);
			});

			// If delete button is clicked
			$('.deletelearningoutcome-popuptrigger').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-learning-outcome-form").attr("action", $(this).data('route'));
				$("#del-lo-name").text($(this).data('del_lo_name'));

				// Show the popup
				$("#delete-learning-outcome").parent().show();
			});

			// Clear inputs when any popup is closed
			$(".popup-dismiss").click(function(){
				$('input[name]:not([name="_token"])').val("");
			});

			// Redisplay popup and fill with prev data (for invalidated data)
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_popup = @json(old('h-last-popup'));

				if(old_popup == "add-learning-outcome"){
					const old_route = @json(old('h-route'));

					initializeNewLearningOutcomePopup(old_route, old_popup);
				}
				else if(old_popup == "edit-learning-outcome") {
					const old_route = @json(old('h-route'));
					const old_lo = @json(old('h-lo'));

					initializeEditLearningOutcomePopup(old_route, JSON.parse(old_lo), old_popup);
				}
			@endif
		});
	</script>

@endsection
