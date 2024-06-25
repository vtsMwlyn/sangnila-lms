@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">Import Old Student Data</x-page-title>

		@if($course->teachers->count() && $course->topics->count() && $course->topics[0]->materials->count())
			<form action="#" id="le-form">
				<div class="border rounded-xl p-5 my-5 bg-white border-blue-800">
					<div class="flex w-full items-center justify-between mb-3">
						<h1 class="font-semibold text-lg">Student's Profile</h1>
						<x-button class="bg-orange-500" id="show-details-button">Show Details</x-button>
					</div>
					<hr>
					<div class="flex flex-col md:flex-row gap-3 mt-3">
						<!-- Student Name -->
						<div class="w-full md:w-1/2" id="container_full_name">
							<x-label for="full_name" :value="__('Student Name')" />
							<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" placeholder="Student's Name" :value="old('full_name')" autofocus />
							<p class="text-red-500" style="display: none" id="err_full_name">This field is required.</p>
						</div>

						<div class="w-full md:w-1/2 flex flex-col md:flex-row gap-3">
							<!-- Student Email -->
							<div class="w-full md:w-2/3" id="container_email">
								<x-label for="email" :value="__('Student Email')" />
								<x-input id="email" class="block mt-1 w-full" type="text" name="email" placeholder="Student's Email" :value="old('email')"
									autofocus />
								<p class="text-red-500" style="display: none" id="err_email">This field is required.</p>
							</div>
							<!-- Student Gender -->
							<div class="w-full md:w-1/3" id="container_gender">
								<x-label for="gender" :value="__('Student Name')" />
								<x-select name="gender" id="gender"
								class="mt-1 w-full">
									<option value="" selected disabled>Select Gender
									<option value="1">Male</option>
									<option value="2">Female</option>
								</x-select>
								<p class="text-red-500" style="display: none" id="err_gender">This field is required.</p>
							</div>
						</div>
					</div>

					<div class="" id="form-details" style="display: none;">
						<div class="flex flex-col md:flex-row gap-3 mt-3">
							<!-- Student Phone Number -->
							<div class="w-full md:w-1/2">
								<x-label for="phone_number" :value="__('Student Phone Number')"/>
								<x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" placeholder="Add phone number"  />
							</div>

							<div class="w-full md:w-1/2 flex flex-col md:flex-row gap-3">
								<!-- Student City of Birth -->
								<div class="w-full md:w-1/2">
									<x-label for="city_of_birth" :value="__('Student City of Birth')"/>
									<x-input id="city_of_birth" class="block mt-1 w-full" type="text" name="city_of_birth" :value="old('city_of_birth')" placeholder="Add city of birth"  />
								</div>

								<!-- Student Date of Birth -->
								<div class="w-full md:w-1/2">
									<x-label for="date_of_birth" :value="__('Student Date of Birth')"/>
									<x-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" placeholder="Add date of birth"  />
								</div>
							</div>
						</div>

						<div class="flex flex-col md:flex-row gap-3 mt-6">
							<!-- Student School Name -->
							<div class="w-full md:w-1/2">
								<x-label for="school_name" :value="__('Student School Name')"/>
								<x-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="old('school_name')" placeholder="Add school name"  />
							</div>

							<!-- Student Education Level -->
							<div class="w-full md:w-1/2">
								<x-label for="student_level" class="text-white" :value="__('Select Student Education Level')" />
								<x-select name="student_level" id="student_level"
								class="mt-1 w-full">
									<option value="" selected disabled>Add Student Education Level</option>
									@forelse ($education_levels as $level)
										<option value="{{ $level }}">{{ $level }}</option>
									@empty
									@endforelse
								</x-select>
							</div>
						</div>

						<div class="flex flex-col md:flex-row gap-3 mt-3">
							<!-- Student Parent's Name -->
							<div class="w-full md:w-1/2" id="container_parent_name">
								<x-label for="name_parent" :value="__('Student Parent\'s Name')"/>
								<x-input id="name_parent" class="block mt-1 w-full" type="text" name="name_parent" :value="old('name_parent')" placeholder="Add parent's name"  />
							</div>

							<!-- Student Parent's Phone Number -->
							<div class="w-full md:w-1/2" id="container_phone_number">
								<x-label for="phone_parent" :value="__('Student Parent\'s Phone Number')"/>
								<x-input id="phone_parent" class="block mt-1 w-full" type="text" name="phone_parent" :value="old('phone_parent')" placeholder="Add parent's phone number"  />
							</div>
						</div>
					</div>

					<h1 class="font-semibold text-lg mt-10 mb-3">Student's Course Data</h1>
					<hr>
					<div class="mt-3 flex flex-col md:flex-row gap-3">
						<!-- Student's Teacher -->
						<div class="w-full md:w-1/2" id="container_teacher_name">
							<x-label for="teacher_name" :value="__('Select Student\'s Teacher')" />
							<x-select name="teacher_name" id="teacher_name" class="mt-1 w-full">
								<option value="" disabled selected>Pick a teacher</option>
								@foreach ($course->teachers as $teacher)
									<option value="{{ $teacher->toJson() }}">{{ $teacher->full_name }}</option>
								@endforeach
							</x-select>
							<p class="text-red-500" style="display: none" id="err_teacher_name">This field is required.</p>
						</div>

						<div class="w-full md:w-1/2 flex gap-3">
							<!-- Student's last attendance count -->
							<div class="w-1/2" id="container_last_attendance_count">
								<x-label for="last_attendance_count" :value="__('Student\'s Last Attendance Count')" />
								<x-input id="last_attendance_count" class="block mt-1 w-full" type="number" name="last_attendance_count" placeholder="Maximum sessions" value="0" />
								<p class="text-red-500" style="display: none" id="err_last_attendance_count">Invalid input.</p>
							</div>

							<!-- Student's max course session -->
							<div class="w-1/2" id="container_max_course_session">
								<x-label for="max_course_session" :value="__('Student\'s Maximum Sessions')"/>
								<x-input id="max_course_session" class="block mt-1 w-full" type="number" name="max_course_session" placeholder="Maximum sessions" value="8" />
								<p class="text-red-500" style="display: none" id="err_max_course_session">Invalid input.</p>
							</div>
						</div>
					</div>

					<!-- Student's last mnaterial unlocked -->
					<div class="w-full mt-3" id="container_last_material_unlocked">
						<x-label for="last_material_unlocked" :value="__('Student\'s Last Material Unlock')"/>
						<x-select name="last_material_unlocked" id="last_material_unlocked" class="mt-1 w-full">
							<option value="" disabled selected>Pick a material</option>
							@foreach ($course->topics as $topic)
								@foreach ($topic->materials as $material)
									<option value="{{ $material->toJson() }}">{{ $topic->title }} - {{ $material->title }}</option>
								@endforeach
							@endforeach
						</x-select>
						<p class="text-red-500" style="display: none" id="err_last_material_unlocked">This field is required.</p>
					</div>
					<x-button class="bg-green-600 mt-5" type="submit" id="addBtn">Add Data</x-button>
				</div>
			</form>

			<div class="border rounded-xl p-5 border-blue-800 bg-white">
				<h1 class="font-semibold text-lg">Students Data to Import</h1>
				<form class="w-full" action="{{ route("admin.course.import-student-data.store", $course->id) }}" method="post" id="real-form">
					@csrf
					<div class="overflow-x-auto w-full">
						<table class="w-full mt-3" id="student-to-import">
							<thead>
								<tr>
									<th class="border px-4 fixed1" rowspan="2">Name</th>
									<th class="border px-4 fixed1" rowspan="2">Email</th>
									<th class="border px-4 fixed1" rowspan="2">Teacher</th>
									<th class="border px-4" colspan="2">Session</th>
									<th class="border px-4 fixed1" rowspan="2">Last Material</th>
									<th class="border px-4" rowspan="2">Action</th>
									<th class="border px-4" rowspan="2">Gender</th>
									<th class="border px-4 fixed2" rowspan="2">Phone</th>
									<th class="border px-4 fixed2" rowspan="2">COB</th>
									<th class="border px-4 fixed2" rowspan="2">DOB</th>
									<th class="border px-4 fixed2" rowspan="2">School</th>
									<th class="border px-4 fixed2" rowspan="2">Level</th>
									<th class="border px-4 fixed2" rowspan="2">Parent</th>
									<th class="border px-4 fixed2" rowspan="2">Parent Phone</th>
								</tr>
								<tr>
									<th class="border px-4">Last</th>
									<th class="border px-4">Max</th>
								</tr>
							</thead>
							<tbody id="table-body">
							</tbody>
						</table>
					</div>
					<x-button class="bg-indigo-400 mt-3" type="submit">Import Data</x-button>
				</form>
			</div>

			<script>
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

					// Collect data from form and collect them in an object
					const formDataArray = $("#le-form").serializeArray();
					const formData = {};
					$.each(formDataArray, function() {
						formData[this.name] = this.value;
					});

					// Validations
					const contFullName = $("#container_full_name");
					const errFullName = $("#err_full_name");
					const contEmail = $("#container_email");
					const errEmail = $("#err_email");
					const contGender = $("#container_gender");
					const errGender = $("#err_gender");
					const contTeacherName = $("#container_teacher_name");
					const errTeacherName = $("#err_teacher_name");
					const contLastAttendanceCount = $("#container_last_attendance_count");
					const errLastAttendanceCount = $("#err_last_attendance_count");
					const contCourseMaxSession = $("#container_max_course_session");
					const errCourseMaxSession = $("#err_max_course_session");
					const contLastMaterialUnlocked = $("#container_last_material_unlocked");
					const errLastMaterialUnlocked = $("#err_last_material_unlocked");

					contFullName.css({"padding": 0, "border": "none"});
					errFullName.css({"display": "none"});
					contEmail.css({"padding": 0, "border": "none"});
					errEmail.css({"display": "none"});
					contGender.css({"padding": 0, "border": "none"});
					errGender.css({"display": "none"});
					contTeacherName.css({"padding": 0, "border": "none"});
					errTeacherName.css({"display": "none"});
					contLastAttendanceCount.css({"padding": 0, "border": "none"});
					errLastAttendanceCount.css({"display": "none"});
					contCourseMaxSession.css({"padding": 0, "border": "none"});
					errCourseMaxSession.css({"display": "none"});
					contLastMaterialUnlocked.css({"padding": 0, "border": "none"});
					errLastMaterialUnlocked.css({"display": "none"});

					let thereAreUnfilledFields = false;
					if(!$("#full_name").val()){
						contFullName.css({"border": "solid 1px red", "padding": "15px"});
						errFullName.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if(!$("#email").val()){
						contEmail.css({"border": "solid 1px red", "padding": "15px"});
						errEmail.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if(!$("#gender").val()){
						contGender.css({"border": "solid 1px red", "padding": "15px"});
						errGender.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if(!$("#teacher_name").val()){
						contTeacherName.css({"border": "solid 1px red", "padding": "15px"});
						errTeacherName.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if($("#last_attendance_count").val() < 0){
						contLastAttendanceCount.css({"border": "solid 1px red", "padding": "15px"});
						errLastAttendanceCount.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if($("#max_course_session").val() < 1){
						contCourseMaxSession.css({"border": "solid 1px red", "padding": "15px"});
						errCourseMaxSession.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if(!$("#last_material_unlocked").val()){
						contLastMaterialUnlocked.css({"border": "solid 1px red", "padding": "15px"});
						errLastMaterialUnlocked.css({"display": "block"});
						thereAreUnfilledFields = true;
					}

					if(thereAreUnfilledFields){
						return;
					}

					// Generate new row of data to show in the table
					const newRow = $("<tr>");

					const colFullName = $("<td>").addClass("border px-4 py-2 fixed1").text(formData.full_name);
					const colEmail = $("<td>").addClass("border px-4 py-2 fixed1").text(formData.email);
					const colTeacherName = $("<td>").addClass("border px-4 py-2 fixed1").text(JSON.parse(formData.teacher_name).full_name);
					const colLastAttendanceCount = $("<td>").addClass("border px-4 py-2").text(formData.last_attendance_count);
					const colMaxCourseSession = $("<td>").addClass("border px-4 py-2").text(formData.max_course_session);
					const colLastMaterialUnlocked = $("<td>").addClass("border px-4 py-2 fixed1").text(JSON.parse(formData.last_material_unlocked).title);

					const delBtn = $("<button>").attr({"type": "button"}).addClass("text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red-700 hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150").html("<i class='bi bi-trash3'></i>");
					const colAction = $("<td>").addClass("border px-4 py-2").append(delBtn);

					const colGender = $("<td>").addClass("border px-4 py-2").text(formData.phone_number);
					const colPhoneNumber = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.phone_number);
					const colCityOfBirth = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.city_of_birth);
					const colDateOfBirth = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.date_of_birth);
					const colSchoolName = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.school_name);
					const colStudentLevel = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.student_level);
					const colNameParent = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.name_parent);
					const colPhoneParent = $("<td>").addClass("border px-4 py-2 fixed2").text(formData.phone_parent);

					newRow.append(colFullName, colEmail, colTeacherName, colLastAttendanceCount, colMaxCourseSession, colLastMaterialUnlocked, colAction, colGender, colPhoneNumber, colCityOfBirth, colDateOfBirth, colSchoolName, colStudentLevel, colNameParent, colPhoneParent);

					$("#table-body").append(newRow);

					// Generate hidden inputs to help send data to Laravel
					const hiddenInputsContainer = $("<div>");

					const hidFullName = $("<input>").attr({"type": "hidden", "name": "inp_full_name[]", "value": formData.full_name});
					const hidEmail = $("<input>").attr({"type": "hidden", "name": "inp_email[]", "value": formData.email});
					const hidGender = $("<input>").attr({"type": "hidden", "name": "inp_gender[]", "value": formData.gender});
					const hidTeacherName = $("<input>").attr({"type": "hidden", "name": "inp_teacher_name[]", "value": JSON.parse(formData.teacher_name).id});
					const hidLastAttendanceCount = $("<input>").attr({"type": "hidden", "name": "inp_last_attendance_count[]", "value": formData.last_attendance_count});
					const hidMaxCourseSession = $("<input>").attr({"type": "hidden", "name": "inp_max_course_session[]", "value": formData.max_course_session});
					const hidLastMaterialUnlocked = $("<input>").attr({"type": "hidden", "name": "inp_last_material_unlocked[]", "value": JSON.parse(formData.last_material_unlocked).id});

					const hidPhoneNumber = $("<input>").attr({"type": "hidden", "name": "inp_phone_number[]", "value": formData.phone_number});
					const hidCityOfBirth = $("<input>").attr({"type": "hidden", "name": "inp_cob[]", "value": formData.city_of_birth});
					const hidDateOfBirth = $("<input>").attr({"type": "hidden", "name": "inp_dob[]", "value": formData.date_of_birth});
					const hidSchoolName = $("<input>").attr({"type": "hidden", "name": "inp_school_name[]", "value": formData.school_name});
					const hidStudentLevel = $("<input>").attr({"type": "hidden", "name": "inp_student_level[]", "value": formData.student_level});
					const hidNameParent = $("<input>").attr({"type": "hidden", "name": "inp_name_parent[]", "value": formData.name_parent});
					const hidPhoneParent = $("<input>").attr({"type": "hidden", "name": "inp_phone_parent[]", "value": formData.phone_parent});

					$(hiddenInputsContainer).append(hidFullName, hidEmail, hidTeacherName, hidLastAttendanceCount, hidMaxCourseSession, hidLastMaterialUnlocked, hidGender, hidPhoneNumber, hidCityOfBirth, hidDateOfBirth, hidSchoolName, hidStudentLevel, hidNameParent, hidPhoneParent);
					$("#real-form").append(hiddenInputsContainer);

					$(delBtn).click(() => {
						if(confirm("Are you sure want to remove this student from the list?")){
							$(newRow).remove();
							$(hiddenInputsContainer).remove();
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
					$("#teacher_name").val("");
					$("#last_attendance_count").val("0");
					$("#max_course_session").val("8");
					$("#last_material_unlocked").val("");
				});
			</script>
		@else
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- This course still has no teachers assigned to it -</p>
				<x-button type="button" onclick="history.back()" class="bg-orange-500 mt-4">
					Return
				</x-button>
			</div>
		@endif
	</x-section-container>
@endsection
