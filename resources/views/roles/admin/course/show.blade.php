@extends("layouts.main-admin")

@section("title")
	<h1>Course Details</h1>
@endsection

@section("popup")
	{{-- Delete course --}}
	<x-confirmation method="delete" popup_title="Delete Course" id="delete-course-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> the Course <span class="font-bold text-light-blue" id="del-course-name"></span> from Sangnila LMS? <strong>This action will erase all data related to the course and can't be undone!</strong>
	</x-confirmation>

	{{-- New LO --}}
	<x-popup popup_title="New Learning Outcome" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="new-learning-outcome">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4" id="new-learning-outcome-form">
				@csrf
				<div class="flex flex-col">
					<label for="title">Learning Outcome Title<span class="text-red">*</span></label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Learning Outcome Title" autofocus />
				</div>

				<div class="flex flex-col mt-4">
					<label for="number">Order/Number of Learning Outcome<span class="text-red">*</span></label>
					<x-input id="number" class="w-full mt-1" type="number" name="number" style="border-width: 3px;" value="{{ old('number') }}" placeholder="Learning Outcome Order/Number" autofocus />
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-40 xl:w-1/6">Submit</x-button>
					{{-- <x-button class="w-full md:w-40 xl:w-1/6">Cancel</x-button> --}}
				</div>

				{{-- Helper --}}
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
			</form>
		</div>
	</x-popup>

	{{-- Edit LO --}}
	<x-popup popup_title="Edit Learning Outcome" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-learning-outcome">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4" id="edit-learning-outcome-form">
				@csrf
				<div class="flex flex-col">
					<label for="title">Learning Outcome Title<span class="text-red">*</span></label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" placeholder="Learning Outcome Title" autofocus />
				</div>

				<div class="flex flex-col mt-4">
					<label for="number">Order/Number of Learning Outcome<span class="text-red">*</span></label>
					<x-input id="number" class="w-full mt-1" type="number" name="number" style="border-width: 3px;" placeholder="Learning Outcome Order/Number" autofocus />
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-40 xl:w-1/6">Submit</x-button>
					{{-- <x-button class="w-full md:w-40 xl:w-1/6">Cancel</x-button> --}}
				</div>

				{{-- Helper --}}
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-lo" class="h-lo">
			</form>
		</div>
	</x-popup>

	{{-- Delete LO --}}
	<x-confirmation popup_title="Delete Learning Outcome" id="delete-learning-outcome">
		Are you sure want to <span class="font-bold text-red">delete</span> the Learning Outcome <span class="font-bold text-light-blue" id="del-lo-name"></span> from this course?
	</x-confirmation>

	{{-- New topic --}}
	<x-popup popup_title="New Curriculum Topic" class="w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="new-curriculum-topic">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf
				{{-- Curriculum Topic Title --}}
				<div class="flex flex-col">
					<label for="topic_title">Curriculum Topic Title<span class="text-red">*</span></label>
					<x-input id="topic_title" class="w-full mt-1" type="text" name="topic_title" style="border-width: 3px;" value="{{ old('topic_title') }}" placeholder="Curriculum topic title" autofocus />
				</div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/4">
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
	<x-popup popup_title="Edit Curriculum Topic" class="w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-curriculum-topic">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf
				{{-- Curriculum Topic Title --}}
				<div class="flex flex-col">
					<label for="topic_title">Curriculum Topic Title<span class="text-red">*</span></label>
					<x-input id="topic_title" class="w-full mt-1" type="text" name="topic_title" style="border-width: 3px;" value="{{ old('topic_title') }}" placeholder="Curriculum topic title" autofocus />
				</div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/4">
						{{ __('Submit') }}
					</x-button>
				</div>

				{{-- Helper --}}
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-ctopic" class="h-ctopic">
			</form>
		</div>
	</x-popup>

	{{-- Delete curriculum topic --}}
	<x-confirmation popup_title="Delete Curriculum Topic" id="delete-curriculum-topic">
		Are you sure want to <span class="font-bold text-red">delete</span> the Curriculum Topic <span class="font-bold text-light-blue" id="del-ct-name"></span> from this course?
	</x-confirmation>

	{{-- Copy syllabus --}}
	<x-popup popup_title="Copy Syllabus Data" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="copy-syllabus-data">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<p class="mt-4"><strong class="text-red">Warning:</strong> You're about to copy topics, activities, sessions, and learning outcomes from the selected course below. This will <strong>erase current existing topics, activities, sessions, and learning outcomes from this course</strong> and replace with the data from the selected couse.</p>

			<form action="{{ route('admin.course.curriculum.copy-syllabus', $course->id) }}" method="post" class="mt-4">
				@csrf
				<div class="flex flex-col">
					<label for="course_id">Copy from:<span class="text-red">*</span></label>
					<x-select id="course_id" class="w-full mt-1" type="text" name="course_id" style="border-width: 3px;">
						@foreach ($all_courses as $c)
							<option value="{{ $c->id }}">{{ $c->course_name }} - {{ ucwords($c->level) }}</option>
						@endforeach
					</x-select>
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-40 xl:w-1/6">Proceed</x-button>
				</div>
			</form>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container class="mb-10">
		{{-- Page title --}}
		<x-back-button href="{{ route('admin.course.index') }}"></x-back-button>
		<div class="flex items-center justify-between">
			<x-page-title style="margin-bottom: 0;">{{ $course->course_name }}</x-page-title>
			<div class="relative">
				<x-button type="button" id="copy-syllabus-data-btn"><i class="bi bi-copy"></i> Copy Syllabus Data</x-button>
				@if($course_empty)
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
				@endif
			</div>
		</div>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		{{-- Flash messages --}}
		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		{{-- Course informations --}}
		<div class="w-full flex flex-col gap-y-4">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Course Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $course->course_name }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Status</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ ucwords($course->status) }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Level</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ ucwords($course->level) }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Format</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $course->format }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Delivery Mode</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ ucwords($course->delivery_mode) }}</div>
				</div>

				<div class="flex flex-col w-1/2"></div>
			</div>

			<div class="flex flex-col w-full">
				<p>Course Description</p>
				<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{!! nl2br($course->course_description) !!}</div>
			</div>

		</div>

		<div class="flex gap-3 mt-8 w-full justify-end">
			<x-anchor-button  href="{{ route('admin.course.edit', $course->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
			<x-button type="button" id="delete-course-btn" data-route="{{ route('admin.course.destroy', $course->id) }}" data-del_course_name="{{ $course->course_name }}"><i class="bi bi-trash3"></i> Delete</x-button>
		</div>

		{{-- Learning Outcomes --}}
		<div class="w-full bg-slate-400 mt-12" style="height: 2px;"></div>
		<div class="flex items-center justify-between">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Learning Outcomes</h2>
			<div class="relative">
				<x-button type="button" class="newlearningoutcome-popuptrigger" data-route="{{ route('admin.course.learning-outcome.store', $course->id) }}"><i class="bi bi-plus-lg"></i> New Learning Outcome</x-button>
				@if($learning_outcomes_empty)
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
				@endif
			</div>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

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
							<td class="py-3 px-4">{{ $lo->number }}</td>
							<td class="py-3 px-4">{{ $lo->title }}</td>
							<td class="py-3 px-4">
								<div class="flex justify-start gap-1 w-full">
									<button type="button" class="editlearningoutcome-popuptrigger" data-route="{{ route('admin.course.learning-outcome.update', [$course->id, $lo->id]) }}" data-learning_outcome="{{ $lo->toJSON() }}" title="Edit this learning outcome">
										<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</button>
									<button type="button" class="deletelearningoutcome-popuptrigger" data-route="{{ route('admin.course.learning-outcome.destroy', [$course->id, $lo->id]) }}" data-del_lo_name="{{ $lo->title }}" title="Delete this learning outcome">
										<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</button>
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="p-5 text-center" colspan="3">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- Assigned teachers and students --}}
		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="my-3 flex items-center justify-between">
			<h2 class="font-extrabold text-xl text-dark-blue">List of Assigned Teachers</h2>
			<div class="relative">
				<x-anchor-button href="{{ route('admin.course.batch-assign-teacher', $course->id) }}"><i class="bi bi-ui-checks-grid"></i> Assign Teachers</x-anchor-button>
				@if($no_teachers_assigned)
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
				@endif
			</div>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-8 flex flex-wrap">
			@forelse ($course->teachers as $index => $teacher)
				<a href="{{ route('admin.teacher.show', $teacher->id) }}" class="w-1/6 mb-6 hover:text-cyan-500">
					<div class="flex flex-col items-center">
						@if($teacher->details->profpic)
							<img src="{{ Storage::url("app/public/" . $teacher->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
						@else
							@if($teacher->details->gender == 1)
								<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@else
								<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@endif
						@endif
						<h1 class="text-lg font-bold text-center">{{-- explode(" ", $teacher->full_name)[0] --}}{{ $teacher->full_name }}</h1>
					</div>
				</a>
			@empty
				- No teachers assigned to this course yet -
			@endforelse
		</div>

		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="flex w-full justify-between items-center">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Student List</h2>
			<div class="flex gap-4 justify-end">
				<div class="relative">
					<x-anchor-button  href="{{ route('admin.course.batch-assign-student', $course->id) }}"><i class="bi bi-ui-checks-grid"></i> Assign Students</x-anchor-button>
					@if($no_students_assigned)
						<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
					@endif
				</div>
				<x-anchor-button  href="{{ route('admin.course.import-student-data', $course->id) }}"><i class="bi bi-card-checklist"></i> Import Old Student</x-anchor-button>
			</div>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-8 flex flex-wrap">
			@forelse ($course->students as $index => $student)
				<a href="{{ route('admin.student.show', $student->id) }}" class="w-1/6 mb-6 hover:text-cyan-500">
					<div class="flex flex-col items-center">
						@if($student->details->profpic)
							<img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
						@else
							@if($student->details->gender == 1)
								<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@else
								<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@endif
						@endif
						<h1 class="text-lg font-bold text-center">{{-- explode(" ", $student->full_name)[0] --}}{{ $student->full_name }}</h1>
					</div>
				</a>
			@empty
				- No students assigned yet to this course -
			@endforelse
		</div>

		{{-- Curriculum --}}
		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="flex w-full justify-between items-center">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Syllabus/Curriculum</h2>
			<div class="flex gap-4 justify-end">
				<x-anchor-button  href="{{ route('admin.course.curriculum.import-excel', $course->id) }}"><i class="bi bi-file-earmark-arrow-up"></i> Import from Excel</x-anchor-button>
				<div class="relative">
					<x-button type="button"  data-route="{{ route('admin.course.curriculum.topic.store', $course->id) }}" id="new-curriculum-topic-btn"><i class="bi bi-plus-lg"></i> Add New Topic</x-button>
					@if($syllabus_empty)
						<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
					@endif
				</div>
			</div>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="overflow-x-auto mt-3">
			<table class="w-full">
				<thead>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activities</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>

				@forelse ($course->curriculum_topics as $topic)
					<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
						<td class="py-3 px-4 text-center">
							@php
								if($topic->curriculum_activities->count()){
									echo $topic->curriculum_activities->min('session') . '-' . $topic->curriculum_activities->max('session');
								} else {
									echo 'N/A';
								}
							@endphp
						</td>
						<td class="py-3 px-4 w-1/4">
							{{ $topic->title }}
						</td>
						<td class="py-3 px-4" style="text-align: start">
							@if($topic->curriculum_activities->count())
								<ul class="list-disc list-inside">
									@foreach ($topic->curriculum_activities()->orderBy('session')->get() as $activity)
										<li class="mb-2">{{ $activity->title }}</li>
									@endforeach
								</ul>
							@else
								- No curriculum activities yet -
							@endif
						</td>
						<td class="py-3 px-4">
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

							@forelse($lolist as $los)
								<div class="w-full text-center">LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif</div>
							@empty
								<div class="w-full text-center">N/A</div>
							@endforelse
						</td>
						<td class="py-3 px-4">
							<div class="w-full flex items-center gap-1">
								<div class="relative">
									<a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $topic->id]) }}" title="View this curriculum topic details">
										<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
									@if($topic->curriculum_activities->count() == 0)
										<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
									@endif
								</div>
								<button type="button" data-curriculum_topic="{{ $topic }}" data-route="{{ route('admin.course.curriculum.topic.update', [$course->id, $topic->id]) }}" class="edit-curriculum-topic-btn" title="Edit this curriculum topic">
									<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
								</button>
								<button type="button" data-del_ct_name="{{ $topic->title }}" data-route="{{ route('admin.course.curriculum.topic.destroy', [$course->id, $topic->id]) }}" class="delete-curriculum-topic-btn" title="Delete this curriculum topic">
									<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
								</button>
							</div>
						</td>
					</tr>
				@empty
					<tr><td colspan="5" class="text-center p-5 bg-white">- No curriculum topics and activities yet -</td></tr>
				@endforelse
			</table>
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
			const oldTitle = '{{ old ('title') }}';
			const oldNumber = '{{ old('number') }}';

			// If old values exist, fill them in
			$('input[name="title"]').val(oldTitle || learning_outcome.title);
			$('input[name="number"]').val(oldNumber || learning_outcome.number);

			$("#edit-learning-outcome-form").attr("action", route);

			// Display the popup
			$("#edit-learning-outcome").parent().show();
		}

		function initializeNewCurriculumTopicPopup(route, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(`#${whichpopup}`).find('form').attr("action", route);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		function initializeEditCurriculumTopicPopup(route, curriculum_topic, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(".h-ctopic").val(JSON.stringify(curriculum_topic));

			// Retrieve old values
			const oldCurriculumTitle = '{{ old('curriculum_title') }}';

			// If old values exist, fill them in
			$('input[name="topic_title"]').val(oldCurriculumTitle ?  oldCurriculumTitle : curriculum_topic.title);

			$(`#${whichpopup}`).find('form').attr("action", route);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		$(document).ready(() => {
			$('#copy-syllabus-data-btn').on('click', function(){
				$('#copy-syllabus-data').parent().show();
			});

			// Delete course
			$('#delete-course-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-course-popup").find('form').attr("action", $(this).data('route'));
				$("#del-course-name").text($(this).data('del_course_name'));

				// Show the popup
				$("#delete-course-popup").parent().show();
			});

			// New LO
			$('.newlearningoutcome-popuptrigger').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "add-learning-outcome";

				initializeNewLearningOutcomePopup(route, whichpopup);
			});

			// Edit LO
			$('.editlearningoutcome-popuptrigger').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "edit-learning-outcome";
				const learning_outcome = $(this).data("learning_outcome");

				initializeEditLearningOutcomePopup(route, learning_outcome, whichpopup);
			});

			// Delete LO
			$('.deletelearningoutcome-popuptrigger').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-learning-outcome").find('form').attr("action", $(this).data('route'));
				$("#del-lo-name").text($(this).data('del_lo_name'));

				// Show the popup
				$("#delete-learning-outcome").parent().show();
			});

			// Create new curriculum topic
			$('#new-curriculum-topic-btn').on('click', function(){
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "new-curriculum-topic";

				initializeNewCurriculumTopicPopup(route, whichpopup);
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
			$('.delete-curriculum-topic-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-curriculum-topic").find('form').attr("action", $(this).data('route'));
				$("#del-ct-name").text($(this).data('del_ct_name'));

				// Show the popup
				$("#delete-curriculum-topic").parent().show();
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
				else if(old_popup == "new-curriculum-topic"){
					const old_route = @json(old('h-route'));

					initializeNewCurriculumTopicPopup(old_route, old_popup);
				}
				else if(old_popup == "edit-curriculum-topic"){
					const old_route = @json(old('h-route'));
					const old_ctopic = @json(old('h-ctopic'));

					initializeEditCurriculumTopicPopup(old_route, JSON.parse(old_ctopic), old_popup);
				}
			@endif
		});
	</script>

@endsection
