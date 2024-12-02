@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("popup")
	<x-popup class="w-2/3 flex flex-col items-stretch justify-center overflow-y-auto" id="attendance-details">
		<!-- Popup header -->
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">Attendance Details</div>
			<button type="button" class="popup-dismiss">
				<img src="{{ asset('img/close.svg') }}" alt="history-icon" class="w-6 h-6 hover:scale-110">
			</button>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<!-- Popup content -->
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attended</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">In Class Progress</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Notes</th>
					</thead>
					<tbody id="attendance-details-tbody">
					</tbody>
				</table>
			</div>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.attendance.index') }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Attendance Reports List</h1>

		@if(session()->has("successUploadAttendance"))
			<x-badge-success badge_text="{{ session('successUploadAttendance') }}"></x-badge-success>
		@elseif(session()->has("successEditAttendance"))
			<x-badge-success badge_text="{{ session('successEditAttendance') }}"></x-badge-success>
		@endif

		<div class="mt-5">
			<x-anchor-button class="bg-orange-500  mb-3" href="{{ route('teacher.attendance.select-students', $course->id) }}">
				<i class="bi bi-plus-lg"></i> Upload New Attendance
			</x-anchor-button>
		</div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Students</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Attended</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Absent</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($attendanceData as $atd)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-2 px-4">{{ Carbon\Carbon::parse($atd->attendance_date)->format('d F Y') }}</td>
							<td class="py-2 px-4 text-center">{{ $atd->student_attendances->count() }}</td>
							<td class="py-2 px-4 text-center">{{ $atd->student_attendances->where("is_attend", 1)->count() }}</td>
							<td class="py-2 px-4 text-center">{{ $atd->student_attendances->where("is_attend", 0)->count() }}</td>
							<td class="py-2 px-4">
								<div class="flex gap-2">
									<x-anchor-button class="bg-orange-500" href="{{ route('teacher.attendance.edit', $atd->id) }}">
										<i class="bi bi-pencil-square"></i> Edit Data
									</x-anchor-button>
									<x-button type="button" class="bg-orange-500 show-attendance-details-button" data-attendance="{{ $atd }}" data-student_attendances="{{ $atd->student_attendances }}"><i class="bi bi-eye"></i> Show Details</x-button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td class="mt-5 p-5 w-full flex justify-center rounded-xl bg-white font-semibold">- No attendance data yet -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<script>

			$(document).ready(() => {
				$(".show-attendance-details-button").click(function(){
					$("#attendance-details-tbody").empty();

					const attendance = $(this).data('attendance');
					const student_attendances = $(this).data('student_attendances');

					let i = 0;
					for(let sa of student_attendances){
						const col1 = $("<td>").addClass("py-2 px-4 text-center").text(i + 1);
						const col2 = $("<td>").addClass("py-2 px-4").text(sa.student.full_name);
						const col3 = $("<td>").addClass("py-2 px-4").html(sa.is_attend == 1 ? `<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">` : `<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">`);
						const col4 = $("<td>").addClass("py-2 px-4").text(`${sa.activity_progress ? sa.activity_progress : 'N/A'} [${sa.learning_status ? sa.learning_status : 'N/A'}]`);
						const col5 = $("<td>").addClass("py-2 px-4").text(sa.attendance_detail);

						let rowBG;
						if(i % 2 == 0){
							rowBG = "rgb(237, 241, 247)";
						}
						else {
							rowBG = "white";
						}

						$("#attendance-details-tbody").append(
							$("<tr>").css("background-color", rowBG).append(col1).append(col2).append(col3).append(col4).append(col5)
						);
						i++;
					}

					$("#attendance-details").parent().show();
				});
			});
		</script>
	</x-section-container>

@endsection
