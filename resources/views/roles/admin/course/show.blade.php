@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-page-title style="margin-bottom: 0">{{ __("Course Details") }}</x-page-title>
	<h6 class="text-sm italic text-gray-500 text-center mb-4">(Visibility: {{ $course->visibility }})</h6>

	@if(session()->has("successUpdateCourseData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateCourseData") }}</p>
		</div>
	@elseif(session()->has("successBatchAssign"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successBatchAssign") }}</p>
		</div>
	@elseif(session()->has("successImportStudent"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successImportStudent") }}</p>
		</div>
	@endif

	<div class="px-10 py-5 bg-indigo-200 rounded-3xl my-10">

		<x-page-title>{{ __("About Course") }}</x-page-title>

		<p class="text-gray-700 mb-3">{{ $course->course_description }}</p>

		<div class="flex justify-center gap-5 mt-8">
			<x-anchor-button class="bg-orange-500 w-1/2 md:w-1/5" href="{{ route('admin.course.edit', $course->id) }}"><i class="bi bi-pencil-square"></i> Edit Course</x-anchor-button>
			<x-anchor-button class="bg-orange-500 w-1/2 md:w-1/5" href="{{ route('admin.course.delete', $course->id) }}"><i class="bi bi-trash3"></i> Delete Course</x-anchor-button>
		</div>
	</div>

	<div class="flex flex-col items-stretch mt-10">
		<div class="rounded-xl py-5 px-10 text-white bg-blue-900">List of Assigned Teachers</div>
		<div class="flex flex-wrap gap-x-10 overflow-y-auto bg-indigo-200 px-10 py-5 rounded-xl mt-3" style="max-height: 300px;">
			@forelse ($course->teachers as $teacher)
				<div class="text-white border-4 border-white bg-yellow-500 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 220px; max-width: 220px; min-height: 80px; max-height: 80px;">{{ ($teacher->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ $teacher->full_name }}</div>
			@empty
				<div class="flex w-full justify-center">
					<span>- No student enrolled in this course yet -</span>
				</div>
			@endforelse
		</div>
	</div>

	<div class="flex flex-col items-stretch mt-10">
		<div class="rounded-xl py-5 px-10 text-white bg-blue-900 flex flex-col md:flex-row gap-5 md:gap-0 items-center justify-between">
			<div class="">List of Assigned Students</div>
			<div class="flex gap-5">
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.batch-assign', $course->id) }}">Batch Assign</x-anchor-button>
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.import-student-data', $course->id) }}">Import Student</x-anchor-button>
			</div>
		</div>
		<div class="flex flex-wrap gap-x-10 overflow-y-auto bg-indigo-200 px-10 py-5 rounded-xl mt-3" style="max-height: 300px;">
			@forelse ($course->students as $student)
				<div class="text-white border-4 border-white bg-yellow-500 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 220px; max-width: 220px; min-height: 80px; max-height: 80px;">
					{{ $student->full_name }}
					@if($student->status == "disabled")
						<span class="text-red-500">(Disabled)</span>
					@endif
				</div>
			@empty
				<div class="flex w-full justify-center">
					<span>- No student enrolled in this course yet -</span>
				</div>
			@endforelse
		</div>
	</div>

	<div class="rounded-xl py-5 px-10 mt-10 bg-indigo-200">
		<x-page-title>{{ __("Course Topics and Materials") }}</x-page-title>
		<div class="mb-6 overflow-x-auto">
			<table class="w-full bg-white">
				<thead class="bg-blue-900 text-white">
					<th class="border border-blue-400 px-5 py-3">Topic name</th>
					<th class="border border-blue-400 px-5 py-3">Materials</th>
				</thead>
				<tbody>
					@forelse ($course->topics as $topic)
						<tr>
							<td class="border border-blue-400 px-5 py-3">{{ $topic->title }}</td>
							<td class="border border-blue-400 px-5 py-3">
								@if($topic->materials->count())
									<ul>
										@foreach ($topic->materials as $material)
											<li>{{ $material->title }}</li>
										@endforeach
									</ul>
								@else
									<span class="text-gray-500">- No materials yet -</span>
								@endif
							</td>
						</tr>
					@empty
						<tr class="text-gray-500 border border-blue-900"><td colspan="2" class="text-center py-3">- No topics yet -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

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
