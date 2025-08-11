@extends("layouts.main-teacher")

@section("title")
	<h1>Attendance</h1>
@endsection

@section("popup")
	<x-popup popup_title="Attendance Details" class="w-5/6 flex flex-col items-stretch justify-center overflow-y-auto" id="attendance-details">
		{{-- Popup content --}}
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Time</th>
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

	<x-popup popup_title="New Substitution Attendance" class="w-1/2 flex flex-col items-stretch justify-center overflow-y-auto" id="select-course">
		<div class="w-full flex gap-5 mt-3 attendance-detail-fields">
			<div class="w-full container-select2">
				<x-label for="course_selection" class="mb-1">Please select a course:</x-label>
				<x-select name="course_selection" id="course_selection" class="w-full select-2">
					@foreach($courses as $course)
						<option value="{{ route('teacher.attendance.substitution.create', $course->id) }}">{{ $course->course_name }} - {{ ucwords($course->level) }}</option>
					@endforeach
				</x-select>
			</div>
		</div>

		<div class="mt-8 w-full flex justify-center">
			<x-button type="button" class="w-1/3" id="confirm-course-button">Confirm</x-button>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.attendance.index') }}"></x-back-button>
		<x-page-title>My Substitute Attendances</x-page-title>

		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		@if(request('content') == 'attendance reports' || !request('content'))
			<x-button type="button" class="self-start mt-6" id="new-substitute-attendance-btn">
				<i class="bi bi-plus-lg"></i> New Substitute Attendance Report
			</x-button>

			<div class="w-full overflow-auto hidden xl:block mt-6" style="height: 60vh;">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Students</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Attended</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Absent</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Uploader</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@forelse ($substitution_attendances as $atd)
							<tr class="@if($loop->index % 2 == 0) bg-white @endif">
								<td class="py-3 px-4">{{ Carbon\Carbon::parse($atd->attendance_date)->format('l, d F Y') }}</td>
								<td class="py-3 px-4">{{ $atd->course->course_name }} - {{ ucwords($atd->course->level) }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->student_attendances->count() }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->student_attendances->where("is_attend", 1)->count() }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->student_attendances->where("is_attend", 0)->count() }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->posted_by->id == Auth::user()->id? 'Me' : $atd->posted_by->full_name }}</td>
								<td class="py-3 px-4">
									<div class="flex gap-1 justify-center">
										<a href="{{ route('teacher.attendance.substitution.edit', $atd->id) }}"><img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></a>
										<button type="button" data-attendance="{{ $atd }}" data-student_attendances="{{ $atd->student_attendances()->with('student')->get() }}" class=" show-attendance-details-button" title="Check this attendance report details"><img src="{{ asset('img/history.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></button>
									</div>
								</td>
							</tr>
						@empty
							<tr><td class="p-5 bg-white font-semibold text-center" colspan="6">- No attendance data yet -</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>

			{{-- For smaller screen --}}
			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
				@forelse ($substitution_attendances as $atd)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
						<button class="flex flex-col items-start dropdown-toggler w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<strong class="text-base text-start">{{ Carbon\Carbon::parse($atd->attendance_date)->format('l, d F Y') }}</strong>
								<i class="bi bi-chevron-down"></i>
							</div>
						</button>
						<div class="flex flex-col w-full dropdown-menu" style="display: none;">
							{{ $atd->student_attendances->count() }} Students Reported:
							<ol class="list-disc list-inside">
								<li>{{ $atd->student_attendances->where("is_attend", 1)->count() }} Attended</li>
								<li>{{ $atd->student_attendances->where("is_attend", 0)->count() }} Absent</li>
							</ol>

							<div class="mt-5">
								<i>Posted by</i><br>{{ $atd->posted_by->id == Auth::user()->id? 'Me' : $atd->posted_by->full_name }}
							</div>
	
							<strong class="mt-5">Actions</strong>
							<div class="flex gap-3 items-start my-3">
								<a href="{{ route('teacher.attendance.substitution.edit', $atd->id) }}"><img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></a>
								<button type="button" data-attendance="{{ $atd }}" data-student_attendances="{{ $atd->student_attendances()->with('student')->get() }}" class=" show-attendance-details-button"><img src="{{ asset('img/history.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></button>
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		<script>
			$(document).ready(() => {
				$(".show-attendance-details-button").click(function(){
					$("#attendance-details-tbody").empty();

					const attendance = $(this).data('attendance');
					const student_attendances = $(this).data('student_attendances');

					let i = 0;
					for(let sa of student_attendances){
						console.log(sa);
						const col1 = $('<td>').addClass('py-3 px-4 text-center').text(i + 1);
						const col2 = $('<td>').addClass('py-3 px-4').text(sa.student.full_name);
						const col4 = $('<td>').addClass('py-3 px-4').text(sa.is_attend == 1 ? `${sa.start_time.slice(0, 5)}-${sa.end_time.slice(0, 5)}` : 'Absent');
						const col5 = $('<td>').addClass('py-3 px-4').html(sa.is_attend == 1 ? `<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">` : `<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">`);
						const col6 = $('<td>').addClass('py-3 px-4').text(sa.is_attend == 1 ? `${sa.activity_progress} [${sa.learning_status}]` : 'Absent');
						const col7 = $('<td>').addClass('py-3 px-4').text(sa.attendance_detail);

						let rowBG;
						if(i % 2 == 0){
							rowBG = "rgb(237, 241, 247)";
						}
						else {
							rowBG = "white";
						}

						$("#attendance-details-tbody").append(
							$("<tr>").css("background-color", rowBG).append(col1).append(col2).append(col4).append(col5).append(col6).append(col7)
						);
						i++;
					}

					$("#attendance-details").parent().show();
				});

				$('#new-substitute-attendance-btn').on('click', function(){
					$('#select-course').parent().show();
				})
			});

			$('#confirm-course-button').on('click', function() {
				const selectedUrl = $('#course_selection').val();
				window.location.href = selectedUrl;
			});
		</script>
	</x-section-container>

@endsection
