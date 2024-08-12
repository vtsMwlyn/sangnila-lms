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
        <x-page-title class="text-3xl font-semibold text-blue-900 mt-5 mb-8">Upload New Attendance Data</x-page-title>
		@if($course_students->count() && $course->topics->count())
			<form action="{{ route('teacher.attendance.store', $course->id) }}" method="post" class="mx-auto" id="attendance_form">
				@csrf

				<div class="my-4">
					<x-label for="attendance_date">{{ __("Attendance Date") }}</x-label>
					<div class="flex items-center gap-3 mt-1">
						<div class="flex flex-col w-1/3">
							<x-input onfocus="this.type='date'" onblur="this.type='text'" name="attendance_date" id="attendance_date" placeholder="Enter attendance date"/>
						</div>
						<x-button type="button" id="todaybtn" class="bg-orange-500">Today</x-button>
					</div>
				</div>

				<div class="mt-6 overflow-x-auto">
					<x-label>{{ __("Attendance Details") }}</x-label>
					<table class="w-full mt-1" style="border-collapse: separate; border-spacing: 0 20px;">
						<tbody>
							@foreach ($course_students as $cs)
								<tr style="@if($cs->student->status == "disabled") display: none; @endif background: rgba(256, 256, 256, 0.4);">
									<td class="p-5 w-1/2 rounded-l-xl">
										<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
											<input type="checkbox" id="checkbox{{ $loop->iteration }}"
											class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @endif >
											<label for="checkbox{{ $loop->iteration }}">{{ $cs->student->full_name }}</label>
										</div>
										<div class="flex w-full gap-2 mt-2 material_progress_detail" style="display: none;">
											<x-select class="material_progress w-2/3" name="fake_material_progress[]">
												<option selected disabled>Select Material Progress</option>
												@foreach ($course->topics as $topic)
													@foreach ($topic->materials as $material)
														<option value="{{ $material->title }}" @if(old("material_progress[]") == $material->title) selected @endif>{{ $material->title }}</option>
													@endforeach
												@endforeach
											</x-select>

											<x-select class="learning_status w-1/3" name="fake_learning_status[]">
												<option value="On Progress" @if(old("learning_status[]") == "On Progress") selected @endif>On Progress</option>
												<option value="Done" @if(old("learning_status[]") == "Done" ) selected @endif>Done</option>
											</x-select>
										</div>
									</td>
									<td class="p-5 rounded-r-xl w-1/2">
										<div class="flex flex-col items-stretch">
											<textarea name="attendance_detail[]" rows="4" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student in class progress" style="resize: none; box-sizing: border-box; padding: 10px;">@if($cs->student->status == "disabled"){{ __("Account disabled") }}@else{{ old("attendance_detail." . $loop->index) }}@endif</textarea>

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
					<x-cancel-button msg="The filled data will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
						Cancel
					</x-cancel-button>
				</div>
			</form>
		@else
			<div class="bg-blue-900 rounded-xl p-8">
				<h1 class="font-semibold italic text-white">- No students assigned or topics and materials added to this course yet, cannot upload assignment -</h1>
				<x-button type="button" onclick="history.back()" class="bg-slate-600 mt-5">
					Return
				</x-button>
			</div>
		@endif

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

				$invalid = false;

				$("#attendance_date").css({"border": "rgb(30 58 138) solid 2px"});
				$("textarea").css({"border": "rgb(30 58 138) solid 2px"});
				$(".material_progress").each(function(){
					$(this).css({"border": "rgb(30 58 138) solid 2px"});
				})

				if(!$("#attendance_date").val()){
					$("#attendance_date").css({"border": "red solid 2px"});
					$("#attendance_date").after($("<p>").text("The attendance date field is required.").css("color", "red"));
					$invalid = true;
				}

				$("textarea").each(function(){
					if($(this).val() == ""){
						$(this).after($("<p>").text("The attendance detail field is required.").css("color", "red"));
						$(this).css({"border": "red solid 2px"});
						$invalid = true;
					}
				});

				checkboxValues.forEach((value, index) => {
					if(value === "on" && !materialProgressValues[index]){
						$($(".material_progress")[index]).css({"border": "red solid 2px"});
						$($(".material_progress")[index]).after($("<p>").text("The material progress field is required.").css("color", "red"));
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
		</script>
	</x-section-container>
@endsection
