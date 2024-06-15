@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-page-title>Batch Assign</x-page-title>

	<div class="border rounded-xl p-5">
		<h1 class="font-semibold text-lg">Input New Data</h1>
		<div class="flex gap-3">
			<div class="mt-3 w-1/3" id="inpStudentField">
				<x-label class="mb-1">{{ __("Student Name") }}</x-label>
				<x-select name="student_name" id="student_name" class="w-full" required>
					<option disabled selected>Select Student</option>
				</x-select>
				<p class="text-red-500" id="errStudent">This field is required.</p>
			</div>
			<div class="mt-3 w-1/3" id="inpTeacherField">
				<x-label class="mb-1">{{ __("Teacher Name") }}</x-label>
				<x-select name="teacher_name" id="teacher_name" class="w-full" required>
					<option disabled selected>Pick a teacher</option>
					@foreach ($allTeachers as $t)
						<option value="{{ $t->full_name }}">{{ ($t->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ $t->full_name }}</option>
					@endforeach
				</x-select>
				<p class="text-red-500" id="errTeacher">This field is required.</p>
			</div>
			<div class="mt-3 w-1/3" id="inpMaxCourseSessionField">
				<x-label class="mb-1">{{ __("Max Course Session") }}</x-label>
				<x-input id="max_course_session" class="w-full" type="number" name="max_course_session" placeholder="Maximum sessions" value="8" />
				<p class="text-red-500" id="errMaxCourseSession">Invalid input.</p>
			</div>
		</div>
		<x-button class="bg-green-600 mt-5" type="button" id="addBtn">Add Data</x-button>
	</div>

	<div class="border rounded-xl p-5 mt-5">
		<h1 class="font-semibold text-lg">Data to Add</h1>
		<div class="overflow-x-auto mt-5">
			<table class="w-full">
				<thead>
					<th class="border px-4 py-2">Student Name</th>
					<th class="border px-4 py-2">Teacher Name</th>
					<th class="border px-4 py-2" style="min-width: 100px; max-width: 100px;">Max Session</th>
					<th class="border px-4 py-2">Action</th>
				</thead>
				<tbody id="tableBody">
				</tbody>
			</table>
		</div>

		<form action="{{ route("admin.course.batch-assign.store", $course->id) }}" class="mt-5" method="post" id="leForm">
			@csrf
			<x-button class="bg-indigo-400">Assign All</x-button>
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

			$("#errStudent").css({"display": "none"});
			$("#errTeacher").css({"display": "none"});
			$("#errMaxCourseSession").css({"display": "none"});

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
			}

			// Select2 initialization + MAKING THIS SH*T FOLLOW THE RESIZING OF ITS CONTAINER SO YOU NO NEED TO REFRESH
			$('#student_name').select2({
				placeholder: "Select a Student",
				allowClear: false
			});

			function stylingSelect2(){
				$('#student_name').next('.select2-container').find('.select2-selection').addClass('rounded-lg shadow-sm border text-blue-800 py-1.5 px-3');

				$('#student_name').next('.select2-container').find('.select2-selection').css({
				"height": "2.6rem",
				"border": "solid 1px rgb(30 64 175)",
				"width": "100%"});
			}

			const container = document.getElementById('inpStudentField');
			const resizeObserver = new ResizeObserver(() => {
				$('#student_name').select2('destroy').select2({
					placeholder: "Select a Student",
					allowClear: false
				});

				stylingSelect2();
			});

			if (container) {
				resizeObserver.observe(container);
			}

			// Adding placeholder to search field inside select2
			$('#student_name').on('select2:open', function (e) {
				if ($('#student_name').data('select2').isOpen()) {
					const searchField = $('.select2-search__field');
					searchField.attr('placeholder', 'Search for a student...');
				}
			});

			// Add items when add button clicked (main logic)
			$("#addBtn").click(() => {
				const inpStudentName = $("#student_name").val();
				const inpTeacherName = $("#teacher_name").val();
				const inpMaxCourseSession = $("#max_course_session").val();

				// Some validations
				$("#inpStudentField").css({"border": "none", "padding": 0});
				$("#inpTeacherField").css({"border": "none", "padding": 0});
				$("#inpMaxCourseSessionField").css({"border": "none", "padding": 0});
				$("#errStudent").css({"display": "none"});
				$("#errTeacher").css({"display": "none"});
				$("#errMaxCourseSession").css({"display": "none"});

				let invalidInput = false;

				if(!inpStudentName){
					$("#inpStudentField").css({"border": "1px solid red", "padding": "10px"});
					$("#errStudent").css({"display": "block"});
					invalidInput = true;
				}

				if(!inpTeacherName){
					$("#inpTeacherField").css({"border": "1px solid red", "padding": "10px"});
					$("#errTeacher").css({"display": "block"});
					invalidInput = true;
				}

				if(inpMaxCourseSession < 1 || inpMaxCourseSession % 8 != 0){
					$("#inpMaxCourseSessionField").css({"border": "1px solid red", "padding": "10px"});
					$("#errMaxCourseSession").css({"display": "block"});
					invalidInput = true;
				}

				if(invalidInput){
					return;
				}

				// Generate new data element
				const row = $("<tr>");
				const col1 = $("<td>").addClass("border py-2 px-4");
				const col2 = $("<td>").addClass("border py-2 px-4");
				const col3 = $("<td>").addClass("border py-2 px-4");
				const col4 = $("<td>").addClass("border py-2 px-4");

				const studentObj = JSON.parse(inpStudentName);

				col1.text(studentObj.full_name);
				col2.text(inpTeacherName);
				col3.text(inpMaxCourseSession);

				const removeBtn = $("<button>").text("Remove").attr({"type": "button"}).addClass("text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red-700 hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150");

				const btnContainer = $("<div>").addClass("flex w-full justify-center").append(removeBtn);

				col4.append(btnContainer);

				row.append(col1, col2, col3, col4);

				removeBtn.click(() => {
					row.remove();

					const index = without.indexOf(inpStudentName);
					without.splice(index, 1);

					document.dispatchEvent(itemListModifiedEvent);
				});

				$("#tableBody").append(row);

				// Generate helper hidden input for Laravel data retrieval
				const hiddenInput1 = $("<input>").attr({"type": "hidden", "value": studentObj.id, "name": "studentName[]"});
				const hiddenInput2 = $("<input>").attr({"type": "hidden", "value": inpTeacherName, "name": "teacherName[]"});
				const hiddenInput3 = $("<input>").attr({"type": "hidden", "value": inpMaxCourseSession, "name": "maxCourseSession[]"});
				$("#leForm").append(hiddenInput1, hiddenInput2, hiddenInput3);

				without.push(studentObj.full_name);

				document.dispatchEvent(itemListModifiedEvent);
			});
		});
	</script>
@endsection
