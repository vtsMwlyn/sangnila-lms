@extends("layouts.main-teacher")

@section("title")
	<h1>Attendance</h1>
@endsection

@section('content')
    <x-section-container>
		<x-page-title>New Substitution Attendance Report</x-page-title>
		<h1 class="font-bold text-lg text-blue mt-1">{{ $student->full_name }}</h1>
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
			<form action="{{ route('teacher.attendance.substitution.store', $student->id) }}" method="post" class="my-4" id="store-attendance-form">
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
                                <x-label class="mb-1">Course<span class="text-red">*</span></x-label>
                                <x-select type="text" name="_course_name" id="_course_name" class="w-full select-2">
									<option disabled selected>Select a Course</option>
                                    @foreach($courses as $course)
										<option value="{{ $course }}">{{ $course->course_name }} - {{ ucwords($course->level) }}</option>
									@endforeach
                                </x-select>
                                <input type="hidden" name="course_id" id="course_id">
                            </div>
                            <div class="w-full md:w-1/2"></div>
						</div>

						<div class="flex gap-5 w-full mt-4 attendance-detail-fields">
							{{-- Start Time --}}
							<div class="flex flex-col w-1/2">
								<x-label for="_start_time">Start Time<span class="text-red">*</span></x-label>
								<x-input class="_start_time" type="time" id="_start_time" value="00:00"/>
								<p class="text-red font-bold mt-2 hidden error-time"><i class="bi bi-exclamation-circle"></i> Invalid learning time range.</p>
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
									<option value="Other">Other (Please specify on the details)</option>
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
							<x-textarea rows="4" name="_attendance_details" id="_attendance_details" class="w-full" placeholder="Input details"></x-textarea>
							<p class="text-red font-bold mt-2 hidden" id="error-attendance-detail"><i class="bi bi-exclamation-circle"></i> Please input learning details.</p>
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
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
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
					<x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
					<x-button class=" w-1/2 md:w-1/6">Submit</x-button>
				</div>
			</form>

			<script>
				const courseTopicsAndActivities = @json($courses);

				$(document).ready(function() {
					$('#_course_name').on('change', function(){
						$('#_activity_progress').empty();

						const inpCourse = JSON.parse($('#_course_name').val());

						const targettedCourse = courseTopicsAndActivities.find(ctaa => ctaa.id === inpCourse.id);
						console.log(targettedCourse);

						targettedCourse.topics.forEach(topic => {
							topic.activities.forEach(activity => {
								$('#_activity_progress').append(
									$('<option>').attr('value', activity.title).text(activity.title)
								)
							});
						});

						$('#_activity_progress').append(
							$('<option>').attr('value', 'Other').text('Other (please specify on the details)')
						);
					});

					$('#addBtn').on('click', function(){
                        const inpCourse = JSON.parse($('#_course_name').val());

						const _inpActivityProgress = $('#_activity_progress').val();
						const inpLearningStatus = $('#_learning_status').val();
						const inpAttendanceDetails = $('#_attendance_details').val();
						let inpStartTime = $('#_start_time').val();
						let inpEndTime = $('#_end_time').val();

						$('#_start_time').addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						$('#_end_time').addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						$('.error-time').hide();

						$('#_attendance_details').addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						$('#error-attendance-detail').hide();

						let invalid = false;

						// convert to Date objects (or just minutes)
						const [h1, m1] = inpStartTime.split(":").map(Number);
						const [h2, m2] = inpEndTime.split(":").map(Number);

						// compare by total minutes
						const stime = h1 * 60 + m1;
						const etime = h2 * 60 + m2;

						if(inpStartTime == '00:00' && inpEndTime == '00:00' || etime < stime){
							$('#_start_time').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							$('#_end_time').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							$('.error-time').show();

							invalid = true;
						}

						if(!inpAttendanceDetails){
							$('#_attendance_details').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							$('#error-attendance-detail').show();

							invalid = true;
						}

						if(invalid){
							return;
						}

						const inpActivityProgress = _inpActivityProgress;

						if($('#tableBody').find('#empty-table-placeholder').length){
							$('#empty-table-placeholder').remove();
						}

						const newRow = $('<tr>');
						const removeBtn = $('<button>').html('<i class="bi bi-trash3"></i>').attr('type', 'button').addClass('text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150');

                        const rowCount = $('#tableBody').find('tr').length;

						const sanitizedAttendanceDetails = $('<span>').text(inpAttendanceDetails);

						const courseLevel = inpCourse.level.charAt(0).toUpperCase() + inpCourse.level.slice(1);

						newRow.addClass(rowCount % 2 == 1? 'bg-white' : '')
							.append(
								$('<td>').addClass('py-3 px-4').text(`${inpCourse.course_name} - ${courseLevel}`)
                            ).append(
								$('<td>').addClass('py-3 px-4').html(`${inpStartTime}-${inpEndTime}`)
							).append(
								$('<td>').addClass('py-3 px-4').html(`${inpActivityProgress}<br>(${inpLearningStatus})<br><br>`).append(sanitizedAttendanceDetails)
							).append(
								$('<td>').addClass('py-3 px-4').append(removeBtn)
							);

                        const hidCourseId = $('<input>').attr({'type': 'hidden', 'name': 'course_id[]', 'value': inpCourse.id});
						const hidStartTime = $('<input>').attr({'type': 'hidden', 'name': 'start_time[]', 'value': inpStartTime});
						const hidEndTime = $('<input>').attr({'type': 'hidden', 'name': 'end_time[]', 'value': inpEndTime});
						const hidActivityProgress = $('<input>').attr({'type': 'hidden', 'name': 'activity_progress[]', 'value': inpActivityProgress});
						const hidLearningStatus = $('<input>').attr({'type': 'hidden', 'name': 'learning_status[]', 'value': inpLearningStatus});
						const hidAttendanceDetails = $('<input>').attr({'type': 'hidden', 'name': 'attendance_details[]', 'value': inpAttendanceDetails});

						$('#store-attendance-form').append(hidCourseId).append(hidStartTime).append(hidEndTime).append(hidActivityProgress).append(hidLearningStatus).append(hidAttendanceDetails);

						removeBtn.click(() => {
							if(confirm('Are you sure want to remove this student from the list?')){
                                hidCourseId.remove();
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