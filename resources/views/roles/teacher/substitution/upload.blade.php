@extends("layouts.main-teacher")

@section("title")
	<h1>Attendance</h1>
@endsection

@section('content')
    <x-section-container>
		<x-page-title>New Substitution Attendance Report</x-page-title>
		<h1 class="font-bold text-lg text-blue mt-1">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<x-badge-danger id="emptyDataNotif" badge_text="Please input minimum 1 data to proceed." style="display: none;"></x-badge-danger>

		{{-- @if($allStudents->count() && $course->teachers->count()) --}}
			<form action="{{ route('teacher.attendance.substitution.store', $course->id) }}" method="post" class="my-4" id="store-attendance-form">
				@csrf

				<div id="form-area">
					<div class="flex gap-5">
                        <div class="w-1/2">
                            <x-label for="attendance_date">Attendance Date<span class="text-red">*</span></x-label>
                            <div class="flex gap-3 mt-1 items-start" id="date-inp-cont">
                                <div class="flex flex-col items-start grow">
                                    <x-input type="date" class="date-input w-full" name="attendance_date" id="attendance_date"/>
                                    <p class="text-red font-bold mt-2 hidden" id="error-date"><i class="bi bi-exclamation-circle"></i> Attendance date field is required.</p>
                                </div>
                                <x-button type="button" id="todaybtn" class="mt-1">Today</x-button>
                            </div>
                        </div>
					</div>

					{{-- Learning data --}}
					<h1 class="font-bold text-lg text-blue mt-8">Attendance Data</h1>
					<div class="flex flex-col w-full">
						{{-- <div class="w-full flex gap-5 mt-3">
							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Attendance Status<span class="text-red">*</span></x-label>
								<x-select name="_is_attended" id="_is_attended" class="w-full">
									<option value="1">Attended</option>
									<option value="0">Absent</option>
								</x-select>
							</div>
                            <div class="w-full md:w-1/2"></div>
						</div> --}}
                        <div class="w-full flex gap-5">
                            <div class="mt-3 w-full md:w-1/2 container-select2">
                                <x-label class="mb-1">Student<span class="text-red">*</span></x-label>
                                <x-select type="text" name="_student_name" id="_student_name" class="w-full select-2">
                                    @foreach($course->students as $student)
										<option value="{{ $student }}">{{ $student->full_name }}</option>
									@endforeach
                                </x-select>
                                <input type="hidden" name="student_id" id="student_id">
                            </div>
                            <div class="w-full md:w-1/2"></div>
						</div>

						<div class="flex gap-5 w-full mt-4 attendance-detail-fields">
							{{-- Start Time --}}
							<div class="flex flex-col w-1/2">
								<x-label for="_start_time">Start Time<span class="text-red">*</span></x-label>
								<x-input class="_start_time" type="time" id="_start_time" value="00:00"/>
							</div>

							{{-- End Time --}}
							<div class="flex flex-col w-1/2">
								<x-label for="_end_time">End Time<span class="text-red">*</span></x-label>
								<x-input class="_end_time" type="time" id="_end_time" value="00:00"/>
							</div>
						</div>

						<div class="w-full flex gap-5 mt-3 attendance-detail-fields">
							<div class="w-full md:w-1/2 container-select2">
								<x-label for="_activity_progress" class="mb-1">Activity<span class="text-red">*</span></x-label>
								<x-select name="_activity_progress" id="_activity_progress" class="w-full select-2">
									@foreach($course->topics as $topic)
										@foreach($topic->activities as $activity)
											<option value="{{ $activity->title }}">{{ $activity->title }}</option>
										@endforeach
									@endforeach
									<option value="Other">Other</option>
								</x-select>
							</div>

							<div class="w-full md:w-1/2">
								<x-label for="_learning_status" class="mb-1">Learning Status<span class="text-red">*</span></x-label>
								<x-select name="_learning_status" id="_learning_status" class="w-full">
									<option value="Done">Done</option>
									<option value="On Progress">On Progress</option>
								</x-select>
							</div>
						</div>

						<div class="w-full mt-8">
							<x-label for="_attendance_details" class="mb-1">Details<span class="text-red">*</span></x-label>
							<x-textarea rows="4" name="_attendance_details" id="_attendance_details" class="w-full" placeholder="Input details">N/A</x-textarea>
						</div>
					</div>

					<div class="flex w-full justify-end mt-8">
						<x-button class=" w-1/2 md:w-1/6" type="button" id="addBtn">Add Data</x-button>
					</div>
				</div>

				<div class="flex mt-8">
					<h1 class="font-bold text-lg text-blue">Data to Add</h1>
				</div>
				<div class="overflow-x-auto mt-2">
					<table class="w-full">
						<thead>
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Time</th>
							{{-- <th class="text-start py-3 px-4 border-b-2 border-slate-400">Attended</th> --}}
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
						</thead>
						<tbody id="tableBody">
							<tr class="bg-white" id="empty-placeholder">
								<td colspan="4" class="p-4 text-center" id="empty-table-placeholder">- No data yet -</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
					<x-button class=" w-1/2 md:w-1/6">Submit Data</x-button>
					<x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
				</div>
			</form>

			<script>
				$(document).ready(function() {
					$('#addBtn').on('click', function(){
                        const inpStudent = JSON.parse($('#_student_name').val());

						const _inpActivityProgress = $('#_activity_progress').val();
						const inpLearningStatus = $('#_learning_status').val();
						const inpAttendanceDetails = $('#_attendance_details').val();
						let inpStartTime = $('#_start_time').val();
						let inpEndTime = $('#_end_time').val();

						const inpActivityProgress = _inpActivityProgress;

						if($('#tableBody').find('#empty-table-placeholder').length){
							$('#empty-table-placeholder').remove();
						}

						const newRow = $('<tr>');
						const removeBtn = $('<button>').html('<i class="bi bi-trash3"></i>').attr('type', 'button').addClass('text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150');

                        const rowCount = $('#tableBody').find('tr').length;

						newRow.addClass(rowCount % 2 == 1? 'bg-white' : '')
							.append(
								$('<td>').addClass('py-3 px-4').text(inpStudent.full_name)
                            ).append(
								$('<td>').addClass('py-3 px-4').html(`${inpStartTime}-${inpEndTime}`)
							).append(
								$('<td>').addClass('py-3 px-4').html(`${inpActivityProgress}<br><br>(${inpLearningStatus})<br>${inpAttendanceDetails}`)
							).append(
								$('<td>').addClass('py-3 px-4').append(removeBtn)
							);

                        const hidStudentId = $('<input>').attr({'type': 'hidden', 'name': 'student_id[]', 'value': inpStudent.id});
						const hidStartTime = $('<input>').attr({'type': 'hidden', 'name': 'start_time[]', 'value': inpStartTime});
						const hidEndTime = $('<input>').attr({'type': 'hidden', 'name': 'end_time[]', 'value': inpEndTime});
						const hidActivityProgress = $('<input>').attr({'type': 'hidden', 'name': 'activity_progress[]', 'value': inpActivityProgress});
						const hidLearningStatus = $('<input>').attr({'type': 'hidden', 'name': 'learning_status[]', 'value': inpLearningStatus});
						const hidAttendanceDetails = $('<input>').attr({'type': 'hidden', 'name': 'attendance_details[]', 'value': inpAttendanceDetails});

						$('#store-attendance-form').append(hidStudentId).append(hidStartTime).append(hidEndTime).append(hidActivityProgress).append(hidLearningStatus).append(hidAttendanceDetails);

						removeBtn.click(() => {
							if(confirm('Are you sure want to remove this student from the list?')){
                                hidStudentId.remove();
								hidStartTime.remove();
								hidEndTime.remove();
								hidActivityProgress.remove();
								hidLearningStatus.remove();
								hidAttendanceDetails.remove();

								newRow.remove();
							}
						});

						$('#tableBody').append(newRow);
					});

                    $('#todaybtn').on('click', function(){
                        const currentDate = new Date();
                        const year = currentDate.getFullYear();
                        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                        const day = String(currentDate.getDate()).padStart(2, '0');
                        $('#attendance_date').val(`${year}-${month}-${day}`);
                    });
				});
			</script>
		{{-- @else
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- This course still has no students or students assigned to it, or there are no more students to assign to course -</p>
				<x-button type="button" onclick="history.back()" class=" mt-4">
					Return
				</x-button>
			</div>
		@endif --}}
	</x-section-container>
@endsection