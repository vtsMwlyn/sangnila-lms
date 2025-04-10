@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title>Edit Attendance Data</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
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
						<x-button type="button" id="todaybtn" >Today</x-button>
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

			<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student's Attendance Data</th>
							<th class="text-start py-3 border-b-2 border-slate-400">Attendance Detail</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400"></th>
						</thead>
					<tbody id="table-body">
						@php
							$exclude_from_dropdown = [];
						@endphp

						@forelse (old('checkbox_value', $attendanceData) as $i => $ad)
							@php
								$studentId = is_object($ad)? $ad->student->id : $ad;
								$matProg = is_object($ad)? $ad->activity_progress : $ad;
								$learStat = is_object($ad)? $ad->learning_status : $ad;
								$attenDet = is_object($ad)? $ad->attendance_detail: $ad;
								$isAtten = is_object($ad)? $ad->is_attend : $ad;
								$isCustom = is_object($ad)? $ad->is_custom : $ad;

								array_push($exclude_from_dropdown, old('students.' . $i, $studentId));
							@endphp

							<tr class="table-row @if($loop->index % 2 == 0) bg-white @endif">
								<td class="grow p-5">
									<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
										<input type="checkbox" id="checkbox{{ old('students.' . $i, $studentId) }}"
										class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $i, $studentId) == "on") checked @elseif($isAtten == 1) checked @endif>
										<label for="checkbox{{ old('students.' . $i, $studentId) }}" class="flex gap-3 items-center">
											@if(App\Models\User::find(old('students.' . $i, $studentId))->details->profpic)
												<img src="{{ Storage::url("app/public/" . App\Models\User::find(old('students.' . $i, $studentId))->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
											@else
												<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
											@endif
											{{ App\Models\User::find(old('students.' . $i, $studentId))->full_name }}
										</label>
										<input type="hidden" name="students[]" value="{{ old('students.' . $i, $studentId) }}">
									</div>
									<div class="flex flex-col w-full gap-3 mt-2 activity_progress_detail items-start justify-between @error('activity_progress.' . $i) border-red rounded-2xl p-1 @enderror @error('learning_status.' . $i) border-red p-1 rounded-2xl @enderror">
										<div class="flex flex-col w-full container-select2">
											<x-select class="activity_progress select-2" name="fake_activity_progress[]">
												<option selected disabled>Select Activity Progress</option>
												@foreach ($attendance->course->topics as $topic)
													@foreach ($topic->activities as $activity)
														<option value="{{ $activity->title }}" @if(old('activity_progress.' . $i, $matProg) == $activity->title) selected @endif>{{ $activity->title }}</option>
													@endforeach
												@endforeach
												<option value="other" @if(old("activity_progress." . $i) == "other" || $isCustom == 1) selected @endif>Other</option>
											</x-select>
										</div>

										<x-select class="learning_status w-full" name="fake_learning_status[]">
											<option selected disabled>Select Status</option>
											<option value="On Progress" @if(old('learning_status.' . $i, $learStat) == "On Progress") selected @endif>On Progress</option>
											<option value="Done" @if(old('learning_status.' . $i, $learStat) == "Done") selected @endif>Done</option>
										</x-select>
									</div>

									@error("learning_status." . $i)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror
									@error("activity_progress." . $i)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror

									<div class="mt-2 @error('other_activity.' . $i) border-red rounded-2xl p-1 @enderror">
										<x-input name="other_activity[]" type="text" class="w-full hidden other-activity" placeholder="Input activity/activity" value="{{ old('other_activity.' . $i, ($isCustom == 1 ? $matProg : '')) }}"/>
									</div>
									@error("other_activity." . $i)
										<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
									@enderror
								</td>
								<td class="w-1/2 py-5">
									<div class="flex flex-col items-stretch">
										<textarea name="attendance_detail[]" rows="8" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $i) border-red focus:border-red-700 focus:ring-0 @else border-slate-400 focus:border-slate-600 focus:ring-0 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px; border-width: 3px;">{!! old("attendance_detail." . $i, $attenDet) !!}</textarea>
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

						@php
							$exclude_from_dropdown = array_map('intval', $exclude_from_dropdown);
						@endphp
					</tbody>
				</table>
			</div>

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>

		@php
			$all_topics_and_activities = [];
			foreach($attendance->course->topics as $topic){
				$tm = [];
				$m = [];

				$tm["topic"] = $topic;

				foreach($topic->activities as $activity){
					array_push($m, $activity);
				}

				$tm["activities"] = $m;

				array_push($all_topics_and_activities, $tm);
			}
		@endphp

		<script>
			@php
				$counter = $attendanceData->count();
			@endphp

			const allStudents = @json($all_students);
			let exclude_dropdown = @json($exclude_from_dropdown);

			$(document).ready(() => {
				const itemListModifiedEvent = new Event("item_list_modified");
				$(document).on("item_list_modified", () => {
					refreshAddStudent();
					reapplyEventListeners();
					reinitializeselect2();
				});

				// Initialization
				applyHideAndUnhideProgress();
				applyRemoveRowButton();
				refreshAddStudent();

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

				function refreshAddStudent(){
					$("#student_add").html("");
					if(exclude_dropdown.length < allStudents.length){
						for(let std of allStudents){
							console.log(std.id);
							if(!exclude_dropdown.includes(std.id) && std.status != "disabled"){
								$("#student_add").append($("<option>").attr({"value": JSON.stringify(std)}).text(std.full_name));
								console.log(`${std.full_name} will be included.`);
							} else {
								console.log(`${std.full_name} should be excluded.`);
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

				// Mechanism to hide and unhide selects for activity progress detail depending if the student name checkbox is checked or not
				function toggleProgress(element) {
					const correspondingDetail = $(element).closest('td').find('.activity_progress_detail');
					const otherActivity = $(element).closest('td').find('.other-activity');

					if ($(element).is(":checked")) {
						correspondingDetail.css("display", "flex");
						if(correspondingDetail.find('.activity_progress').val() == "other"){
							otherActivity.css("display", "flex");
						}
					}
					else {
						correspondingDetail.css("display", "none");
						otherActivity.css("display", "none");
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

				$(".activity_progress").change(function(){
					if($(this).val() == "other"){
						$(this).closest(".activity_progress_detail").next().find(".other-activity").show();
					}
					else {
						$(this).closest(".activity_progress_detail").next().find(".other-activity").hide();
					}
				});

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
					const progressContainer = $("<div>").addClass("flex flex-col w-full gap-2 mt-2 activity_progress_detail @error('activity_progress.' . $counter) border-red rounded-2xl p-1 @enderror @error('learning_status.' . $counter) border-red p-1 rounded-2xl @enderror");

					const studentToAdd = JSON.parse($("#student_add").val());
					const newCheckBox = $("<input>").attr({"type": "checkbox", "id": `checkbox${studentToAdd.id}`}).addClass("mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300");
					const newLabelCheckBox = $("<label>").attr({"for": `checkbox${studentToAdd.id}`}).text(studentToAdd.full_name);
					const newHiddenInput = $("<input>").attr({"type": "hidden", "name": "students[]", "value": studentToAdd.id});
					checkNameContainer.append(newCheckBox).append(newLabelCheckBox).append(newHiddenInput);

					const selectContainer = $("<div>").addClass("flex flex-col w-full container-select2");
					const newActivityProgressDropdown = $("<select>").addClass("select-2 activity_progress w-full rounded-2xl shadow-sm focus:outline-none py-2 px-4 border-slate-400 focus:border-slate-600 focus:ring-0").css("border-width", "3px");
					const activityProgressDropdownPlaceholder = $("<option>").attr({"disabled": true, "selected": true}).text("Select Activity Progress");
					newActivityProgressDropdown.append(activityProgressDropdownPlaceholder);
					const allTopicsAndActivities = @json($all_topics_and_activities);
					for(let tm of allTopicsAndActivities){
						for(let activity of tm.activities){
							const newActivityProgressDropdownOption = $("<option>").attr({"value": activity.title}).text(activity.title);
							newActivityProgressDropdown.append(newActivityProgressDropdownOption);
						}
					}
					newActivityProgressDropdown.append($("<option>").attr("value", "other").text("Other"));

					const newLearningStatusDropdown = $("<select>").addClass("learning_status w-full rounded-2xl shadow-sm focus:outline-none py-2 px-4 border-slate-400 focus:border-slate-600 focus:ring-0").css("border-width", "3px");
					const learningStatusPlaceholder = $("<option>").attr({"selected": true, "disabled": true}).text("Select Status");
					const learningStatusDropdownOption1 = $("<option>").attr({"value": "On Progress"}).text("On Progress");
					const learningStatusDropdownOption2 = $("<option>").attr({"value": "Done"}).text("Done");
					newLearningStatusDropdown.append(learningStatusPlaceholder).append(learningStatusDropdownOption1).append(learningStatusDropdownOption2);

					progressContainer.append(selectContainer.append(newActivityProgressDropdown)).append(newLearningStatusDropdown);

					const otherActivityContainer = $("<div>").addClass("mt-2");
					const otherActivityInput = $("<input>").attr({"type": "text", "placeholder": "Input activity/activity", "name": "other-activity[]"}).addClass("w-full hidden other-activity rounded-2xl shadow-sm focus:outline-none py-2 px-4 border-slate-400 focus:border-slate-600 focus:ring-0 ");
					otherActivityContainer.append(otherActivityInput);

					firstCol.append(checkNameContainer).append(progressContainer).append(otherActivityContainer);

					// Second column
					const attendanceDetailContainer = $("<div>").addClass("flex flex-col items-stretch");
					const newTextArea = $("<textarea>").attr({"rows": 8, "name": "attendance_detail[]", "placeholder": "Enter student in class progress"}).addClass("rounded-xl border-2 font-semibold text-blue-900 @error('attendance_detail.' . $counter) border-red focus:border-red-700 focus:ring-0 @else border-slate-400 focus:border-slate-600 focus:ring-0 @enderror").css({"resize": "none", "box-sizing": "border-box", "padding": "10px", "border-width": "3px"});
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
					const activityProgressValues = [];
					const learningStatusValues = [];

					checkboxes.each(function(){
						const correspondingDetail = $(this).closest('td').find('.activity_progress_detail');

						if($(this).is(":disabled")){
							checkboxValues.push('off');
							activityProgressValues.push("Absent");
							learningStatusValues.push("Absent");
						} else {
							const cb = $(this).is(":checked") ? 'on' : 'off';
							checkboxValues.push(cb);

							if(cb == "on"){
								activityProgressValues.push(correspondingDetail.find('.activity_progress').val());
								learningStatusValues.push(correspondingDetail.find('.learning_status').val());
							}
							else {
								activityProgressValues.push("Absent");
								learningStatusValues.push("Absent");
							}

						}
					});

					return [checkboxValues, activityProgressValues, learningStatusValues];
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

					const [checkboxValues, activityProgressValues, learningStatusValues] = collectValues();

					checkboxValues.forEach((value, index) => {
						const hiddenInput1 = $("<input>").attr({"type": "hidden", "name": "checkbox_value[]", "value": value});
						const hiddenInput2 = $("<input>").attr({"type": "hidden", "name": "activity_progress[]", "value": activityProgressValues[index]});
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
