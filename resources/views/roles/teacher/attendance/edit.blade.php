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

		<form action="{{ route('teacher.attendance.update', $attendance->id) }}" method="post" id="attendance_form">
			@csrf
			<div class="my-4 flex w-full justify-between items-start">
				<div class="w-1/3">
					<x-label for="attendance_date">{{ __("Attendance Date") }}</x-label>
					<div class="flex gap-3 mt-1 items-center" id="date-inp-cont">
						<div class="flex flex-col items-start grow">
							<x-input type="date" class="date-input w-full" name="attendance_date" id="attendance_date" :value="$attendance->attendance_date"/>
						</div>
						<x-button type="button" id="todaybtn" class="bg-orange-500">Today</x-button>
					</div>
				</div>
				<div class="w-1/3">
					<x-label for="student_add">{{ __("Add Student to Attendance") }}</x-label>
					<div class="flex items-center gap-3 mt-1 select2_container">
						<select class="w-full select2 rounded-2xl shadow-sm focus:outline-none py-2 px-4 focus:ring-0" id="student_add" style="border-width: 3px;">
						</select>
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

						{{-- @foreach ($attendanceData as $ad)
							@php
								array_push($exclude_from_dropdown, $ad->student->id);
							@endphp

							<tr @if($loop->index == 0) id="tes" @endif class="table-row" style="@if($ad->attendance_detail == "Account disabled") display: none; @endif background: rgba(256, 256, 256, 0.4);">
								<td class="grow rounded-l-xl p-5">
									<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
										<input type="checkbox" id="checkbox{{ $ad->student->id }}"
										class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @elseif($ad->is_attend == 1) checked @endif>
										<label for="checkbox{{ $ad->student->id }}">{{ $ad->student->full_name }}</label>
										<input type="hidden" name="students[]" value="{{ $ad->student->id }}">
									</div>
									<div class="flex w-full gap-3 mt-2 material_progress_detail items-start justify-between @error('material_progress.' . $loop->index) border-red rounded-2xl p-1 @enderror @error('learning_status.' . $loop->index) border-red p-1 rounded-2xl @enderror">
										<div class="flex flex-col w-2/3 container_select2">
											<x-select class="material_progress select2" name="fake_material_progress[]">
												<option selected disabled>Select Material Progress</option>
												@foreach ($attendance->course->topics as $topic)
													@foreach ($topic->materials as $material)
														<option value="{{ $material->title }}" @if($ad->material_progress == $material->title) selected @endif>{{ $material->title }}</option>
													@endforeach
												@endforeach
											</x-select>
										</div>

										<x-select class="learning_status w-1/3" name="fake_learning_status[]">
											<option value="On Progress" @if($ad->learning_status == "On Progress") selected @endif>On Progress</option>
											<option value="Done" @if($ad->learning_status == "Done") selected @endif>Done</option>
										</x-select>
									</div>

									@error("learning_status." . $loop->index)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror
									@error("material_progress." . $loop->index)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror
								</td>
								<td class="w-1/2">
									<div class="flex flex-col items-stretch">
										@if($ad)
											<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red focus:border-red-700 focus:ring-0 @else border-slate-400 focus:border-slate-600 focus:ring-0 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px; border-width: 3px;">{{ old("attendance_detail." . $loop->index, $ad->attendance_detail) }}</textarea>
										@else
											<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px;"></textarea>
										@endif
										@error("attendance_detail." . $loop->index)
											<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> The attendance detail field is required.</p>
										@enderror
									</div>
								</td>
								<td class="rounded-r-xl shrink p-5">
									<div class="flex justify-center items-center w-full h-full">
										<x-button class="bg-red-600 remove-row" type="button"><i class="bi bi-trash3"></i></x-button>
									</div>
								</td>
							</tr>
						@endforeach --}}

						{{-- <tr class="bg-slate-800">
							<td colspan="3"></td>
						</tr> --}}

						{{-- @if(old('students'))
							@dd(old('students.6', 'tolol'))
						@endif --}}

						@forelse (old('checkbox_value', $attendanceData) as $i => $ad)
							@php
								$studentId = is_object($ad)? $ad->student->id : $ad;
								$matProg = is_object($ad)? $ad->material_progress : $ad;
								$learStat = is_object($ad)? $ad->learning_status : $ad;
								$attenDet = is_object($ad)? $ad->attendance_detail: $ad;
								$isAtten = is_object($ad)? $ad->is_attend : $ad;

								array_push($exclude_from_dropdown, old('students.' . $i, $studentId));
							@endphp

							<tr class="table-row" style=" background: rgba(256, 256, 256, 0.4);">
								<td class="grow rounded-l-xl p-5">
									<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
										<input type="checkbox" id="checkbox{{ old('students.' . $i, $studentId) }}"
										class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $i, $studentId) == "on") checked @elseif($isAtten == 1) checked @endif>
										<label for="checkbox{{ old('students.' . $i, $studentId) }}">{{ App\Models\User::find(old('students.' . $i, $studentId))->full_name }}</label>
										<input type="hidden" name="students[]" value="{{ old('students.' . $i, $studentId) }}">
									</div>
									<div class="flex w-full gap-3 mt-2 material_progress_detail items-start justify-between @error('material_progress.' . $i) border-red rounded-2xl p-1 @enderror @error('learning_status.' . $i) border-red p-1 rounded-2xl @enderror">
										<div class="flex flex-col w-2/3 container_select2">
											<x-select class="material_progress select2" name="fake_material_progress[]">
												<option selected disabled>Select Material Progress</option>
												@foreach ($attendance->course->topics as $topic)
													@foreach ($topic->materials as $material)
														<option value="{{ $material->title }}" @if(old('material_progress.' . $i, $matProg) == $material->title) selected @endif>{{ $material->title }}</option>
													@endforeach
												@endforeach
											</x-select>
										</div>

										<x-select class="learning_status w-1/3" name="fake_learning_status[]">
											<option selected disabled>Select Status</option>
											<option value="On Progress" @if(old('learning_status.' . $i, $learStat) == "On Progress") selected @endif>On Progress</option>
											<option value="Done" @if(old('learning_status.' . $i, $learStat) == "Done") selected @endif>Done</option>
										</x-select>
									</div>

									@error("learning_status." . $i)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror
									@error("material_progress." . $i)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror
								</td>
								<td class="w-1/2">
									<div class="flex flex-col items-stretch">
										<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $i) border-red focus:border-red-700 focus:ring-0 @else border-slate-400 focus:border-slate-600 focus:ring-0 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px; border-width: 3px;">{{ old("attendance_detail." . $i, $attenDet) }}</textarea>
										@error("attendance_detail." . $i)
											<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> The attendance detail field is required.</p>
										@enderror
									</div>
								</td>
								<td class="rounded-r-xl shrink p-5">
									<div class="flex justify-center items-center w-full h-full">
										<x-button class="bg-red-600 remove-row" type="button"><i class="bi bi-trash3"></i></x-button>
									</div>
								</td>
							</tr>
						@empty
						@endforelse

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
			@php
				$counter = $attendanceData->count();
			@endphp

			const allStudents = @json($all_students);
			let exclude_dropdown = {!! json_encode($exclude_from_dropdown) !!};

			$(document).ready(() => {
				const itemListModifiedEvent = new Event("item_list_modified");
				$(document).on("item_list_modified", () => {
					refreshAddStudent();
					reapplyEventListeners();
					reinitializeSelect2();
				});

				// Initialization
				applyHideAndUnhideProgress();
				applyRemoveRowButton();
				refreshAddStudent();

				function reinitializeSelect2(){
					// Select2 initialization
					$('.select2').select2({
						allowClear: false
					});

					// Apply resize observer to each container with class 'container_select2'
					$('.container_select2').each(function () {
						const container = this;
						const resizeObserver = new ResizeObserver(() => {
							$(container).find('.select2').each(function () {
								$(this).select2('destroy').select2({
									allowClear: false
								});
							});

							// stylingSelect2();
						});

						resizeObserver.observe(container);
					});
				}

				function refreshAddStudent(){
					$("#student_add").html("");
					console.log(`To exclude length: ${exclude_dropdown.length}, all students count: ${allStudents.length}`);
					if(exclude_dropdown.length < allStudents.length){
						for(let std of allStudents){
							if(!exclude_dropdown.includes(std.id) && std.status != "disabled"){
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

						@php
							$counter--;
						@endphp

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
					const secondCol = $("<td>").addClass("w-1/2");
					const thirdCol = $("<td>").addClass("p-5 rounded-r-xl shrink");

					// First column
					const checkNameContainer = $("<div>").addClass("flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl");
					const progressContainer = $("<div>").addClass("flex w-full gap-2 mt-2 material_progress_detail @error('material_progress.' . $counter) border-red rounded-2xl p-1 @enderror @error('learning_status.' . $counter) border-red p-1 rounded-2xl @enderror");

					const studentToAdd = JSON.parse($("#student_add").val());
					const newCheckBox = $("<input>").attr({"type": "checkbox", "id": `checkbox${studentToAdd.id}`}).addClass("mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300");
					const newLabelCheckBox = $("<label>").attr({"for": `checkbox${studentToAdd.id}`}).text(studentToAdd.full_name);
					const newHiddenInput = $("<input>").attr({"type": "hidden", "name": "students[]", "value": studentToAdd.id});
					checkNameContainer.append(newCheckBox).append(newLabelCheckBox).append(newHiddenInput);

					const selectContainer = $("<div>").addClass("flex flex-col w-2/3 container_select2");
					const newMaterialProgressDropdown = $("<select>").addClass("select2 material_progress w-full rounded-2xl shadow-sm focus:outline-none py-2 px-4 border-slate-400 focus:border-slate-600 focus:ring-0").css("border-width", "3px");
					const materialProgressDropdownPlaceholder = $("<option>").attr({"disabled": true, "selected": true}).text("Select Material Progress");
					newMaterialProgressDropdown.append(materialProgressDropdownPlaceholder);
					const allTopicsAndMaterials = @json($all_topics_and_materials);
					for(let tm of allTopicsAndMaterials){
						for(let material of tm.materials){
							const newMaterialProgressDropdownOption = $("<option>").attr({"value": material.title}).text(material.title);
							newMaterialProgressDropdown.append(newMaterialProgressDropdownOption);
						}
					}

					const newLearningStatusDropdown = $("<select>").addClass("learning_status w-1/3 rounded-2xl shadow-sm focus:outline-none py-2 px-4 border-slate-400 focus:border-slate-600 focus:ring-0").css("border-width", "3px");
					const learningStatusPlaceholder = $("<option>").attr({"selected": true, "disabled": true}).text("Select Status");
					const learningStatusDropdownOption1 = $("<option>").attr({"value": "On Progress"}).text("On Progress");
					const learningStatusDropdownOption2 = $("<option>").attr({"value": "Done"}).text("Done");
					newLearningStatusDropdown.append(learningStatusPlaceholder).append(learningStatusDropdownOption1).append(learningStatusDropdownOption2);

					progressContainer.append(selectContainer.append(newMaterialProgressDropdown)).append(newLearningStatusDropdown);

					firstCol.append(checkNameContainer).append(progressContainer);

					// Second column
					const attendanceDetailContainer = $("<div>").addClass("flex flex-col items-stretch");
					const newTextArea = $("<textarea>").attr({"rows": 4, "name": "attendance_detail[]", "placeholder": "Enter student in class progress"}).addClass("rounded-xl border-2 font-semibold text-blue-900 @error('attendance_detail.' . $counter) border-red focus:border-red-700 focus:ring-0 @else border-slate-400 focus:border-slate-600 focus:ring-0 @enderror").css({"resize": "none", "box-sizing": "border-box", "padding": "10px", "border-width": "3px"});
					attendanceDetailContainer.append(newTextArea);

					secondCol.append(attendanceDetailContainer);

					// Third column
					const removeBtnContainer = $("<div>").addClass("flex justify-center items-center w-full h-full");
					const newRemoveBtn = $("<button>").attr({"type": "button"}).addClass("remove-row text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold hover:bg-slate-800 hover:scale-105 active:bg-slate-900 focus:scale-95 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25").css({"box-shadow": "0 1px 2px rgba(0, 0, 0, 0.3)", "background": "linear-gradient(90deg, #1EB8CD 0%, #354D9B 100%)"}).html("<i class='bi bi-trash3'></i>");
					removeBtnContainer.append(newRemoveBtn);
					thirdCol.append(removeBtnContainer);

					trow.append(firstCol, secondCol, thirdCol);
					tbody.append(trow);

					exclude_dropdown.push(studentToAdd.id);

					applyHideAndUnhideProgress();
					applyRemoveRowButton();

					@php
						$counter++;
					@endphp

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
				const collectValues = () => {
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

					const [checkboxValues, materialProgressValues, learningStatusValues] = collectValues();

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
