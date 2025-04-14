@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

{{-- @section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Batch Assign</span>
@endsection --}}

@section("content")
	<x-section-container>
		<x-page-title>Input Student Attendances</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">{{ $student->full_name }}</h1>
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
			<form action="{{ route('admin.student.store-attendance', $student->id) }}" method="post" class="my-4">
				@csrf

				<div id="form-area">
					{{-- Class data --}}
					<h1 class="font-bold text-lg text-blue">Class Data</h1>
					<div class="flex gap-5">
						<div class="mt-3 w-full md:w-1/2">
							<x-label class="mb-1">Course Name<span class="text-red">*</span></x-label>
							<x-select name="_course_name" id="_course_name" class="w-full">
								<option disabled selected>Pick a course</option>
								@foreach ($student->enrolled_courses as $c)
									<option value="{{ App\Models\CourseStudent::where('course_id', $c->id)->where('student_id', $student->id)->where('learning_status', 'learning')->with(['teacher'])->with('course', function($query){
										return $query->select('id', 'course_name');
									})->first() }}">{{ $c->course_name }} - {{ ucwords($c->level) }}</option>
								@endforeach
							</x-select>
							<p class="text-red font-bold mt-2 hidden" id="error-course"><i class="bi bi-exclamation-circle"></i> Please pick a course.</p>
						</div>
						<div class="mt-3 w-full md:w-1/2">
							<x-label class="mb-1">Teacher<span class="text-red">*</span></x-label>
							<x-input type="text" name="_teacher_name" id="_teacher_name" class="w-full" disabled value="Pick a course first"/>
							<input type="hidden" name="teacher_id" id="teacher_id">
						</div>
					</div>

					{{-- Attendance data --}}
					<h1 class="font-bold text-lg text-blue mt-8">Attendance Data</h1>
					<div class="flex flex-col w-full">
						<div class="w-full flex gap-5 mt-3">
							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Attendance Date<span class="text-red">*</span></x-label>
								<x-input type="date" name="_attendance_date" id="_attendance_date" class="w-full date-input" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"/>
							</div>
							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Attendance Status<span class="text-red">*</span></x-label>
								<x-select name="_is_attended" id="_is_attended" class="w-full">
									<option value="1">Attended</option>
									<option value="0">Absent</option>
								</x-select>
							</div>
							{{-- <div class="flex flex-col w-full md:w-1/3">
								<x-label class="mb-1" for="_session">N-th Session</x-label>
								<x-input class="_session" type="text" id="_session" value="1"/>
								<p class="text-red font-bold mt-2 error-session hidden"><i class="bi bi-exclamation-circle"></i> Please input the nth-session.</p>
							</div> --}}
						</div>

						<div class="flex gap-5 w-full mt-6 attendance-detail-fields">
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
									<option disabled selected>Pick a course first</option>
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
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date & Time</th>
							{{-- <th class="text-start py-3 px-4 border-b-2 border-slate-400">Session</th> --}}
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attended</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
						</thead>
						<tbody id="tableBody">
							<tr class="bg-white" id="empty-placeholder">
								<td colspan="5" class="p-4 text-center" id="empty-table-placeholder">- No data yet -</td>
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
				const allCoursesWithTopicsAndActivities = @json($allCoursesWithTopicsAndActivities);

				$(document).ready(function() {
					$('#_course_name').on('change', function(){
						const classData = JSON.parse($(this).val());
						// console.log(classData);

						$('#_teacher_name').val(classData.teacher.full_name);
						$('#teacher_id').val(classData.teacher.id);
						$('#course_id').val(classData.course_id);

						const filteredCourses = allCoursesWithTopicsAndActivities.filter(acwtaa => acwtaa.id === classData.course_id);
						const course = filteredCourses[0];

						$('#_activity_progress').html('');

						if (course) {
							// Filter topics based on the teacher's user_id
							const filteredTopics = course.topics.filter(topic => topic.user_id === classData.teacher.id);

							// Loop through each topic and its activities
							filteredTopics.forEach(topic => {
								topic.activities.forEach(activity => {
									$('#_activity_progress').append($('<option>').attr('value', JSON.stringify(activity)).text(activity.title));
								});
							});

							// Add the "Other" option at the end
							$('#_activity_progress').append($('<option>').attr('value', 'Other').text('Other'));
						}

					});

					$('#_is_attended').on('change', function(){
						if($(this).val() == 0){
							$('.attendance-detail-fields').hide();
						}
						else {
							$('.attendance-detail-fields').show();
						}
					});

					$('#addBtn').on('click', function(){
						const inpCourse = JSON.parse($('#_course_name').val());

						$('#_course_name').addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						$('#error-course').addClass('hidden');

						if(!inpCourse){
							$('#_course_name').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							$('#error-course').removeClass('hidden');

							$(window).scrollTop($('#_course_name').offset().top - 100);

							return;
						}

						const inpDate = $('#_attendance_date').val();
						// const inpSession = $('#_session').val();
						const inpIsAttended = $('#_is_attended').val();
						const _inpActivityProgress = $('#_activity_progress').val();
						const inpLearningStatus = $('#_learning_status').val();
						const inpAttendanceDetails = $('#_attendance_details').val();
						let inpStartTime = $('#_start_time').val();
						let inpEndTime = $('#_end_time').val();

						const inpActivityProgress = (_inpActivityProgress == 'Other') ? 'Other' : JSON.parse(_inpActivityProgress).title;

						if(inpIsAttended == 0){
							inpStartTime = '00:00';
							inpEndTime = '00:00';
						}

						if($('#tableBody').find('#empty-table-placeholder').length){
							$('#empty-table-placeholder').remove();
						}

						const newRow = $('<tr>');
						const removeBtn = $('<button>').html('<i class="bi bi-trash3"></i>').attr('type', 'button').addClass('text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150');

						let atdIcon = (inpIsAttended == 1)? `<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">` : `<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">`;
						const rowCount = $('#tableBody').find('tr').length;

						newRow.addClass(rowCount % 2 == 1? 'bg-white' : '').append(
								$('<td>').addClass('py-3 px-4').text(inpCourse.course.course_name)
							).append(
								$('<td>').addClass('py-3 px-4').html(inpIsAttended == 0? `${inpDate}<br>Absent` : `${inpDate}<br>${inpStartTime}-${inpEndTime}`)
							).append(
								$('<td>').addClass('py-3 px-4').html(atdIcon)
							).append(
								$('<td>').addClass('py-3 px-4').html(inpIsAttended == 1? `${inpActivityProgress}<br><br>(${inpLearningStatus})<br>${inpAttendanceDetails}` : `Absent<br>${inpAttendanceDetails}`)
							).append(
								$('<td>').addClass('py-3 px-4').append(removeBtn)
							);

						const hidCourseId = $('<input>').attr({'type': 'hidden', 'name': 'course_id[]', 'value': inpCourse.course.id});
						// const hidSession = $('<input>').attr({'type': 'hidden', 'name': 'session[]', 'value': inpSession});
						const hidDate = $('<input>').attr({'type': 'hidden', 'name': 'attendance_date[]', 'value': inpDate});
						const hidStartTime = $('<input>').attr({'type': 'hidden', 'name': 'start_time[]', 'value': inpStartTime});
						const hidEndTime = $('<input>').attr({'type': 'hidden', 'name': 'end_time[]', 'value': inpEndTime});
						const hidIsAttended = $('<input>').attr({'type': 'hidden', 'name': 'is_attended[]', 'value': inpIsAttended});
						const hidActivityProgress = $('<input>').attr({'type': 'hidden', 'name': 'activity_progress[]', 'value': inpIsAttended == 1? inpActivityProgress : 'Absent'});
						const hidLearningStatus = $('<input>').attr({'type': 'hidden', 'name': 'learning_status[]', 'value': inpIsAttended == 1? inpLearningStatus : 'Absent'});
						const hidAttendanceDetails = $('<input>').attr({'type': 'hidden', 'name': 'attendance_details[]', 'value': inpAttendanceDetails});

						$('form').append(hidCourseId).append(hidDate).append(hidStartTime).append(hidEndTime).append(hidIsAttended).append(hidActivityProgress).append(hidLearningStatus).append(hidAttendanceDetails);

						removeBtn.click(() => {
							if(confirm('Are you sure want to remove this student from the list?')){
								hidCourseId.remove();
								// hidSession.remove();
								hidDate.remove();
								hidStartTime.remove();
								hidEndTime.remove();
								hidIsAttended.remove();
								hidActivityProgress.remove();
								hidLearningStatus.remove();
								hidAttendanceDetails.remove();

								newRow.remove();
							}
						});

						$('#tableBody').append(newRow);
					});
				});
			</script>
		{{-- @else
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- This course still has no teachers or students assigned to it, or there are no more students to assign to course -</p>
				<x-button type="button" onclick="history.back()" class=" mt-4">
					Return
				</x-button>
			</div>
		@endif --}}
	</x-section-container>
@endsection
