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
					<div class="flex gap-3 mt-1 items-center" id="date-inp-cont">
						<div class="flex flex-col items-start grow">
							<x-input type="date" class="date-input w-full" name="attendance_date" id="attendance_date"/>
						</div>
						<x-button type="button" id="todaybtn" >Today</x-button>
					</div>
				</div>
				<div class="w-1/3">
					<x-label for="student_add">{{ __("Add Student to Attendance") }}</x-label>
					<div class="flex items-center gap-3 mt-1 select2_container">
						<select class="w-full select2 rounded-2xl shadow-sm focus:outline-none py-2 px-4 focus:ring-0" id="student_add" style="border-width: 3px;">
						</select>
						<x-button type="button" id="add_student" >Add</x-button>
					</div>
				</div>
			</div>

			<h2 class="mt-12 font-extrabold text-xl text-dark-blue">Attendance Report</h2>
			@foreach ($students as $student)
				<div class="bg-white rounded-xl p-5 flex flex-col w-full my-6">
					<!-- Accordion trigger -->
					<button type="button" class="flex justify-between attendance-detail-accordion-btn items-center">
						<div class="flex items-center gap-3">
							@if($student->details->profpic)
								<img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
							@else
								<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
							@endif
							{{ $student->full_name }}
						</div>
						<div class="accordion-icon"><i class="bi bi-chevron-up text-slate-600"></i></div>
					</button>

					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<!-- Accordion Area -->
					<div class="w-full flex flex-col attendance-detail-accordion-area">
						<!-- Is Attended -->
						<div class="w-full flex items-center justify-between">
							<label for="_checkbox{{ $loop->iteration }}" class="flex gap-3 items-center mt-2">
								<input class="_checkbox" type="checkbox" id="_checkbox{{ $loop->iteration }}" class="form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" checked/>
								Is Attended
							</label>
							<button type="button" class="bg-red text-white rounded-xl hover:bg-slate-600 py-2 px-4" onclick="return confirm('Are you sure want to remove this student from the new attendance report?');"><i class="bi bi-trash3"></i></button>
						</div>

						<!-- Nth Session -->
						<div class="flex flex-col w-full mt-4">
							<x-label for="_session{{ $loop->iteration }}">N-th Session</x-label>
							<x-input class="_session" type="text" id="_session{{ $loop->iteration }}" value="1"/>
							<p class="text-red font-bold mt-2 error-session hidden"><i class="bi bi-exclamation-circle"></i> Please input a valid value.</p>
						</div>

						<!-- Activity (Present) -->
						<div class="flex gap-5 mt-4 w-full container-activity-present">
							<div class="flex flex-col w-1/2 container_select2">
								<x-label for="_activity{{ $loop->iteration }}">Activity</x-label>
								<x-select id="_activity{{ $loop->iteration }}" class="select2 w-full _activity">
									@foreach($course->topics as $topic)
										@foreach($topic->activities as $activity)
											<option value="{{ $activity }}">{{ $activity->title }}</option>
										@endforeach
									@endforeach
									<option value="Other">Other (Please specify in the attendance detail)</option>
								</x-select>
							</div>

							<div class="flex flex-col w-1/2">
								<x-label for="_learning_status{{ $loop->iteration }}">Learning Status</x-label>
								<x-select type="text" id="_learning_status{{ $loop->iteration }}" class="w-full _learning_status" value="1">
									<option value="Done">Done</option>
									<option value="On Progress">On Progress</option>
								</x-select>
							</div>
						</div>

						<!-- Activity (Absent) -->
						<div class="flex gap-5 mt-4 w-full container-activity-absent">
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
							<x-label for="_details{{ $loop->iteration }}">Details</x-label>
							<x-textarea class="_details" rows="4" type="text" id="_details{{ $loop->iteration }}" placeholder="Enter attendance details..."></x-textarea>
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
								</tbody>
							</table>
						</div>
					</div>
				</div>
			@endforeach

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		const allStudents = @json($allStudents);
		let exclude_dropdown = @json($exclude_from_dropdown).map(Number);

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

		refreshAddStudent();

		$('#add_student').on('click', function(){
			console.log(JSON.parse($("#student_add").val()));
		});

		$('.add-data-btn').on('click', function(){
			const currCard = $(this).closest('.attendance-detail-accordion-area');
			const _isAttended = currCard.find('._checkbox').is(':checked')? 'on' : 'off';
			const _nthSession = currCard.find('._session').val();
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

			let atdIcon = (_isAttended == 'on')? `<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">` : `<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">`;
			const delRowBtn = $('<button>').attr('type', 'button').addClass('bg-red text-white rounded-xl hover:bg-slate-600 py-2 px-4').html('<i class="bi bi-trash3"></i>');

			const newRow = $('<tr>')
				.append(
					$('<td>').addClass('py-2 px-4').text(_nthSession)
				).append(
					$('<td>').addClass('py-2 px-4').html(atdIcon)
				).append(
					$('<td>').addClass('py-2 px-4').text(JSON.parse(_activity).title)
				).append(
					$('<td>').addClass('py-2 px-4').text(_learning_status)
				).append(
					$('<td>').addClass('py-2 px-4').text(_details)
				).append(
					$('<td>').addClass('py-2 px-4').html(delRowBtn)
				);

			const student = $(this).data('student');
			const hidIsAttend = $('<input>').attr({'type': 'hidden', 'name': `is_attend[${student.id}][]`, 'value': _isAttended});
			const hidNthSession = $('<input>').attr({'type': 'hidden', 'name': `nth_session[${student.id}][]`, 'value': _nthSession});
			const hidActivity = $('<input>').attr({'type': 'hidden', 'name': `activity[${student.id}][]`, 'value': JSON.parse(_activity).title});
			const hidLearningStatus = $('<input>').attr({'type': 'hidden', 'name': `learning_status[${student.id}][]`, 'value': _learning_status});
			const hidDetails = $('<input>').attr({'type': 'hidden', 'name': `details[${student.id}][]`, 'value': _details});

			delRowBtn.on('click', () => {
				if(confirm('Are you sure want to remove this item?')){
					newRow.remove();

					hidIsAttend.remove();
					hidNthSession.remove();
					hidActivity.remove();
					hidLearningStatus.remove();
					hidDetails.remove();
				}
			});

			currCard.find('.session-details-tbody').append(newRow);
			currCard.append(hidIsAttend).append(hidNthSession).append(hidActivity).append(hidLearningStatus).append(hidDetails);
		});

		$('.attendance-detail-accordion-btn').on('click', function(){
			if($(this).parent().find('.attendance-detail-accordion-area').is(':visible')){
				$(this).find('.accordion-icon').html('<i class="bi bi-chevron-down text-slate-600"></i>');
			}
			else {
				$(this).find('.accordion-icon').html('<i class="bi bi-chevron-up text-slate-600"></i>');
			}
			$(this).parent().find('.attendance-detail-accordion-area').slideToggle();
		});
	</script>
@endsection
