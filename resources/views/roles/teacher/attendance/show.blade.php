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

					<div class=" contentTable w-full" style="display: none;" >
						<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>

						<div class="w-full overflow-x-auto">
							<table class="w-full">
								<thead>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Students</th>
									<th class="text-center py-3 px-4 border-b-2 border-slate-400">Attended</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">In Class Progress</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Notes</th>
								</thead>
								<tbody>
									@forelse ($atd->student_attendances as $sa)
										@if($sa->attendance_detail != "Account disabled")
											<tr class="@if($loop->index % 2 == 0) bg-white @endif">
												<td class="py-2 px-4">{{ $sa->student->full_name }}</td>
												<td class="py-2 px-4">
													<div class="w-full flex justify-center">
														@if($sa->is_attend == 1)
															<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">
														@else
															<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">
														@endif
													</div>
												</td>
												<td class="py-2 px-4">
													{{ $sa->material_progress ?? "N/A" }} [{{ $sa->learning_status ?? "N/A" }}]
												</td>
												<td class="py-2 px-4 w-1/3">
													{{ $sa->attendance_detail }}
												</td>
											</tr>
										@endif
									@empty
										<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No materials added yet to this course topic -</td></tr>
									@endforelse
								</tbody>
							</table>
						</div>
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
