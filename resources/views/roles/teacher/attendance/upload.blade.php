@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.attendance.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Upload</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>New Attendance Report</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.attendance.store', $course->id) }}" method="post" id="attendance_form">
			@csrf
			<div class="my-4 flex w-full justify-between items-start">
				<div class="w-1/3">
					<x-label for="attendance_date">{{ __("Attendance Date") }}</x-label>
					<div class="flex gap-3 mt-1 items-start" id="date-inp-cont">
						<div class="flex flex-col items-start grow">
							<x-input type="date" class="date-input w-full" name="attendance_date" id="attendance_date"/>
							<p class="text-red font-bold mt-2 hidden" id="error-date"><i class="bi bi-exclamation-circle"></i> Attendance date field is required.</p>
						</div>
						<x-button type="button" id="todaybtn" class="mt-1">Today</x-button>
					</div>
				</div>
				<div class="w-1/3">
					<x-label for="student_add">{{ __("Add Student to Attendance") }}</x-label>
					<div class="flex items-center gap-3 mt-1 select-2_container">
						<select class="w-full select-2 rounded-2xl shadow-sm focus:outline-none py-2 px-4 focus:ring-0" id="student_add" style="border-width: 3px;">
						</select>
						<x-button type="button" id="add_student" >Add</x-button>
					</div>
				</div>
			</div>

			<h2 class="mt-12 font-extrabold text-xl text-dark-blue">Attendance Report</h2>
			@foreach ($students as $student)
				<div class="bg-white rounded-xl p-5 flex flex-col w-full my-6 student-card">
					<!-- Accordion trigger -->
					<button type="button" class="flex justify-between attendance-detail-accordion-btn items-center">
						<div class="flex items-center gap-3">
							@if($student->details->profpic)
								<img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-12 h-12 card_profpic" alt="profpic" style="object-fit: cover; object-position: center;">
							@else
								<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12 card_profpic" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
							@endif
							<span class="card_student_name">{{ $student->full_name }}</span>
						</div>
						<div class="accordion-icon"><i class="bi bi-chevron-up text-slate-600"></i></div>
					</button>

					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<!-- Accordion Area -->
					<div class="w-full flex flex-col attendance-detail-accordion-area">
						<!-- Is Attended -->
						<div class="w-full flex items-center justify-between">
							<label for="_checkbox{{ $student->id }}" class="flex gap-3 items-center mt-2">
								<input type="checkbox" id="_checkbox{{ $student->id }}" class="_checkbox form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" checked/>
								Is Attended
							</label>
							<button type="button" class="bg-red text-white rounded-xl hover:bg-slate-600 py-2 px-4 remove-student-btn" onclick="return confirm('Are you sure want to remove this student from the new attendance report?');" data-sid="{{ $student->id }}"><i class="bi bi-trash3"></i></button>
						</div>

						<div class="flex gap-5 mt-4 w-full container-session-present">
							<!-- Nth Session -->
							<div class="flex flex-col w-1/3 mt-4">
								<x-label for="_session{{ $student->id }}">N-th Session</x-label>
								<x-input class="_session" type="text" id="_session{{ $student->id }}" value="1"/>
								<p class="text-red font-bold mt-2 error-session hidden"><i class="bi bi-exclamation-circle"></i> Please input the nth-session.</p>
							</div>

							<!-- Start Time -->
							<div class="flex flex-col w-1/3 mt-4 start_time-container-present">
								<x-label for="_start_time{{ $student->id }}">Start Time</x-label>
								<x-input class="_start_time" type="time" id="_start_time{{ $student->id }}" value="00:00"/>
							</div>

							<!-- End Time -->
							<div class="flex flex-col w-1/3 mt-4 start_time-container-present">
								<x-label for="_end_time{{ $student->id }}">End Time</x-label>
								<x-input class="_end_time" type="time" id="_end_time{{ $student->id }}" value="00:00"/>
							</div>

							<!-- Start Time (Absent) -->
							<div class="hidden flex-col w-1/3 mt-4 start_time-container-absent">
								<x-label>Start Time</x-label>
								<x-input class="_start_time_absent" type="text" value="Absent" disabled/>
							</div>

							<!-- End Time (Absent) -->
							<div class="hidden flex-col w-1/3 mt-4 end_time-container-absent">
								<x-label>End Time</x-label>
								<x-input class="_end_time_absent" type="text" value="Absent" disabled/>
							</div>
						</div>

						<!-- Activity (Present) -->
						<div class="flex gap-5 mt-4 w-full container-activity-present">
							<div class="flex flex-col w-1/2 container-select2">
								<x-label for="_activity{{ $student->id }}">Activity</x-label>
								<x-select id="_activity{{ $student->id }}" class="select-2 w-full _activity">
									@foreach($course->topics as $topic)
										@foreach($topic->activities as $activity)
											<option value="{{ $activity }}">{{ $activity->title }}</option>
										@endforeach
									@endforeach
									<option value="Other">Other (Please specify in the attendance detail)</option>
								</x-select>
							</div>

							<div class="flex flex-col w-1/2">
								<x-label for="_learning_status{{ $student->id }}">Learning Status</x-label>
								<x-select id="_learning_status{{ $student->id }}" class="w-full _learning_status">
									<option value="Done">Done</option>
									<option value="On Progress">On Progress</option>
								</x-select>
							</div>
						</div>

						<!-- Activity (Absent) -->
						<div class="hidden gap-5 mt-4 w-full container-activity-absent">
							<div class="flex flex-col w-1/2">
								<x-label>Activity</x-label>
								<x-input class="w-full _activity_absent" value="Absent" disabled/>
							</div>

							<div class="flex flex-col w-1/2">
								<x-label>Learning Status</x-label>
								<x-input class="w-full _learning_status_absent" value="Absent" disabled/>
							</div>
						</div>

						<!-- Details -->
						<div class="flex flex-col w-full mt-4">
							<x-label for="_details{{ $student->id }}">Details</x-label>
							<x-textarea class="_details" rows="4" type="text" id="_details{{ $student->id }}" placeholder="Enter attendance details..."></x-textarea>
							<p class="text-red font-bold mt-2 error-details hidden"><i class="bi bi-exclamation-circle"></i> Please input the attendance details for this session.</p>
						</div>

						<!-- Add data to table -->
						<div class="flex justify-end mt-4">
							<x-button type="button" class="add-data-btn" data-student="{{ $student }}">Add Data</x-button>
						</div>

						<!-- Data summary table -->
						<div class="w-full overflow-x-auto mt-4">
							<table class="w-full">
								<thead>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">N-th Session</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Is Attended</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Status</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
									<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
								</thead>
								<tbody class="session-details-tbody">
									<tr class="bg-slate-100 empty-table-placeholder">
										<td colspan="6" class="py-2 px-4 text-center">- No attendance data inputted for this student -</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<p class="text-red font-bold error-card hidden"><i class="bi bi-exclamation-circle"></i> Please input minimum 1 attendance data for this student!</p>
			@endforeach

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3" id="button-area">
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button type="button" id="submit-btn" class="w-full md:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		const baseUrl = "{{ url('/') }}";
		const allStudents = @json($allStudents);
		let exclude_dropdown = @json($exclude_from_dropdown).map(Number);
		const course = @json($course);

		function refreshAddStudent(){
			$("#student_add").html("");
			if(exclude_dropdown.length < allStudents.length){
				for(let std of allStudents){
					if(!exclude_dropdown.includes(std.id)){
						$("#student_add").append($("<option>").attr({"value": JSON.stringify(std)}).text(std.full_name));
					}
				}
			}
			else {
				$("#student_add").append($("<option>").attr({"value": ""}).text("No more students can be added"));
			}
		}

		function reinitializeselect2(){
			// select-2 initialization
			$('.select-2').select2({
				allowClear: false
			});

			// Apply resize observer to each container with class 'container-select2'
			$('.container-select2').each(function () {
				const container = this;
				const resizeObserver = new ResizeObserver(() => {
					$(container).find('.select-2').each(function () {
						$(this).select2('destroy').select2({
							allowClear: false
						});
					});
				});

				resizeObserver.observe(container);
			});
		}

		$(document).ready(() => {
			refreshAddStudent();

			$(document).on('click', '.remove-student-btn', function(){
				const nCard = $('.student-card').length;
				if(nCard == 1){
					alert('Cannot delete the card since the attendance report needs minimum 1 student to be reported!');

					return;
				}

				const delId = $(this).data('sid');

				$(this).closest('.student-card').fadeOut(500, function(){
					$(this).remove();
					exclude_dropdown = exclude_dropdown.filter(item => item !== delId);

					refreshAddStudent();
				});
			});

			$(document).on('click', '.add-data-btn', function(){
				const currCard = $(this).closest('.attendance-detail-accordion-area');
				const _isAttended = currCard.find('._checkbox').is(':checked')? 'on' : 'off';
				const _nthSession = currCard.find('._session').val();
				const _start_time = currCard.find('._start_time').val();
				const _end_time = currCard.find('._end_time').val();
				const _activity = currCard.find('._activity').val();
				const _learning_status = currCard.find('._learning_status').val();
				const _details = currCard.find('._details').val();

				let input_error = false;

				currCard.find('.error-session').addClass('hidden');
				currCard.find('._session').removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
				currCard.find('.error-details').addClass('hidden');
				currCard.find('._details').removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');

				if(!_nthSession || _nthSession < 1){
					currCard.find('.error-session').removeClass('hidden');
					currCard.find('._session').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');

					input_error = true;
				}

				if(!_details || _details == ''){
					currCard.find('.error-details').removeClass('hidden');
					currCard.find('._details').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');

					input_error = true;
				}

				if(input_error){
					return;
				}

				if(currCard.find('.session-details-tbody').find('.empty-table-placeholder').length > 0){
					currCard.find('.session-details-tbody').empty();
				}

				let atdIcon = (_isAttended == 'on')? `<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">` : `<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">`;
				const delRowBtn = $('<button>').attr('type', 'button').addClass('bg-red text-white rounded-xl hover:bg-slate-600 py-2 px-4').html('<i class="bi bi-trash3"></i>');

				const rowCount = currCard.find('.session-details-tbody').find('tr').length;

				const newRow = $('<tr>').addClass(rowCount % 2 == 0? 'bg-slate-100' : '')
					.append(
						$('<td>').addClass('py-2 px-4').text((_isAttended == 'on')? `${_nthSession} (${_start_time}-${_end_time})` : `${_nthSession}`)
					).append(
						$('<td>').addClass('py-2 px-4').html(atdIcon)
					).append(
						$('<td>').addClass('py-2 px-4').text((_isAttended == 'on')? JSON.parse(_activity).title : 'Absent')
					).append(
						$('<td>').addClass('py-2 px-4').text((_isAttended == 'on')? _learning_status : 'Absent')
					).append(
						$('<td>').addClass('py-2 px-4').text(_details)
					).append(
						$('<td>').addClass('py-2 px-4').html(delRowBtn)
					);

				const student = $(this).data('student');
				const hidIsAttend = $('<input>').attr({'type': 'hidden', 'name': `is_attend[${student.id}][]`, 'value': _isAttended});
				const hidNthSession = $('<input>').attr({'type': 'hidden', 'name': `nth_session[${student.id}][]`, 'value': _nthSession});
				const hidStartTime = $('<input>').attr({'type': 'hidden', 'name': `start_time[${student.id}][]`, 'value': (_isAttended == 'on')?_start_time : '00:00'});
				const hidEndTime = $('<input>').attr({'type': 'hidden', 'name': `end_time[${student.id}][]`, 'value': (_isAttended == 'on')?_end_time : '00:00'});
				const hidActivity = $('<input>').attr({'type': 'hidden', 'name': `activity[${student.id}][]`, 'value': (_isAttended == 'on')? JSON.parse(_activity).title : 'Absent'});
				const hidLearningStatus = $('<input>').attr({'type': 'hidden', 'name': `learning_status[${student.id}][]`, 'value': (_isAttended == 'on')? _learning_status : 'Absent'});
				const hidDetails = $('<input>').attr({'type': 'hidden', 'name': `details[${student.id}][]`, 'value': _details});

				delRowBtn.on('click', () => {
					if(confirm('Are you sure want to remove this item?')){
						newRow.remove();

						hidIsAttend.remove();
						hidNthSession.remove();
						hidStartTime.remove();
						hidEndTime.remove();
						hidActivity.remove();
						hidLearningStatus.remove();
						hidDetails.remove();
					}
				});

				currCard.find('.session-details-tbody').append(newRow);
				currCard.append(hidIsAttend).append(hidNthSession).append(hidStartTime).append(hidEndTime).append(hidActivity).append(hidLearningStatus).append(hidDetails);
			});

			$(document).on('change', '.form-checkbox', function(){
				const currCard = $(this).closest('.attendance-detail-accordion-area');

				if($(this).is(':checked')){
					currCard.find('.container-activity-present').removeClass('hidden').addClass('flex');
					currCard.find('.container-activity-absent').removeClass('flex').addClass('hidden');
					currCard.find('.start_time-container-present').removeClass('hidden').addClass('flex');
					currCard.find('.start_time-container-absent').removeClass('flex').addClass('hidden');
					currCard.find('.end_time-container-present').removeClass('hidden').addClass('flex');
					currCard.find('.end_time-container-absent').removeClass('flex').addClass('hidden');
				}
				else {
					currCard.find('.container-activity-absent').removeClass('hidden').addClass('flex');
					currCard.find('.container-activity-present').removeClass('flex').addClass('hidden');
					currCard.find('.start_time-container-absent').removeClass('hidden').addClass('flex');
					currCard.find('.start_time-container-present').removeClass('flex').addClass('hidden');
					currCard.find('.end_time-container-absent').removeClass('hidden').addClass('flex');
					currCard.find('.end_time-container-present').removeClass('flex').addClass('hidden');
				}
			});

			$(document).on('click', '.attendance-detail-accordion-btn', function(){
				if($(this).parent().find('.attendance-detail-accordion-area').is(':visible')){
					$(this).find('.accordion-icon').html('<i class="bi bi-chevron-down text-slate-600"></i>');
				}
				else {
					$(this).find('.accordion-icon').html('<i class="bi bi-chevron-up text-slate-600"></i>');
				}
				$(this).parent().find('.attendance-detail-accordion-area').slideToggle();
			});

			$('#add_student').on('click', function(){
				const student = JSON.parse($("#student_add").val());
				const blankProfpic = "{{ asset('img/tempblankprofpic.png') }}";

				const newStudentCard = $('.student-card').first().clone();
				const newSelect = $('<select>').addClass('border-slate-400 focus:border-slate-600 focus:ring-0 rounded-2xl shadow-sm focus:outline-none py-2 px-4 cursor-pointer disabled:cursor-not-allowed select-2 w-full _activity').css('border-width', '3px').attr({'id': `_activity${student.id}`});
				course.topics.forEach(topic => {
					topic.activities.forEach(activity => {
						newSelect.append($('<option>').attr('value', JSON.stringify(activity)).text(activity.title));
					});
				});

				newStudentCard.find('input[type="hidden"]').remove();

				newStudentCard.find('.card_student_name').text(student.full_name);
				newStudentCard.find('.card_profpic').attr('src', ((student.details.profpic !== null)? `${baseUrl}/storage/${student.details.profpic}` : blankProfpic)).css('border-width', ((student.details.profpic !== null)? '3px' : '0'));

				newStudentCard.find('._checkbox').attr('id', `_checkbox${student.id}`).prop('checked', true);
				newStudentCard.find('._checkbox').closest('label').attr('for', `_checkbox${student.id}`);

				newStudentCard.find('._session').attr('id', `_session${student.id}`).val(1);
				newStudentCard.find('._session').prev().attr('for', `_session${student.id}`);

				newStudentCard.find('._start_time').attr('id', `_start_time${student.id}`).val('00:00');
				newStudentCard.find('._start_time').prev().attr('for', `_start_time${student.id}`);

				newStudentCard.find('._end_time').attr('id', `_end_time${student.id}`).val('00:00');
				newStudentCard.find('._end_time').prev().attr('for', `_end_time${student.id}`);

				newStudentCard.find('.container-select2').empty();
				newStudentCard.find('.container-select2').append($('<label>').attr('for', `_activity${student.id}`).text('Activity')).append(newSelect);

				newStudentCard.find('._learning_status').attr('id', `_learning_status${student.id}`).val('Done');
				newStudentCard.find('._learning_status').prev().attr('for', `_learning_status${student.id}`);

				newStudentCard.find('._details').attr('id', `_details${student.id}`).val('');
				newStudentCard.find('._details').prev().attr('for', `_details${student.id}`);

				newStudentCard.find('.session-details-tbody').html('');
				newStudentCard.find('.session-details-tbody').append(
					$('<tr>').addClass('bg-slate-100 empty-table-placeholder').append(
						$('<td>').attr('colspan', '6').addClass('py-2 px-4 text-center').text('- No attendance data inputted for this student -')
					)
				)
				newStudentCard.find('.add-data-btn').data('student', student);

				newStudentCard.insertBefore('#button-area');

				const cardError = $('<p>').addClass('text-red font-bold error-card hidden').html('<i class="bi bi-exclamation-circle"></i> Please input minimum 1 attendance data for this student!');

				cardError.insertBefore('#button-area');

				exclude_dropdown.push(student.id);

				reinitializeselect2();
				refreshAddStudent();
			});

			$('#todaybtn').on('click', function(){
				const currentDate = new Date();
				const year = currentDate.getFullYear();
				const month = String(currentDate.getMonth() + 1).padStart(2, '0');
				const day = String(currentDate.getDate()).padStart(2, '0');
				$('#attendance_date').val(`${year}-${month}-${day}`);
			});

			$('#submit-btn').on('click', function(){
				const nCard = $('.student-card').length;
				if(nCard == 0){
					alert('Cannot submit empty data!');
					return;
				}

				let dataEmpty = false;
				$('.student-card').each(function(){
					if($(this).find('input[type="hidden"]').length == 0){
						$(this).addClass('border-red');
						$(this).next().removeClass('hidden');

						dataEmpty = true;
					}
					else {
						$(this).removeClass('border-red');
						$(this).next().addClass('hidden');
					}
				});

				$('#error-date').addClass('hidden');
				$('#attendance_date').removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');

				if(!$('#attendance_date').val()){
					$('#error-date').removeClass('hidden');
					$('#attendance_date').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');

					dataEmpty = true;
				}

				if(dataEmpty){
					return;
				}
				else {
					$('form').submit();
				}

			});
		});
	</script>
@endsection
