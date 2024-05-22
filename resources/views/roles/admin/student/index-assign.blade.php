@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<h2 class="text-2xl font-semibold text-blue-900 mb-4">Assign Student to Course</h2>

	@if($courses->count())
		<form action="{{ route('admin.student.assign.store', $student->id) }}" method="post" class="border rounded-lg p-5">
			@csrf
			<x-label for="visibility" :value="__('Select a course to assign')" />

				<select name="course_name" id="course_name" class="rounded-md shadow-sm border-gray-300 px-4 py-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full mt-4">
					@foreach ($courses as $course)
						<option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
					@endforeach
				</select>

				<div class="flex gap-1 mt-4 items-center">
					<x-button type="button" onclick="history.back()" class="bg-indigo-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25">
						Cancel
					</x-button>

					<x-button class="bg-indigo-400">
						{{ __('Add') }}
					</x-button>
				</div>


		</form>
	@else
		<p class="text-gray-500 italic">- No more courses to assign -</p>
	@endif

	<h2 class="text-xl font-semibold mb-5">Course(s) enrolled by this user:</h2>

	<ul class="list-disc pl-6 mb-6 mt-5">
		@forelse ($student->enrolled_courses as $course)
			<li class="text-black">{{ $course->course_name }}</li>
		@empty
			<li class="text-gray-500">No course</li>
		@endforelse
	</ul>

	{{-- Schedule --}}
	{{-- <h2 class="text-xl font-semibold mb-2">Student Schedules:</h2>

	@if ($student->schedules->isNotEmpty())
		<div class="overflow-x-auto">
			<table class="min-w-full bg-white border-collapse">
				<thead>
					<tr>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Course Name</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Day of Week</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Start Time</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">End Time</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($student->schedules as $schedule)
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								<a href="{{ route('admin.course.show', ['course_id' => $schedule->schedule->course->id ]) }}"
									class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
									{{ $schedule->schedule->course->course_name }}
								</a>
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								{{ $schedule->schedule->day_of_week }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								{{ $schedule->schedule->start_time }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								{{ $schedule->schedule->end_time }}
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center py-2">No schedules found for this student.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	@else
		<div class="text-blue-900">N/A</div>
	@endif --}}
@endsection
