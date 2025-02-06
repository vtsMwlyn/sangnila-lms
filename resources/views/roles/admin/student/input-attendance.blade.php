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

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<x-badge-danger id="emptyDataNotif" badge_text="Please input minimum 1 data to proceed." style="display: none;"></x-badge-danger>

		{{-- @if($allStudents->count() && $course->teachers->count()) --}}
			<div class="my-4">
				<div id="form-area">
					{{-- Class data --}}
					<h1 class="font-bold text-lg text-blue">Class Data</h1>
					<div class="flex gap-3">
						<div class="mt-3 w-full md:w-1/2">
							<x-label class="mb-1">Course Name</x-label>
							<x-select name="course_name" id="course_name" class="w-full">
								<option disabled selected>Pick a course</option>
								@foreach ($student->enrolled_courses as $c)
									<option value="{{ App\Models\CourseStudent::where('course_id', $c->id)->where('student_id', $student->id)->with('teacher')->first() }}">{{ $c->course_name }}</option>
								@endforeach
							</x-select>
						</div>
						<div class="mt-3 w-full md:w-1/2">
							<x-label class="mb-1">Teacher</x-label>
							<x-input type="text" name="teacher_name" id="teacher_name" class="w-full" disabled value="Pick a course first"/>
						</div>
					</div>

					{{-- Attendance data --}}
					<h1 class="font-bold text-lg text-blue mt-8">Attendance Data</h1>
					<div class="flex flex-col w-full">
						<div class="w-full flex gap-3 mt-3">
							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Attendance Date</x-label>
								<x-input type="date" name="_teacher_name" id="_teacher_name" class="w-full date-input"/>
							</div>
							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Attendance Status</x-label>
								<x-select name="_is_attended" id="_is_attended" class="w-full">
									<option value="1">Attended</option>
									<option value="0">Absent</option>
								</x-select>
							</div>
						</div>

						<div class="w-full flex gap-3 mt-3">
							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Activity</x-label>
								<x-select name="_activity_progress" id="_activity_progress" class="w-full select2">
									@foreach ($c->topics as $t)
										@foreach ($t->activities as $a)
											<option value="{{ $a->id }}">{{ $a->title }}</option>
										@endforeach
									@endforeach
								</x-select>
							</div>

							<div class="w-full md:w-1/2">
								<x-label class="mb-1">Learning Status</x-label>
								<x-select name="_learning_status" id="_learning_status" class="w-full">
									<option value="done">Done</option>
									<option value="on_progress">On Progress</option>
								</x-select>
							</div>
						</div>

						<div class="w-full mt-3">
							<x-label class="mb-1">Details</x-label>
							<x-textarea rows="4" name="_attendance_details" id="_attendance_details" class="w-full" placeholder="Input details"></x-textarea>
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
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attended</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
						</thead>
						<tbody id="tableBody">
							<tr class="bg-white" id="empty-placeholder">
								<td colspan="5" class="p-4 text-center">- No data yet -</td>
							</tr>
						</tbody>
					</table>
				</div>

				<form action="{{ route('admin.student.store-attendance', $student->id) }}" class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
					@csrf
					<x-button class=" w-1/2 md:w-1/6">Submit Data</x-button>
					<x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
				</form>
			</div>

			<script>
				$(document).ready(function() {
					$('#course_name').on('change', function(){
						const classData = JSON.parse($(this).val());

						$('#teacher_name').val(classData.teacher.full_name);
					});

					// // Helper array
					// let without = [];

					// // Initialization
					// keys.forEach((key) => {
					// 	const newOption = $("<option>").attr({"value": JSON.stringify({"id": allStudents[key].id, "full_name": allStudents[key].full_name})});
					// 	newOption.text(allStudents[key].full_name);
					// 	$("#student_name").append(newOption);
					// });

					// $("#errStudent").css({"display": "none"});
					// $("#errTeacher").css({"display": "none"});
					// $("#errMaxCourseSession").css({"display": "none"});

					// // Event trigger
					// const itemListModifiedEvent = new Event("item_list_modified");
					// $(document).on("item_list_modified", () => {
					// 	console.log("event triggered");
					// 	console.log(without);
					// 	filterStudentList();
					// });

					// function filterStudentList(){
					// 	$("#student_name").empty();

					// 	const plesholder = $("<option>").attr({"disabled": true, "selected": true});
					// 	plesholder.text("Select Student");
					// 	$("#student_name").append(plesholder);

					// 	keys.forEach((key) => {
					// 		for(let element of without){
					// 			if(element == allStudents[key].full_name){
					// 				return;
					// 			}
					// 		};

					// 		const newOption = $("<option>").attr({"value": JSON.stringify({"id": allStudents[key].id, "full_name": allStudents[key].full_name})});
					// 		newOption.text(allStudents[key].full_name);
					// 		$("#student_name").append(newOption);
					// 	});

					// 	if($("#student_name option").length == 1){
					// 		$("#form-area").css({"display": "none"});
					// 		$("#no-more-add-data").css({"display": "block"});
					// 	}
					// }

					// // Add items when add button clicked (main logic)
					// $("#addBtn").click(() => {
					// 	const inpStudentName = $("#student_name").val();
					// 	const inpTeacherName = $("#teacher_name").val();
					// 	const inpMaxCourseSession = $("#max_course_session").val();

					// 	// Some validations
					// 	$("#inpStudentField").css({"border": "none", "padding": 0});
					// 	$("#inpTeacherField").css({"border": "none", "padding": 0});
					// 	$("#inpMaxCourseSessionField").css({"border": "none", "padding": 0});
					// 	$("#errStudent").css({"display": "none"});
					// 	$("#errTeacher").css({"display": "none"});
					// 	$("#errMaxCourseSession").css({"display": "none"});

					// 	let invalidInput = false;

					// 	if(!inpStudentName){
					// 		$("#inpStudentField").css({"border": "2px solid rgb(185 28 28)", "padding": "10px"});
					// 		$("#errStudent").css({"display": "block"});
					// 		invalidInput = true;
					// 	}

					// 	if(!inpTeacherName){
					// 		$("#inpTeacherField").css({"border": "2px solid rgb(185 28 28)", "padding": "10px"});
					// 		$("#errTeacher").css({"display": "block"});
					// 		invalidInput = true;
					// 	}

					// 	if(inpMaxCourseSession < 1){
					// 		$("#inpMaxCourseSessionField").css({"border": "2px solid rgb(185 28 28)", "padding": "10px"});
					// 		$("#inpMaxCourseSession")
					// 		$("#errMaxCourseSession").css({"display": "block"});
					// 		invalidInput = true;
					// 	}

					// 	if(invalidInput){
					// 		return;
					// 	}

					// 	if($("#tableBody").find('#empty-placeholder').length){
					// 		$("#tableBody").html('');
					// 	}

					// 	// Generate new data element
					// 	const row = $("<tr>");
					// 	if($("#tableBody").children().length % 2 == 0){
					// 		row.addClass('bg-white');
					// 	}

					// 	const col1 = $("<td>").addClass("py-2 px-4");
					// 	const col2 = $("<td>").addClass("py-2 px-4");
					// 	const col3 = $("<td>").addClass("py-2 px-4");
					// 	const col4 = $("<td>").addClass("py-2 px-4");

					// 	const studentObj = JSON.parse(inpStudentName);

					// 	col1.text(studentObj.full_name);
					// 	col2.text(inpTeacherName);
					// 	col3.text(inpMaxCourseSession);

					// 	const removeBtn = $("<button>").html("<i class='bi bi-trash3'></i>").attr({"type": "button"}).addClass("text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150");

					// 	const btnContainer = $("<div>").addClass("flex w-full justify-start").append(removeBtn);

					// 	col4.append(btnContainer);

					// 	row.append(col1, col2, col3, col4);

					// 	removeBtn.click(() => {
					// 		if(confirm("Are you sure want to remove this student from the list?")){
					// 			row.remove();

					// 			const index = without.indexOf(inpStudentName);
					// 			without.splice(index, 1);

					// 			document.dispatchEvent(itemListModifiedEvent);
					// 		}
					// 	});

					// 	$("#tableBody").append(row);

					// 	// Generate helper hidden input for Laravel data retrieval
					// 	const hiddenInput1 = $("<input>").attr({"type": "hidden", "value": studentObj.id, "name": "studentName[]"});
					// 	const hiddenInput2 = $("<input>").attr({"type": "hidden", "value": inpTeacherName, "name": "teacherName[]"});
					// 	const hiddenInput3 = $("<input>").attr({"type": "hidden", "value": inpMaxCourseSession, "name": "maxCourseSession[]"});
					// 	$("#leForm").append(hiddenInput1, hiddenInput2, hiddenInput3);

					// 	without.push(studentObj.full_name);

					// 	document.dispatchEvent(itemListModifiedEvent);
					// });

					// $("#leForm").on("submit", function(e){
					// 	e.preventDefault();

					// 	if(without.length == 0){
					// 		$("#emptyDataNotif").css("display", "flex");

					// 		return;
					// 	}

					// 	this.submit();
					// });
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
