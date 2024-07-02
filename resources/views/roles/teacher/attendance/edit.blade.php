@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">Edit Attendance Data</x-page-title>

		@if(session()->has("failedEditAttendance"))
			<x-badge-danger badge_text="{{ session('failedEditAttendance') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.attendance.update', $attendance->id) }}" method="post" class="mx-auto" id="attendance_form">
			@csrf
			<div class="my-4">
				<x-label for="attendance_date">{{ __("Attendance Date") }}</x-label>
				<div class="flex items-center gap-3 mt-1">
					<x-input onfocus="this.type='date'" onblur="this.type='text'" name="attendance_date" id="attendance_date" :value="$attendance->attendance_date" class="w-1/3"/>
					<x-button type="button" id="todaybtn" class="bg-orange-500">Today</x-button>
				</div>
			</div>

			<div class="overflow-x-auto mt-6">
				<x-label>{{ __("Attendance Details") }}</x-label>
				<table class="w-full" style="border-collapse: separate; border-spacing: 0 20px;">
					<tbody>
						@foreach (App\Models\CourseStudent::where("course_id", $attendance->course_id)->where("teacher_id", Auth::user()->id)->get() as $course_student)
							@php
								$atd = $attendanceData->where("user_id", $course_student->student->id)->first();
							@endphp

							<tr style="@if($atd && $atd->attendance_detail == "Account disabled") display: none; @endif background: rgba(256, 256, 256, 0.4);">
								<td class="p-5 w-1/2 rounded-l-xl">
									<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
										<input type="checkbox" id="checkbox{{ $loop->iteration }}"
										class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @elseif($atd && $atd->is_attend == 1) checked @endif @if($atd && $atd->attendance_detail == "Account disabled") disabled @endif>
										<label for="checkbox{{ $loop->iteration }}">{{ $course_student->student->full_name }}</label>
									</div>
									<div class="flex w-full gap-2 mt-2 material_progress_detail">
										<x-select class="material_progress w-2/3">
											<option selected disabled>Select Material Progress</option>
											@foreach ($attendance->course->topics as $topic)
												@foreach ($topic->materials as $material)
													<option value="{{ $material->title }}" @if($atd && $atd->material_progress == $material->title) selected @endif>{{ $material->title }}</option>
												@endforeach
											@endforeach
										</x-select>

										<x-select class="learning_status w-1/3">
											<option value="On Progress" @if($atd && $atd->learning_status == "On Progress") selected @endif>On Progress</option>
											<option value="Done" @if($atd && $atd->learning_status == "Done") selected @endif>Done</option>
										</x-select>
									</div>
								</td>
								<td class="p-5 w-1/2 rounded-r-xl">
									<div class="flex flex-col items-stretch">
										@if($atd)
											<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student attendance details" style="resize: none; box-sizing: border-box; padding: 10px;">{{ old("attendance_detail." . $loop->index, $atd->attendance_detail) }}</textarea>
										@else
											<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student attendance details" style="resize: none; box-sizing: border-box; padding: 10px;"></textarea>
										@endif
										@error("attendance_detail." . $loop->index)
											<span class="text-red-500 mt-2">{{ $message }}</span>
										@enderror
									</div>
								</td>
							</tr>
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

		<script>
			$(document).ready(() => {
				$("#attendance_date").on({
					"focus": function(){
						this.showPicker();
					},
					"click": function(){
						this.showPicker();
					}
				});

				// Mechanism to hide and unhide selects for material progress detail depending if the student name checkbox is checked or not
				const allCheckBoxes = $('input[type="checkbox"]');

				allCheckBoxes.each(function() {
					const correspondingDetail = $(this).closest('td').find('.material_progress_detail');

					if ($(this).is(":checked")) {
						correspondingDetail.css("display", "flex");
					}
					else {
						correspondingDetail.css("display", "none");
					}
				});

				allCheckBoxes.change(function(){
					const correspondingDetail = $(this).closest('td').find('.material_progress_detail');

					if ($(this).is(":checked")) {
						correspondingDetail.css("display", "flex");
					}
					else {
						correspondingDetail.css("display", "none");
					}
				});
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
		</script>
	</x-section-container>
@endsection
