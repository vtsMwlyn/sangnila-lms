<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>Sangnila Academy | LMS</title>
</head>

<body class="font-sans bg-cover h-screen bg-center bg-no-repeat"
    style="background-image: url({{ asset('img/background.png') }});">
    <x-navbar.teacher></x-navbar.teacher>

    <!-- Content Section -->
    <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-blue-900 mb-4">Upload New Attendance Data</h1>
		@if($course->students->count())
			<form action="{{ route('teacher.attendance.store', $course->id) }}" method="post" class="mx-auto" id="attendance_form">
				@csrf

				<table class="w-full">
					<thead>
						<th class="border px-5">Student Name</th>
						<th class="border px-5">Attendance Detail</th>
					</thead>
					<tbody>
						@foreach ($course->students as $student)
						{{--
							Note:
							Teacher can only submit attendance for active students account, if disabled by admin then the checkbox and textarea for that student account will be disabled (showing "account disabled")
						--}}
							<tr>
								<td class="border px-5">
									<div class="flex items-center gap-3">
										<input type="checkbox" id="checkbox{{ $loop->iteration }}"
										class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @endif @if($student->status == "disabled") disabled @endif>
										<span>{{ $student->full_name }}</span>
									</div>
								</td>
								<td class="border px-5">
									<div class="flex flex-col items-stretch">
										<textarea name="attendance_detail[]" rows="3" class="rounded-lg @error("attendance_detail." . $loop->index) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 @enderror" placeholder="Enter student attendance details" style="resize: none;" @if($student->status == "disabled") disabled @endif>@if($student->status == "disabled") Account disabled @endif {{ old("attendance_detail." . $loop->index) }}</textarea>

										@error("attendance_detail." . $loop->index)
											<span class="text-red-500 mt-2">{{ $message }}</span>
										@enderror
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				<div class="flex items-stretch gap-1 justify-end mt-4">
					<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
							Cancel
					</button>
					<x-button>
						{{ __('Submit') }}
					</x-button>
				</div>
			</form>
		@else
			<h1 class="text-md font-semibold italic">- No students assigned to this course yet, cannot upload assignment -</h1>
			<button type="button" onclick="history.back()" class="mt-6 px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
				Return
			</button>
		@endif
    </div>

	<script>
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
	</script>

</body>

</html>
