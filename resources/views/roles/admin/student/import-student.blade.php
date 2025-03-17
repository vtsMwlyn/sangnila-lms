@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Import Student</span>
@endsection

@section("content")
	@if($course->teachers->count() && $course->topics->count() && $course->topics[0]->activities->count())
		<x-section-container>
			<x-page-title>Import Old Student Data</x-page-title>
			<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

			@if(session()->has("success"))
				<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
			@elseif(session()->has("warning"))
				<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
			@elseif(session()->has("danger"))
				<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
			@endif

			<x-badge-danger id="emptyDataNotif" badge_text="Please input minimum 1 data to proceed." style="display: none;"></x-badge-danger>

			<form action="#" id="le-form" class="mt-2">
				<div>
					<div class="flex items-center justify-between">
						<h1 class="font-bold text-lg text-blue">Student's Profile</h1>
						<x-button class=" w-1/2 md:w-1/6" id="show-details-button">Show Details</x-button>
					</div>

					<div class="flex flex-col md:flex-row gap-3 mt-3">
						{{-- Student Name --}}
						<div class="w-full md:w-1/2">
							<x-label for="full_name">Student Name<span class="text-red">*</span></x-label>
							<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" placeholder="Full Name" :value="old('full_name')" autofocus />
							<p class="text-red font-bold mt-1" style="display: none" id="err_full_name"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
						</div>

						<div class="w-full md:w-1/2 flex flex-col md:flex-row gap-3">
							{{-- Student Email --}}
							<div class="w-full md:w-2/3">
								<x-label for="email">Student Email<span class="text-red">*</span></x-label>
								<x-input id="email" class="block mt-1 w-full" type="text" name="email" placeholder="Student's Email" :value="old('email')"
									autofocus />
								<p class="text-red font-bold mt-1" style="display: none" id="err_email"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
							</div>
							{{-- Gender --}}
							<div class="w-full md:w-1/3">
								<x-label for="gender">Gender<span class="text-red">*</span></x-label>
								<x-select name="gender" id="gender"
								class="mt-1 w-full">
									<option value="" selected disabled>Select Gender
									<option value="1">Male</option>
									<option value="2">Female</option>
								</x-select>
								<p class="text-red font-bold mt-1" style="display: none" id="err_gender"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
							</div>
						</div>
					</div>

					<div class="" id="form-details" style="display: none;">
						<div class="flex flex-col md:flex-row gap-3 mt-3">
							{{-- Phone Number --}}
							<div class="w-full md:w-1/2">
								<x-label for="phone_number" :value="__('Phone Number')"/>
								<x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" placeholder="Add phone number"  />
							</div>

							<div class="w-full md:w-1/2 flex flex-col md:flex-row gap-3">
								{{-- City of Birth --}}
								<div class="w-full md:w-1/2">
									<x-label for="city_of_birth" :value="__('City of Birth')"/>
									<x-input id="city_of_birth" class="block mt-1 w-full" type="text" name="city_of_birth" :value="old('city_of_birth')" placeholder="Add city of birth"  />
								</div>

								{{-- Date of Birth --}}
								<div class="w-full md:w-1/2">
									<x-label for="date_of_birth" :value="__('Date of Birth')"/>
									<x-input id="date_of_birth" class="block mt-1 w-full" onfocus="this.type = 'date';" onblur="this.type = 'text'" name="date_of_birth" :value="old('date_of_birth')" placeholder="Add date of birth"  />
								</div>
							</div>
						</div>

						<div class="flex flex-col md:flex-row gap-3 mt-6">
							{{-- School Name --}}
							<div class="w-full md:w-1/2">
								<x-label for="school_name" :value="__('School Name')"/>
								<x-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="old('school_name')" placeholder="Add school name"  />
							</div>

							{{-- Education Level --}}
							<div class="w-full md:w-1/2">
								<x-label for="student_level" :value="__('Select Education Level')" />
								<x-select name="student_level" id="student_level"
								class="mt-1 w-full">
									<option value="" selected disabled>Add Education Level</option>
									@forelse ($education_levels as $level)
										<option value="{{ $level }}">{{ $level }}</option>
									@empty
									@endforelse
								</x-select>
							</div>
						</div>

						<div class="flex flex-col md:flex-row gap-3 mt-3">
							{{-- Parent's Name --}}
							<div class="w-full md:w-1/2">
								<x-label for="name_parent" :value="__('Parent\'s Name')"/>
								<x-input id="name_parent" class="block mt-1 w-full" type="text" name="name_parent" :value="old('name_parent')" placeholder="Add parent's name"  />
							</div>

							{{-- Parent's Phone Number --}}
							<div class="w-full md:w-1/2">
								<x-label for="phone_parent" :value="__('Parent\'s Phone Number')"/>
								<x-input id="phone_parent" class="block mt-1 w-full" type="text" name="phone_parent" :value="old('phone_parent')" placeholder="Add parent's phone number"  />
							</div>
						</div>
					</div>

					<div class="mt-5 flex items-center">
						<input type="checkbox" id="new_student" name="new_student" class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" checked>
						<label for="new_student" class="font-semibold text-blue-950">Add as a new student</label>
					</div>

					<h1 class="font-bold text-lg text-blue mt-12">Student's Course Data</h1>

					<div class="mt-4 flex flex-col md:flex-row gap-3">
						{{-- Student's Teacher --}}
						<div class="w-full md:w-1/2">
							<x-label for="teacher_name">Student's Teacher<span class="text-red">*</span></x-label>
							<x-select name="teacher_name" id="teacher_name" class="mt-1 w-full">
								<option value="" disabled selected>Pick a teacher</option>
								@foreach ($course->teachers as $teacher)
									<option value="{{ $teacher->toJson() }}">{{ $teacher->full_name }}</option>
								@endforeach
							</x-select>
							<p class="text-red font-bold mt-1" style="display: none" id="err_teacher_name"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
						</div>

						<div class="w-full md:w-1/2 flex gap-3">
							{{-- Last attendance count --}}
							<div class="w-1/2">
								<x-label for="last_attendance_count">Last Attendance Count<span class="text-red">*</span></x-label>
								<x-input id="last_attendance_count" class="block mt-1 w-full" type="number" name="last_attendance_count" placeholder="Last Attendance Count" value="0" />
								<p class="text-red font-bold mt-1" style="display: none" id="err_last_attendance_count"><i class="bi bi-exclamation-circle"></i> Invalid input.</p>
							</div>

							{{-- Student's max course session --}}
							<div class="w-1/2">
								<x-label for="max_course_session">Maximum Sessions<span class="text-red">*</span></x-label>
								<x-input id="max_course_session" class="block mt-1 w-full" type="number" name="max_course_session" placeholder="Maximum sessions" value="8" />
								<p class="text-red font-bold mt-1" style="display: none" id="err_max_course_session"><i class="bi bi-exclamation-circle"></i> Invalid input.</p>
							</div>
						</div>
					</div>

					{{-- Student's last activity unlocked --}}
					<div class="w-full mt-3 container-select2">
						<x-label for="last_activity_unlocked" class="mb-1">Last Activity Unlock<span class="text-red">*</span></x-label>
						<x-select name="last_activity_unlocked" id="last_activity_unlocked" class="mt-1 w-full select-2">
						</x-select>
						<p class="text-red font-bold mt-1" style="display: none" id="err_last_activity_unlocked"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
					</div>
					<div class="flex w-full justify-end">
						<x-button class=" mt-8 w-1/2 md:w-1/6" type="submit" id="addBtn">Add Data</x-button>
					</div>
				</div>
			</form>

			<div class="mt-12 mb-6">
				<h1 class="font-bold text-lg text-blue">Students to Import</h1>

				<div class="overflow-x-auto w-full mt-4">
					<table class="w-full" id="student-to-import">
						<thead>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Teacher</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Session</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Last Activity</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
						</thead>
						<tbody id="table-body">
							<tr class="bg-white" id="empty-placeholder">
								<td colspan="8" class="p-4 text-center">- No data yet -</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="flex w-full items-center gap-3 justify-end mt-8">
					<x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
					<x-button type="button" class="w-1/2 md:w-1/6" id="import-data-btn">Import Data</x-button>
				</div>

				<form class="w-full" action="{{ route("admin.course.import-student-data.store", $course->id) }}" method="post" id="real-form">
					@csrf
				</form>
			</div>

			<script>
				$(document).ready(() => {
					function resetActivitySelection(){
						$("#last_activity_unlocked").empty();

						$("#last_activity_unlocked").append($("<option>").text("Select an activity (Pick teacher first)").attr({
							"value": "",
							"disabled": true,
							"selected": true
						}));
					}

					resetActivitySelection();

					$("#teacher_name").on("change", function() {
						resetActivitySelection();

						const course = @json($course);
						const teacher = JSON.parse($("#teacher_name").val());

						course.topics.forEach(topic => {
							if(topic.user_id == teacher.id){
								topic.activities.forEach(activity => {
									$("#last_activity_unlocked").append($("<option>").text(`${topic.title} - ${activity.title}`).attr({"value": JSON.stringify(activity)}));
								});
							}
						});
					});

					let import_data_count = 0;

					$("#date_of_birth").on({
						"focus": function(){
							this.showPicker();
						},
						"click": function(){
							this.showPicker();
						}
					});

					$("#show-details-button").click((e) => {
						e.preventDefault();

						$("#form-details").slideToggle(() => {
							if ($("#form-details").is(":visible")) {
								$("#show-details-button").text("Hide Details");
							} else {
								$("#show-details-button").text("Show Details");
							}
						});
					});

					$("#addBtn").click((e) => {
						e.preventDefault();

						// Checkbox value
						const cbv = $('input[type="checkbox"]').is(":checked")? "Yes" : "No";

						// Validations
						const inpFullName = $("#full_name");
						const inpEmail = $("#email");
						const inpGender = $("#gender");
						const inpTeacherName = $("#teacher_name");
						const inpLastAttendanceCount = $("#last_attendance_count");
						const inpMaxCourseSession = $("#max_course_session");
						const inpLastActivityUnlocked = $("#last_activity_unlocked");

						const inpPhoneNumber = $('#phone_number');
						const inpCOB = $('#city_of_birth');
						const inpDOB = $('#date_of_birth');
						const inpSchoolName = $('#school_name');
						const inpStudentLevel = $('#student_level');
						const inpNameParent = $('#name_parent');
						const inpPhoneParent = $('#phone_parent');

						const errFullName = $("#err_full_name");
						const errEmail = $("#err_email");
						const errGender = $("#err_gender");
						const errTeacherName = $("#err_teacher_name");
						const errLastAttendanceCount = $("#err_last_attendance_count");
						const errCourseMaxSession = $("#err_max_course_session");
						const errLastActivityUnlocked = $("#err_last_activity_unlocked");

						inpFullName.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						inpEmail.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						inpGender.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						inpTeacherName.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						inpLastAttendanceCount.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						inpMaxCourseSession.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');
						inpLastActivityUnlocked.addClass('border-slate-400 focus:border-slate-600 focus:ring-0').removeClass('border-red focus:border-red-700 focus:ring-0');

						errFullName.hide();
						errEmail.hide();
						errGender.hide();
						errTeacherName.hide();
						errLastAttendanceCount.hide();
						errCourseMaxSession.hide();
						errLastActivityUnlocked.hide();

						let thereAreUnfilledFields = false;
						if(!inpFullName.val()){
							inpFullName.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errFullName.show();
							thereAreUnfilledFields = true;
						}

						if(!inpEmail.val()){
							inpEmail.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errEmail.show();
							thereAreUnfilledFields = true;
						}

						if(!inpGender.val()){
							inpGender.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errGender.show();
							thereAreUnfilledFields = true;
						}

						if(!inpTeacherName.val()){
							inpTeacherName.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errTeacherName.show();
							thereAreUnfilledFields = true;
						}

						if(!inpLastAttendanceCount.val() || inpLastAttendanceCount.val() < 0){
							inpLastAttendanceCount.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errLastAttendanceCount.show();
							thereAreUnfilledFields = true;
						}

						if(!inpMaxCourseSession.val() || inpMaxCourseSession.val() < 1){
							inpMaxCourseSession.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errCourseMaxSession.show();
							thereAreUnfilledFields = true;
						}

						if(!inpLastActivityUnlocked.val()){
							inpLastActivityUnlocked.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
							errLastActivityUnlocked.show();
							thereAreUnfilledFields = true;
						}

						if(thereAreUnfilledFields){
							return;
						}

						// Generate new row of data to show in the table
						const delBtn = $("<button>").attr({"type": "button"}).addClass("text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150").html("<i class='bi bi-trash3'></i>");

						const newRow = $("<tr>")
							.append(
								$('<td>').addClass('py-3 px-4').html(`${inpFullName.val()} (${inpGender.val() == 1? 'Male' : 'Female'})<br>${inpEmail.val()}<br>(${cbv == 'Yes'? 'New Student' : ''})`)
							).append(
								$('<td>').addClass('py-3 px-4').text(JSON.parse(inpTeacherName.val()).full_name)
							).append(
								$('<td>').addClass('py-3 px-4').text(`${inpLastAttendanceCount.val()}/${inpMaxCourseSession.val()}`)
							).append(
								$('<td>').addClass('py-3 px-4').text(JSON.parse(inpLastActivityUnlocked.val()).title)
							).append(
								$('<td>').addClass('py-3 px-4').append(
									$('<ol>')
										.append(
											$('<li>').text(`Phone number: ${inpPhoneNumber.val()}`)
										)
										.append(
											$('<li>').text(`City of birth: ${inpCOB.val()}`)
										)
										.append(
											$('<li>').text(`Date of birth: ${inpDOB.val()}`)
										)
										.append(
											$('<li>').text(`School name: ${inpSchoolName.val()}`)
										)
										.append(
											$('<li>').text(`Education level: ${inpStudentLevel.val()}`)
										)
										.append(
											$('<li>').text(`Parent name: ${inpNameParent.val()}`)
										)
										.append(
											$('<li>').text(`Parent phone: ${inpPhoneParent.val()}`)
										)
								)
							).append(
								$('<td>').addClass('py-3 px-4').append(delBtn)
							);


						const tableBody = $('#table-body');
						if(tableBody.find('#empty-placeholder').length > 0){
							tableBody.html('');
						}

						if(tableBody.find('tr').length % 2 == 0){
							newRow.addClass('bg-white');
						}

						tableBody.append(newRow);

						// Generate hidden inputs to help send data to Laravel
						const hiddenInputsContainer = $("<div>");

						const hidFullName = $("<input>").attr({"type": "hidden", "name": "inp_full_name[]", "value": inpFullName.val()});
						const hidEmail = $("<input>").attr({"type": "hidden", "name": "inp_email[]", "value": inpEmail.val()});
						const hidGender = $("<input>").attr({"type": "hidden", "name": "inp_gender[]", "value": inpGender.val()});
						const hidTeacherName = $("<input>").attr({"type": "hidden", "name": "inp_teacher_name[]", "value": JSON.parse(inpTeacherName.val()).id});
						const hidLastAttendanceCount = $("<input>").attr({"type": "hidden", "name": "inp_last_attendance_count[]", "value": inpLastAttendanceCount.val()});
						const hidMaxCourseSession = $("<input>").attr({"type": "hidden", "name": "inp_max_course_session[]", "value": inpMaxCourseSession.val()});
						const hidLastActivityUnlocked = $("<input>").attr({"type": "hidden", "name": "inp_last_activity_unlocked[]", "value": JSON.parse(inpLastActivityUnlocked.val()).id});
						const hidNewStudent = $("<input>").attr({"type": "hidden", "name": "is_new_student[]", "value": cbv});

						const hidPhoneNumber = $("<input>").attr({"type": "hidden", "name": "inp_phone_number[]", "value": inpPhoneNumber.val()});
						const hidCityOfBirth = $("<input>").attr({"type": "hidden", "name": "inp_cob[]", "value": inpCOB.val()});
						const hidDateOfBirth = $("<input>").attr({"type": "hidden", "name": "inp_dob[]", "value": inpDOB.val()});
						const hidSchoolName = $("<input>").attr({"type": "hidden", "name": "inp_school_name[]", "value": inpSchoolName.val()});
						const hidStudentLevel = $("<input>").attr({"type": "hidden", "name": "inp_student_level[]", "value": inpStudentLevel.val()});
						const hidNameParent = $("<input>").attr({"type": "hidden", "name": "inp_name_parent[]", "value": inpNameParent.val()});
						const hidPhoneParent = $("<input>").attr({"type": "hidden", "name": "inp_phone_parent[]", "value": inpPhoneParent.val()});

						$(hiddenInputsContainer).append(hidFullName, hidEmail, hidGender, hidTeacherName, hidLastAttendanceCount, hidMaxCourseSession, hidLastActivityUnlocked, hidNewStudent, hidPhoneNumber, hidCityOfBirth, hidDateOfBirth, hidSchoolName, hidStudentLevel, hidNameParent, hidPhoneParent);
						$("#real-form").append(hiddenInputsContainer);

						$(delBtn).click(() => {
							if(confirm("Are you sure want to remove this student from the list?")){
								$(newRow).remove();
								$(hiddenInputsContainer).remove();
								import_data_count--;
							}
						});

						// Empty the input field
						$("#full_name").val("");
						$("#email").val("");
						$("#gender").val("");
						$("#phone_number").val("");
						$("#city_of_birth").val("");
						$("#date_of_birth").val("");
						$("#school_name").val("");
						$("#student_level").val("");
						$("#name_parent").val("");
						$("#phone_parent").val("");
						$("#last_attendance_count").val("0");
						$("#max_course_session").val("8");
						$("#last_activity_unlocked").val("");

						import_data_count++;
					});

					$('#import-data-btn').on('click', function(){
						if(import_data_count == 0){
							$("#emptyDataNotif").css("display", "flex");
							$("html, body").scrollTop(0);

							return;
						}
						else {
							$("#real-form").submit();
						}
					});
				});
			</script>

		</x-section-container>

	@else
		<x-section-container>
			<x-page-title>Import Old Student Data</x-page-title>
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- This course still has no teachers assigned or topic and activities added to it -</p>
				<x-button type="button" onclick="history.back()" class=" mt-4">
					Return
				</x-button>
			</div>
		</x-section-container>
	@endif

@endsection
