@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.attendance.show', $attendance->course->id) }}" class="font-bold text-yellow-500">{{ $attendance->course->course_name }}</a>
	> <span>{{ $attendance->attendance_date }}</span>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Edit Attendance Data</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.attendance.update', $attendance->id) }}" method="post" class="mx-auto" id="attendance_form">
			@csrf
			<div class="my-4 flex w-full justify-between items-start">
				<div class="w-1/3">
					<x-label for="attendance_date">{{ __("Attendance Date") }}</x-label>
					<div class="flex gap-3 mt-1 items-center" id="date-inp-cont">
						<div class="flex flex-col items-start">
							<x-input type="date" class="date-input w-full" name="attendance_date" id="attendance_date" :value="$attendance->attendance_date"/>
						</div>
						<x-button type="button" id="todaybtn" class="bg-orange-500">Today</x-button>
					</div>
				</div>
				<div class="w-1/3">
					<x-label for="student_add">{{ __("Add Student to Attendance") }}</x-label>
					<div class="flex items-center gap-3 mt-1">
						<x-select class="w-full" id="student_add">
						</x-select>
						<x-button type="button" id="add_student" class="bg-orange-500">Add</x-button>
					</div>
				</div>
			</div>

			<div class="overflow-x-auto mt-6">
				<x-label>{{ __("Attendance Details") }}</x-label>
				<table class="w-full" style="border-collapse: separate; border-spacing: 0 20px;">
					<tbody id="table-body">
						@php
							$exclude_from_dropdown = [];
						@endphp
						@foreach (App\Models\CourseStudent::where("course_id", $attendance->course_id)->where("teacher_id", Auth::user()->id)->get() as $course_student)
							@php
								$atd = $attendanceData->where("user_id", $course_student->student->id)->first();
							@endphp

							@if($atd)
								@php
									array_push($exclude_from_dropdown, $course_student->student->id);
								@endphp

								<tr class="table-row" style="@if($atd->attendance_detail == "Account disabled") display: none; @endif background: rgba(256, 256, 256, 0.4);">
									<td class="p-5 grow rounded-l-xl">
										<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
											<input type="checkbox" id="checkbox{{ $course_student->student->id }}"
											class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @elseif($atd->is_attend == 1) checked @endif @if($atd->attendance_detail == "Account disabled") disabled @endif>
											<label for="checkbox{{ $course_student->student->id }}">{{ $course_student->student->full_name }}</label>
											<input type="hidden" name="students[]" value="{{ $course_student->student->id }}">
										</div>
										<div class="flex w-full gap-2 mt-2 material_progress_detail items-start">
											<div class="flex flex-col w-2/3 container_select2">
												<x-select class="material_progress select2">
													<option selected disabled>Select Material Progress</option>
													@foreach ($attendance->course->topics as $topic)
														@foreach ($topic->materials as $material)
															<option value="{{ $material->title }}" @if($atd->material_progress == $material->title) selected @endif>{{ $material->title }}</option>
														@endforeach
													@endforeach
												</x-select>
											</div>

											<x-select class="learning_status w-1/3">
												<option value="On Progress" @if($atd->learning_status == "On Progress") selected @endif>On Progress</option>
												<option value="Done" @if($atd->learning_status == "Done") selected @endif>Done</option>
											</x-select>
										</div>
									</td>
									<td class="p-5 w-1/2">
										<div class="flex flex-col items-stretch">
											@if($atd)
												<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px;">{{ old("attendance_detail." . $loop->index, $atd->attendance_detail) }}</textarea>
											@else
												<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px;"></textarea>
											@endif
											@error("attendance_detail." . $loop->index)
												<span class="text-red-500 mt-2">{{ $message }}</span>
											@enderror
										</div>
									</td>
									<td class="p-5 rounded-r-xl shrink">
										<div class="flex justify-center items-center w-full h-full">
											<x-button class="bg-red-600 remove-row" type="button"><i class="bi bi-trash3"></i></x-button>
										</div>
									</td>
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="flex items-stretch gap-2 justify-center w-full mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Submit') }}
				</x-button>
				<x-cancel-button msg="The changes will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
			</div>
		</form>

		@php
			$all_students = [];
			foreach(App\Models\CourseStudent::where("course_id", $attendance->course_id)->where("teacher_id", Auth::user()->id)->get() as $crsstd){
				array_push($all_students, $crsstd->student);
			}

			$all_topics_and_materials = [];
			foreach($attendance->course->topics as $topic){
				$tm = [];
				$m = [];

				$tm["topic"] = $topic;

				foreach($topic->materials as $material){
					array_push($m, $material);
				}

				$tm["materials"] = $m;

				array_push($all_topics_and_materials, $tm);
			}
		@endphp

		<script>
			const allStudents = @json($all_students);
			console.log(allStudents);
			let exclude_dropdown = {!! json_encode($exclude_from_dropdown) !!};
			console.log(exclude_dropdown);

			$(document).ready(() => {
				const itemListModifiedEvent = new Event("item_list_modified");
				$(document).on("item_list_modified", () => {
					refreshAddStudent();
					reapplyEventListeners();
				});

				// Initialization
				applyHideAndUnhideProgress();
				applyRemoveRowButton();
				refreshAddStudent();

				function refreshAddStudent(){
					$("#student_add").html("");
					console.log(`To exclude length: ${exclude_dropdown.length}, all students count: ${allStudents.length}`);
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

				function reapplyEventListeners(){
					// Remove event listeners
					const allCheckBoxes = $('input[type="checkbox"]');
					allCheckBoxes.off("change", evlisToggleProgress);

					const removebuttons = $(".remove-row");
					removebuttons.off("click", evlisRemoveRow);

					// Reapply event listeners
					applyHideAndUnhideProgress();
					applyRemoveRowButton();
				}

				// Mechanism to hide and unhide selects for material progress detail depending if the student name checkbox is checked or not
				function toggleProgress(element) {
					const correspondingDetail = $(element).closest('td').find('.material_progress_detail');

					if ($(element).is(":checked")) {
						correspondingDetail.css("display", "flex");
					} else {
						correspondingDetail.css("display", "none");
					}
				}

				function evlisToggleProgress(){
					toggleProgress(this);
				}

				function applyHideAndUnhideProgress() {
					const allCheckBoxes = $('input[type="checkbox"]');

					// Initialize the display state for all checkboxes
					allCheckBoxes.each(evlisToggleProgress);

					// Set up the event listener for checkbox changes
					allCheckBoxes.change(evlisToggleProgress);
				}

				// Remove row of data if a student doesnt want to be included in attendance data
				function evlisRemoveRow(){
					removeRow(this);
				}

				function removeRow(element){
					if(confirm("This student will be removed from current attendance, are you sure want to proceed?")){
						exclude_dropdown = exclude_dropdown.filter(elementus => {
							const row = $(element).closest('.table-row');
							const studentId = row.find('input[type="hidden"][name="students[]"]').val();

							return elementus != studentId;
						});

						$(element).closest('.table-row').remove();

						document.dispatchEvent(itemListModifiedEvent);
					}
				}

				function applyRemoveRowButton(){
					const removebuttons = $(".remove-row");
					for(let btn of removebuttons){
						$(btn).click(evlisRemoveRow);
					}
				}

				function generateNewRow(){
					const tbody = $("#table-body");

					const trow = $("<tr>").addClass("table-row").css({"background": "rgba(256, 256, 256, 0.4)"});
					const firstCol = $("<td>").addClass("p-5 grow rounded-l-xl");
					const secondCol = $("<td>").addClass("p-5 w-1/2");
					const thirdCol = $("<td>").addClass("p-5 rounded-r-xl shrink");

					// First column
					const checkNameContainer = $("<div>").addClass("flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl");
					const progressContainer = $("<div>").addClass("flex w-full gap-2 mt-2 material_progress_detail");

					const studentToAdd = JSON.parse($("#student_add").val());
					const newCheckBox = $("<input>").attr({"type": "checkbox", "id": `checkbox${studentToAdd.id}`}).addClass("mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300");
					const newLabelCheckBox = $("<label>").attr({"for": `checkbox${studentToAdd.id}`}).text(studentToAdd.full_name);
					const newHiddenInput = $("<input>").attr({"type": "hidden", "name": "students[]", "value": studentToAdd.id});
					checkNameContainer.append(newCheckBox).append(newLabelCheckBox).append(newHiddenInput);

					const newMaterialProgressDropdown = $("<select>").addClass("material_progress w-2/3 border-blue-900 focus:border-blue-700 focus:ring focus:ring-blue-700 focus:ring-opacity-50 rounded-xl shadow-sm border-2 font-semibold text-blue-800 py-3 px-5");
					const materialProgressDropdownPlaceholder = $("<option>").attr({"disabled": true, "selected": true}).text("Select Material Progress");
					newMaterialProgressDropdown.append(materialProgressDropdownPlaceholder);
					const allTopicsAndMaterials = @json($all_topics_and_materials);
					for(let tm of allTopicsAndMaterials){
						for(let material of tm.materials){
							const newMaterialProgressDropdownOption = $("<option>").attr({"value": material.title}).text(material.title);
							newMaterialProgressDropdown.append(newMaterialProgressDropdownOption);
						}
					}

					const newLearningStatusDropdown = $("<select>").addClass("learning_status w-1/3 border-blue-900 focus:border-blue-700 focus:ring focus:ring-blue-700 focus:ring-opacity-50 rounded-xl shadow-sm border-2 font-semibold text-blue-800 py-3 px-5");
					const materialProgressDropdownOption1 = $("<option>").attr({"selected": true, "value": "On Progress"}).text("On Progress");
					const materialProgressDropdownOption2 = $("<option>").attr({"value": "Done"}).text("Done");
					newLearningStatusDropdown.append(materialProgressDropdownOption1).append(materialProgressDropdownOption2);

					progressContainer.append(newMaterialProgressDropdown).append(newLearningStatusDropdown);

					firstCol.append(checkNameContainer).append(progressContainer);

					// Second column
					const attendanceDetailContainer = $("<div>").addClass("flex flex-col items-stretch");
					const newTextArea = $("<textarea>").attr({"rows": 4, "name": "attendance_detail[]", "placeholder": "Enter student in class progress"}).addClass("rounded-xl border-2 font-semibold text-blue-900 border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50").css({"resize": "none", "box-sizing": "border-box", "padding": "10px"});
					attendanceDetailContainer.append(newTextArea);

					secondCol.append(attendanceDetailContainer);

					// Third column
					const removeBtnContainer = $("<div>").addClass("flex justify-center items-center w-full h-full");
					const newRemoveBtn = $("<button>").attr({"type": "button"}).addClass("bg-red-600 remove-row text-center px-5 py-2 border border-transparent rounded-xl text-white font-semibold hover:bg-slate-800 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150").css({"box-shadow": "0 1px 2px rgba(0, 0, 0, 0.3)"}).html("<i class='bi bi-trash3'></i>");
					removeBtnContainer.append(newRemoveBtn);
					thirdCol.append(removeBtnContainer);

					trow.append(firstCol, secondCol, thirdCol);
					tbody.append(trow);

					exclude_dropdown.push(studentToAdd.id);

					applyHideAndUnhideProgress();
					applyRemoveRowButton();

					document.dispatchEvent(itemListModifiedEvent);
				}

				$("#add_student").click(function(){
					if(exclude_dropdown.length < allStudents.length){
						generateNewRow();
					} else {
						return;
					}
				});

				// Helper mechanism to send data to Laravel when form is submitted
				const collectCheckboxValues = () => {
					const checkboxes = $('input[type="checkbox"]');
					const checkboxValues = [];
					const materialProgressValues = [];
					const learningStatusValues = [];

					checkboxes.each(function(){
						const correspondingDetail = $(this).closest('td').find('.material_progress_detail');

						if($(this).is(":disabled")){
							checkboxValues.push('off');
							materialProgressValues.push("Absent");
							learningStatusValues.push("Absent");
						} else {
							const cb = $(this).is(":checked") ? 'on' : 'off';
							checkboxValues.push(cb);

							if(cb == "on"){
								materialProgressValues.push(correspondingDetail.find('.material_progress').val());
								learningStatusValues.push(correspondingDetail.find('.learning_status').val());
							}
							else {
								materialProgressValues.push("Absent");
								learningStatusValues.push("Absent");
							}

						}
					});

					return [checkboxValues, materialProgressValues, learningStatusValues];
				}

				const processDisabledTextAreas = () => {
					const form = document.querySelector('#attendance_form');
					const textareas = form.querySelectorAll('textarea');

					textareas.forEach((textarea, index) => {
						if (textarea.disabled) {
							const hiddenTextarea = document.createElement('input');
							hiddenTextarea.type = 'hidden';
							hiddenTextarea.name = `attendance_detail[${index}]`;
							hiddenTextarea.value = textarea.value;
							form.appendChild(hiddenTextarea);
						}
					});
				}

				const form = document.querySelector('#attendance_form');
				form.addEventListener('submit', (event) => {
					event.preventDefault();

					const [checkboxValues, materialProgressValues, learningStatusValues] = collectCheckboxValues();

					console.log(checkboxValues);
					console.log(materialProgressValues);
					console.log(learningStatusValues);

					$invalid = false;

					$("#attendance_date").css({"border": "rgb(30 58 138) solid 2px"});
					$("#attendance_date").closest("div").find("p").remove();
					$("#date-inp-cont").removeClass("items-start").addClass("items-center");
					$("textarea").css({"border": "rgb(30 58 138) solid 2px"});
					$("textarea").closest("div").find("p").remove();
					$(".material_progress").each(function(){
						$(this).css({"border": "rgb(30 58 138) solid 2px"});
						$(this).closest("div").find("p").remove();
					});

					if(!$("#attendance_date").val()){
						$("#attendance_date").css("border", "solid 2px rgb(185 28 28)");
						$("#date-inp-cont").removeClass("items-center").addClass("items-start");
						$("#attendance_date").after($("<p>").html('<i class="bi bi-exclamation-circle"></i> The attendance date field is required.').addClass("text-red-800 font-bold mt-1"));
						$invalid = true;
					}

					$("textarea").each(function(){
						if($(this).val() == ""){
							$(this).after($("<p>").html('<i class="bi bi-exclamation-circle"></i> The attendance detail field is required.').addClass("text-red-800 font-bold mt-1"));
							$(this).css("border", "solid 2px rgb(185 28 28)");
							$invalid = true;
						}
					});

					checkboxValues.forEach((value, index) => {
						if(value === "on" && !materialProgressValues[index]){
							$($(".material_progress")[index]).css({"border": "solid 2px rgb(185 28 28)"});
							$($(".material_progress")[index]).after($("<p>").html('<i class="bi bi-exclamation-circle"></i> The material progress field is required.').addClass("text-red-800 font-bold mt-1"));
							$invalid = true;
						}
					});

					if($invalid){
						return;
					}

					checkboxValues.forEach((value, index) => {
						const hiddenInput1 = $("<input>").attr({"type": "hidden", "name": "checkbox_value[]", "value": value});
						const hiddenInput2 = $("<input>").attr({"type": "hidden", "name": "material_progress[]", "value": materialProgressValues[index]});
						const hiddenInput3 = $("<input>").attr({"type": "hidden", "name": "learning_status[]", "value": learningStatusValues[index]});

						$(form).append(hiddenInput1, hiddenInput2, hiddenInput3);
					});

					processDisabledTextAreas();

					form.submit();
				});

				const todayBtn = document.querySelector("#todaybtn");
				todayBtn.addEventListener("click", () => {
					const inpDate = document.querySelector("#attendance_date");
					const currentDate = new Date();
					const year = currentDate.getFullYear();
					const month = String(currentDate.getMonth() + 1).padStart(2, '0');
					const day = String(currentDate.getDate()).padStart(2, '0');
					inpDate.value = `${year}-${month}-${day}`;
				});
			});

		</script>
	</x-section-container>
@endsection
