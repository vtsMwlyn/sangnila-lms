@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Student Attendance for {{ $course->course_name }}</x-page-title>

		@if(session()->has("successUploadAttendance"))
			<x-badge-success badge_text="{{ session('successUploadAttendance') }}"></x-badge-success>
		@elseif(session()->has("successEditAttendance"))
			<x-badge-success badge_text="{{ session('successEditAttendance') }}"></x-badge-success>
		@endif

		<div class="">
			<x-anchor-button class="bg-orange-500 mt-5 mb-3" href="{{ route('teacher.attendance.select-students', $course->id) }}">
				<i class="bi bi-plus-lg"></i> Upload New Attendance
			</x-anchor-button>
		</div>

		@if($attendanceData->count())
			@foreach($attendanceData as $atd)
				<div class="p-5 my-4 rounded-xl" style="background-color: rgba(255, 255, 255, 0.3)">
					<div class="flex w-full items-center justify-between">
						<p class="text-blue-950 font-semibold">Attendance Date:<br><span class="font-bold text-blue-950">{{ $atd->attendance_date }}</span></p>
						<div class="flex gap-3">
							<x-anchor-button class="bg-orange-500" href="{{ route('teacher.attendance.edit', $atd->id) }}">
								Edit Data
							</x-anchor-button>
							<x-button type="button" class="bg-orange-500 toggleBtn">Show Details</x-button>
						</div>
					</div>

					{{-- <p>Uploaded by: {{ ($atd->posted_by->details->gender == 1)? "Mr." : "Ms." }} {{ $atd->posted_by->full_name }}</p> --}}

					<div class="overflow-x-auto contentTable" style="display: none;">
						<x-table>
							<x-slot name="head">
								<th class="template-heads rounded-l-xl">Students</th>
								<th class="template-heads">Attendance status</th>
								<th class="template-heads">In Class Progress</th>
								<th class="template-heads rounded-r-xl">Material Progress</th>
							</x-slot>

							@foreach($atd->student_attendances as $sa)
								@if($sa->attendance_detail != "Account disabled")
									<tr>
										<td class="template-bodies rounded-l-xl">{{ $sa->student->full_name }}</td>
										<td class="template-bodies">
											@if($sa->is_attend == 1)
												Attended
											@elseif($sa->is_attend == 0)
												Absent
											@endif
										</td>
										<td class="template-bodies">{{ $sa->attendance_detail }}</td>
										<td class="template-bodies rounded-r-xl">{{ $sa->material_progress ?? "N/A" }} [{{ $sa->learning_status ?? "N/A" }}]</td>
									</tr>
								@endif
							@endforeach

						</x-table>
					</div>
				</div>
			@endforeach

			{{-- <div class="">
				{{ $attendanceData->links() }}
			</div> --}}
		@else
			<div class="mt-5 p-5 w-full flex justify-center rounded-xl bg-white font-semibold">- No attendance data yet -</div>
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
	</x-section-container>

@endsection
