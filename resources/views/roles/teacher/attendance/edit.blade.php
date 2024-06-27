@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">Edit Attendance Data</x-page-title>
		<form action="{{ route('teacher.attendance.update', $attendance->id) }}" method="post" class="mx-auto" id="attendance_form">
			@csrf
			<div class="my-4">
				<div class="flex items-center gap-3 mt-1">
					<x-input onfocus="this.type='date'" onblur="this.type='text'" name="attendance_date" id="attendance_date" :value="$attendance->attendance_date" class="w-1/3"/>
					<x-button type="button" id="todaybtn" class="bg-orange-500">Today</x-button>
				</div>
			</div>

			<div class="overflow-x-auto">
				<table class="w-full">
					<tbody>
						@foreach ($attendanceData as $atd)
							{{--
								Note:
								Even tought teacher can only submit attendance for active students account, previously submitted attendance for the student account still can be edited(?)
							--}}
							<tr style="@if($atd->attendance_detail == "Account disabled") display: none; @endif">
								<td class="px-5 w-1/3">
									<div class="flex items-center gap-3 bg-white py-4 px-5 border-2 border-blue-900 rounded-xl">
										<input type="checkbox" id="checkbox{{ $loop->iteration }}"
										class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @elseif($atd->is_attend == 1) checked @endif @if($atd->attendance_detail == "Account disabled") disabled @endif>
										<span>{{ $atd->student->full_name }}</span>
									</div>
								</td>
								<td class="px-5 py-3">
									<div class="flex flex-col items-stretch">
										<textarea name="attendance_detail[]" rows="3" class="rounded-xl border-2 font-semibold text-blue-900 @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @else border-blue-900 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50 @enderror" placeholder="Enter student attendance details" style="resize: none; box-sizing: border-box; padding: 10px;">{{ old("attendance_detail." . $loop->index, $atd->attendance_detail) }}</textarea>
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
				<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/6">
					Cancel
				</x-button>
			</div>
		</form>

		<script>
			$("#attendance_date").on({
				"focus": function(){
					this.showPicker();
				},
				"click": function(){
					this.showPicker();
				}
			});

			const collectCheckboxValues = () => {
				const checkboxes = document.querySelectorAll('input[type="checkbox"]');
				const checkboxValues = [];
				checkboxes.forEach((checkbox) => {
					if(checkbox.disabled){
						checkboxValues.push('off');
					} else {
						checkboxValues.push(checkbox.checked ? 'on' : 'off');
					}
				});
				return checkboxValues;
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

			// Example of using the function when submitting the form
			const form = document.querySelector('#attendance_form');
			form.addEventListener('submit', (event) => {
				event.preventDefault(); // Prevent the form from submitting normally
				const checkboxValues = collectCheckboxValues();
				// Create a hidden input field in the form
				const hiddenInput = document.createElement('input');
				hiddenInput.type = 'hidden';
				hiddenInput.name = 'checkbox_value[]'; // Make sure to use [] in the name to indicate an array
				checkboxValues.forEach((value, index) => {
					const inputValue = document.createElement('input');
					inputValue.type = 'hidden';
					inputValue.name = 'checkbox_value[]';
					inputValue.value = value;
					form.appendChild(inputValue);
				});

				processDisabledTextAreas();

				// Now you can submit the form with the additional hidden input containing checkbox values
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
