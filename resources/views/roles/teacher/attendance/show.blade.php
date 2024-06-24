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

	<x-anchor-button class="bg-indigo-400 mb-3"
		href="{{ route('teacher.attendance.upload', $course->id) }}">
		Upload New Attendance
	</x-anchor-button>

	@if($attendanceData->count())
		@foreach($attendanceData as $atd)
			<div class="border rounded-lg p-5 mb-5 mt-5">
				<div class="flex w-full items-center justify-between">
					<p>Attendance Date:<br><span class="font-semibold">{{ $atd->attendance_date }}</span></p>
					<div class="flex gap-3">
						<x-anchor-button class="bg-indigo-400" href="{{ route('teacher.attendance.edit', $atd->id) }}">
							Edit Data
						</x-anchor-button>
						<x-button type="button" class="bg-orange-500 toggleBtn">Show Details</x-button>
					</div>
				</div>

				{{-- <p>Uploaded by: {{ ($atd->posted_by->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ $atd->posted_by->full_name }}</p> --}}

				<div class="overflow-x-auto contentTable" style="display: none;">
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

	<script>
		const allToggleBtn = $(".toggleBtn");
		const allContentTable = $(".contentTable");

		allToggleBtn.each(function(index, element) {
			$(element).click(() => {
				allContentTable.eq(index).slideToggle(() => {
				if (allContentTable.eq(index).is(":visible")) {
					$(element).text("Hide Details");
				} else {
					$(element).text("Show Details");
				}
			});
			});
		});
	</script>

@endsection
