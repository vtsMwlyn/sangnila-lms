@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container class="mb-10">
		<x-page-title>{{ __("Course Details") }}</x-page-title>
		{{-- <h6 class="text-sm italic text-gray-500 text-center mb-4">(Visibility: {{ $course->visibility }})</h6> --}}

		@if(session()->has("successUpdateCourseData"))
			<x-badge-success badge_text="{{ session('successUpdateCourseData') }}"></x-badge-success>
		@elseif(session()->has("successBatchAssign"))
			<x-badge-success badge_text="{{ session('successBatchAssign') }}"></x-badge-success>
		@elseif(session()->has("successImportStudent"))
			<x-badge-success badge_text="{{ session('successImportStudent') }}"></x-badge-success>
		@elseif(session()->has("successAddCurriculumTopic"))
			<x-badge-success badge_text="{{ session('successAddCurriculumTopic') }}"></x-badge-success>
		@elseif(session()->has("successEditCurriculumTopic"))
			<x-badge-success badge_text="{{ session('successEditCurriculumTopic') }}"></x-badge-success>
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
	</x-section-container>

	<x-section-container>
		<div class="flex flex-col items-stretch mt-5">
			<div class="rounded-2xl py-5 px-10 text-white bg-blue-950">List of Assigned Teachers</div>
			<div class="flex flex-wrap gap-x-10 overflow-y-auto py-3 mt-3" style="max-height: 300px;">
				@forelse ($course->teachers as $teacher)
					<div class="text-white border-2 border-white bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 220px; max-width: 220px; min-height: 100px; max-height: 100px;">{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}</div>
				@empty
					<div class="flex w-full justify-center bg-white rounded-xl p-5 font-semibold">
						<span>- No student enrolled in this course yet -</span>
					</div>
				@endforelse
			</div>
		</div>

		<div class="flex flex-col items-stretch mt-10">
			<div class="rounded-2xl py-5 px-10 text-white bg-blue-950 flex flex-col md:flex-row gap-5 md:gap-0 items-center justify-between">
				<div class="">List of Assigned Students</div>
				<div class="flex gap-5">
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.batch-assign', $course->id) }}"><i class="bi bi-ui-checks-grid"></i> Batch Assign</x-anchor-button>
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.import-student-data', $course->id) }}"><i class="bi bi-card-checklist"></i> Import Old Student</x-anchor-button>
				</div>
			</div>
			<div class="flex flex-wrap gap-x-10 overflow-y-auto py-3 mt-3" style="max-height: 300px;">
				@forelse ($course->students as $student)
					<div class="text-white border-4 border-white bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 220px; max-width: 220px; min-height: 100px; max-height: 100px;">
						{{ $student->full_name }}
						@if($student->status == "disabled")
							<span class="text-red-500">(Disabled)</span>
						@endif
					</div>
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
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.curriculum.topic.create', $course->id) }}"><i class="bi bi-plus-lg"></i> Add New Topic</x-anchor-button>
			</div>

			<div class="overflow-x-auto mt-3">
				<x-table>
					<x-slot name="head">
						<th class="template-heads rounded-l-xl">Topic</th>
						<th class="template-heads">Materials</th>
						<th class="template-heads rounded-r-xl">Action</th>
					</x-slot>
					@forelse ($course->curriculum_topics as $topic)
						<tr>
							<td class="template-bodies rounded-l-xl"><a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $topic->id]) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $topic->title }}</a></td>
							<td class="template-bodies">
								@if($topic->curriculum_materials->count())
									<ul>
										@foreach ($topic->curriculum_materials as $material)
											<li>{{ $material->title }}</li>
										@endforeach
									</ul>
								@else
									<span class="text-gray-500 font-semibold">- No materials yet -</span>
								@endif
							</td>
							<td class="template-bodies rounded-r-xl w-1/4">
								<div class="w-full flex flex-col items-center justify-center gap-3">
									<x-anchor-button href="{{ route('admin.course.curriculum.topic.edit', [$course->id, $topic->id]) }}" class="bg-orange-500 w-1/2"><i class="bi bi-pencil-square"></i> Edit Topic</x-anchor-button>
									<x-anchor-button href="{{ route('admin.course.curriculum.topic.delete', [$course->id, $topic->id]) }}" class="bg-orange-500 w-1/2"><i class="bi bi-trash3"></i> Delete Topic</x-anchor-button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="3" class="text-center font-semibold rounded-xl p-5 bg-white">- No curriculum topics and materials yet -</td></tr>
					@endforelse
				</x-table>
			</div>

			</div>
		</div>
	</x-section-container>

		{{-- Schedule --}}
		{{-- <h2 class="text-xl font-semibold mb-2">Schedule List:</h2>
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white" href="{{ route('admin.schedule.create', $course->id) }}">Add
			New Schedule</a>
		@if ($course->schedules->isNotEmpty())
			<div class="overflow-x-auto mt-5">
				<table class="min-w-full bg-white border-collapse border border-blue-400">
					<thead>
						<tr>
							<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Day of Week</th>
							<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Start Time</th>
							<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">End Time</th>
							<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($course->schedules as $schedule)
							<tr>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<div>{{ $schedule->day_of_week }}</div>
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<div>{{ $schedule->start_time }}</div>
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<div>{{ $schedule->end_time }}</div>
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
										href="{{ route('admin.schedule.edit', $schedule->id) }}">Edit Schedule</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			<div class="text-blue-900">N/A</div>
		@endif --}}


@endsection
