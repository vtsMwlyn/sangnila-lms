@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<h2 class="text-2xl font-semibold text-blue-900 mb-4">Student Details</h2>

	@if(session()->has("successAssignToCourse"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successAssignToCourse") }}</p>
		</div>
	@elseif(session()->has("successUnassignFromCourse"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successUnassignFromCourse") }}</p>
		</div>
	@elseif(session()->has("successUpdateStudentData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateStudentData") }}</p>
		</div>
	@endif

	<div class="h-fit mb-5">
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
			href="{{ route("admin.student.edit", $student->id) }}">Edit</a>
	</div>
	<table class="mb-8 border">
		<tr>
			<td class="border px-5 font-bold">Full name</td>
			<td class="border px-5">{{ $student->full_name }}</td>
		</tr>
		<tr>
			<td class="border px-5 font-bold">Email</td>
			<td class="border px-5">{{ $student->email }}</td>
		</tr>
	</table>

	<h2 class="text-xl font-semibold mb-5">Course(s) enrolled by this user:</h2>
	<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
		href="{{ route('admin.student.assign.create', $student->id) }}">
		Assign to course
	</a>

	<ul class="mb-6 mt-6 flex flex-wrap gap-5">
		@forelse ($student->enrolled_courses as $course)
			<li class="text-black border rounded-lg bg-gray-300 px-3 py-1">
				{{ $course->course_name }}
				<a
					href="{{ route('admin.student.unassign.delete', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
					class="">
					<i class="bi bi-x-circle-fill"></i>
				</a>
			</li>
		@empty
			<li class="text-gray-500">No course</li>
		@endforelse
	</ul>

	<h2 class="text-xl font-semibold mb-3 mt-5">Student's Assignment and Attendance Data:</h2>
	<div class="overflow-x-auto">
		<table class="w-full">
			<thead>
				<th class="border px-3">Course</th>
				<th class="border px-3">Attendance</th>
				<th class="border px-3">Assignments</th>
			</thead>
			<tbody>
				@if($student->enrolled_courses->count())
					@for($i = 0; $i < $student->enrolled_courses->count(); $i++)
						<tr>
							<td class="border px-3">{{ $student->enrolled_courses[$i]->course_name }}</td>
							<td class="border px-3">
								<div class="flex w-full items-center gap-3">
									<span>{{ $attended[$i] }}/{{ $attendance_if_full[$i] }} attended</span>
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route("admin.student.atd-details", [$student->id, $student->enrolled_courses[$i]->id]) }}">
										Details
									</a>
								</div>
							</td>
							<td class="border px-3">
								<div class="flex w-full items-center gap-3">
									<span>{{ $done_assignment[$i] }}/{{ $assignment_if_full[$i] }} done</span>
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route("admin.student.asg-details", [$student->id, $student->enrolled_courses[$i]->id]) }}">
										Details
									</a>
								</div>
							</td>
						</tr>
					@endfor
				@else
					<tr><td colspan="3" class="text-center border px-3">- Student isn't assigned to any courses yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
	
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
