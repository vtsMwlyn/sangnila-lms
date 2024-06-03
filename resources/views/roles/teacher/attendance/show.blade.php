@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Student Attendance for {{ $course->course_name }}</h1>

	@if(session()->has("successUploadAttendance"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUploadAttendance") }}</p>
		</div>
	@elseif(session()->has("successEditAttendance"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successEditAttendance") }}</p>
		</div>
	@endif

	<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
		href="{{ route('teacher.attendance.upload', $course->id) }}">
		Upload New Attendance
	</a>

	@if($attendanceData->count())
		@foreach($attendanceData as $atd)
			<div class="border rounded-lg p-5 mb-5 mt-5">
				<p>Date: {{ $atd->attendance_date }}</p>
				<p>Uploaded by: {{ $atd->posted_by->full_name }}</p>
				<div class="mt-5">
					<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
						href="{{ route("teacher.attendance.edit", $atd->id) }}">
						Edit
					</a>
				</div>
				<div class="overflow-x-auto">
					<table class="w-full mt-5 mb-5">
						<thead>
							<th class="border px-3">Students</th>
							<th class="border px-3">Attendance status</th>
							<th class="border px-3">Attendance detail</th>
						</thead>
						<tbody>
							{{--
							Note:
							Student attendance data when the account is disabled by admin will not be shown
							--}}

							@foreach($atd->student_attendances as $sa)
								@if($sa->attendance_detail != "Account disabled")
									<tr>
										<td class="border px-3">{{ $sa->student->full_name }}</td>
										<td class="border px-3">
											@if($sa->is_attend == 1)
												Attended
											@elseif($sa->is_attend == 0)
												Absent
											@endif
										</td>
										<td class="border px-3">{{ $sa->attendance_detail }}</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@endforeach

		{{-- <div class="">
			{{ $attendanceData->links() }}
		</div> --}}
	@else
		<div class="mt-5">- No attendance data yet -</div>
	@endif
@endsection
