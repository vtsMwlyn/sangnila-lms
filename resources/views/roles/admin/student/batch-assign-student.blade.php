@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Batch Assign</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Batch Assign</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		@error('studentName')
			<x-badge-danger badge_text="Please input minimum 1 data to proceed."></x-badge-danger>
		@enderror

		@if($allStudents->count() && $course->teachers->count())
			<div class="my-4">
				<div class="flex">
					<h1 class="font-bold text-lg text-blue">Input New Data</h1>
				</div>
				<div id="form-area">
					<div class="flex flex-col md:flex-row gap-3">
						<div class="mt-3 w-full md:w-1/3 container-select2" id="inpStudentField">
							<x-label class="mb-1">Student Name<span class="text-red">*</span></x-label>
							<x-select name="student_name" id="student_name" class="w-full select-2" required>
								<option disabled selected>Select Student</option>
							</x-select>
							<p class="text-red font-bold mt-1" id="errStudent"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
						</div>
						<div class="mt-3 w-full md:w-1/3" id="inpTeacherField">
							<x-label class="mb-1">Teacher Name<span class="text-red">*</span></x-label>
							<x-select name="teacher_name" id="teacher_name" class="w-full" required>
								<option disabled selected>Pick a teacher</option>
								@foreach ($allTeachers as $t)
									<option value="{{ $t->full_name }}">{{ ($t->details->gender == 1)? "Mr." : "Ms." }} {{ $t->full_name }}</option>
								@endforeach
							</x-select>
							<p class="text-red font-bold mt-1" id="errTeacher"><i class="bi bi-exclamation-circle"></i> This field is required.</p>
						</div>
						<div class="mt-3 w-full md:w-1/3" id="inpMaxCourseSessionField">
							<x-label class="mb-1">Max Course Session<span class="text-red">*</span></x-label>
							<x-input id="max_course_session" class="w-full" type="number" name="max_course_session" placeholder="Maximum sessions" value="8" />
							<p class="text-red font-bold mt-1" id="errMaxCourseSession"><i class="bi bi-exclamation-circle"></i> Invalid input.</p>
						</div>
					</div>
					<div class="flex w-full justify-end mt-8">
						<x-button class=" w-1/2 md:w-1/6" type="button" id="addBtn">Add Data</x-button>
					</div>
				</div>
				<div id="no-more-add-data" class="my-5" style="display: none;">
					<p class="italic text-slate-500">- No more students can be added -</p>
				</div>
			</div>

			<div class="">
				<div class="flex">
					<h1 class="font-bold text-lg text-blue">Data to Add</h1>
				</div>
				<div class="overflow-x-auto mt-2">
					<table class="w-full">
						<thead>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student Name</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Teacher Name</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Max Session</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
						</thead>
						<tbody id="tableBody">
							<tr class="bg-white" id="empty-placeholder">
								<td colspan="4" class="p-4 text-center">- No data yet -</td>
							</tr>
						</tbody>
					</table>
				</div>

				<form action="{{ route("admin.course.batch-assign-student.store", $course->id) }}" class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
					@csrf
					<x-button class=" w-1/2 md:w-1/6">Assign All</x-button>
					<x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
				</form>
			</div>

			<script>
				$(document).ready(function() {
					// Helper array
					let without = [];

					// Load all students data sent by Laravel
					const allStudents = @json($allStudents);
					const keys = Object.keys(allStudents);

					// Initialization
					keys.forEach((key) => {
						const newOption = $("<option>").attr({"value": JSON.stringify({"id": allStudents[key].id, "full_name": allStudents[key].full_name})});
						newOption.text(allStudents[key].full_name);
						$("#student_name").append(newOption);
					});

					$("#errStudent").hide();
					$("#errTeacher").hide();
					$("#errMaxCourseSession").hide();

					// Event trigger
					const itemListModifiedEvent = new Event("item_list_modified");
					$(document).on("item_list_modified", () => {
						console.log("event triggered");
						console.log(without);
						filterStudentList();
					});

					function filterStudentList(){
						$("#student_name").empty();

						const plesholder = $("<option>").attr({"disabled": true, "selected": true});
						plesholder.text("Select Student");
						$("#student_name").append(plesholder);

						keys.forEach((key) => {
							for(let element of without){
								if(element == allStudents[key].full_name){
									return;
								}
							};

							const newOption = $("<option>").attr({"value": JSON.stringify({"id": allStudents[key].id, "full_name": allStudents[key].full_name})});
							newOption.text(allStudents[key].full_name);
							$("#student_name").append(newOption);
						});

						if($("#student_name option").length == 1){
							$("#form-area").css({"display": "none"});
							$("#no-more-add-data").css({"display": "block"});
						}
					}

					// Add items when add button clicked (main logic)
					$("#addBtn").click(() => {
						const inpStudentName = $("#student_name");
						const inpTeacherName = $("#teacher_name");
						const inpMaxCourseSession = $("#max_course_session");

						// Some validations
						inpStudentName.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
						inpTeacherName.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
						inpMaxCourseSession.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');

						$("#errStudent").hide();
						$("#errTeacher").hide();
						$("#errMaxCourseSession").hide();

						let invalidInput = false;

						if(!inpStudentName.val()){
							$("#errStudent").show();
							inpStudentName.addClass('border-red focus:border-red-700 focus:ring-0').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0');

							invalidInput = true;
						}

						if(!inpTeacherName.val()){
							$("#errTeacher").show();
							inpTeacherName.addClass('border-red focus:border-red-700 focus:ring-0').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0');

							invalidInput = true;
						}

						if(inpMaxCourseSession.val() < 1){
							$("#errMaxCourseSession").show();
							inpMaxCourseSession.addClass('border-red focus:border-red-700 focus:ring-0').removeClass('border-slate-400 focus:border-slate-600 focus:ring-0');

							invalidInput = true;
						}

						if(invalidInput){
							return;
						}

						if($("#tableBody").find('#empty-placeholder').length){
							$("#tableBody").html('');
						}

						// Generate new data element
						const row = $("<tr>");
						if($("#tableBody").children().length % 2 == 0){
							row.addClass('bg-white');
						}

						const col1 = $('<td>').addClass('py-3 px-4");
						const col2 = $('<td>').addClass('py-3 px-4");
						const col3 = $('<td>').addClass('py-3 px-4");
						const col4 = $('<td>').addClass('py-3 px-4");

						const studentObj = JSON.parse(inpStudentName.val());

						col1.text(studentObj.full_name);
						col2.text(inpTeacherName.val());
						col3.text(inpMaxCourseSession.val());

						const removeBtn = $("<button>").html("<i class='bi bi-trash3'></i>").attr({"type": "button"}).addClass("text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150");

						const btnContainer = $("<div>").addClass("flex w-full justify-start").append(removeBtn);

						col4.append(btnContainer);

						row.append(col1, col2, col3, col4);

						$("#tableBody").append(row);

						// Generate helper hidden input for Laravel data retrieval
						const hiddenInput1 = $("<input>").attr({"type": "hidden", "value": studentObj.id, "name": "studentName[]"});
						const hiddenInput2 = $("<input>").attr({"type": "hidden", "value": inpTeacherName.val(), "name": "teacherName[]"});
						const hiddenInput3 = $("<input>").attr({"type": "hidden", "value": inpMaxCourseSession.val(), "name": "maxCourseSession[]"});
						$("#leForm").append(hiddenInput1, hiddenInput2, hiddenInput3);

						removeBtn.click(() => {
							if(confirm("Are you sure want to remove this student from the list?")){
								row.remove();

								hiddenInput1.remove();
								hiddenInput2.remove();
								hiddenInput3.remove();

								const index = without.indexOf(inpStudentName);
								without.splice(index, 1);

								document.dispatchEvent(itemListModifiedEvent);
							}
						});

						without.push(studentObj.full_name);

						document.dispatchEvent(itemListModifiedEvent);
					});
				});
			</script>
		@else
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- This course still has no teachers or students assigned to it, or there are no more students to assign to course -</p>
				<x-button type="button" onclick="history.back()" class=" mt-4">
					Return
				</x-button>
			</div>
		@endif
	</x-section-container>
@endsection
