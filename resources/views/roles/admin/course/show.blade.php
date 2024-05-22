@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Course Details</h1>

	@if(session()->has("successUpdateCourseData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateCourseData") }}</p>
		</div>
	@endif

	<p class="text-gray-700 mb-3">{{ $course->course_description }}</p>
	<span class="text-sm italic text-gray-500">(Visibility: {{ $course->visibility }})</span>

	<div class="flex space-x-4 mt-8 mb-6">
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('admin.course.edit', $course->id) }}">Edit Course</a>
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
		href="{{ route('admin.course.delete', $course->id) }}">Delete</a>
	</div>

	<h2 class="text-xl font-semibold mb-2">Teacher List:</h2>
	<ul class="list-disc pl-6 mb-6">
		@forelse ($course->teachers as $teacher)
			<li class="text-black">{{ $teacher->full_name }}</li>
		@empty
			<li class="text-gray-500">No teacher for this course</li>
		@endforelse
	</ul>
	<h2 class="text-xl font-semibold mb-2">Student List:</h2>
	<ul class="list-disc pl-6 mb-6">
		@forelse ($course->students as $student)
			<li class="text-black">{{ $student->full_name }}</li>
		@empty
			<li class="text-gray-500">No student enrolled in this course</li>
		@endforelse
	</ul>

	<h2 class="text-xl font-semibold mb-2 mt-8">Course Topics & Materials:</h2>
	<div class="mb-6 overflow-x-auto">
		<table class="w-full">
			<thead>
				<th class="border px-3">Topic name</th>
				<th class="border px-3">Materials</th>
			</thead>
			<tbody>
				@forelse ($course->course_topics as $topic)
					<tr>
						<td class="border px-3">{{ $topic->title }}</td>
						<td class="border px-3">
							@if($topic->course_materials->count())
								<ul>
									@foreach ($topic->course_materials as $material)
										<li>{{ $material->title }}</li>
									@endforeach
								</ul>
							@else
								<span class="text-gray-500">- No materials yet -</span>
							@endif
						</td>
					</tr>
				@empty
					<tr class="text-gray-500 border"><td colspan="2" class="text-center">- No topics yet -</td></tr>
				@endforelse
			</tbody>
		</table>
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
