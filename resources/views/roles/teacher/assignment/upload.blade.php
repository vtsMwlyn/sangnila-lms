@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Upload New Assignment to {{ $course->course_name }}</h1>
	@if($course_students->count())
		<form action="{{ route('teacher.assignment.store', $course->id) }}" method="post" class="mx-auto" id="assignment_form">
			@csrf
			<!-- Assignment Title -->
			<div class="mb-4">
				<x-label for="title" :value="__('Assignment Title')" />
				<x-input id="title" class="block mt-1 w-full" type="text" name="title"
					:value="old('title')" autofocus />
			</div>

			<!-- Assignment Description -->
			<div class="mb-4">
				<x-label for="desc" :value="__('Assignment Description')" />
				<x-input id="desc" class="block mt-1 w-full" type="text" name="desc"
					:value="old('desc')" />
			</div>

			<!-- Assignment Link -->
			<div class="mb-4">
				<x-label for="link" :value="__('Assignment Link')" />
				<x-input id="link" class="block mt-1 w-full" type="text" name="link"
					:value="old('link')" />
			</div>

			<!-- Assignment Deadline Date -->
			<div class="mb-4">
				<x-label for="deadline_date" :value="__('Assignment Deadline Date')" />
				<x-input id="deadline_date" class="block mt-1 w-full" type="date" name="deadline_date"
					:value="old('deadline_date')" />
			</div>

			<!-- Assignment Deadline Time -->
			<div class="mb-4">
				<x-label for="deadline_time" :value="__('Assignment Deadline Time')" />
				<x-input id="deadline_time" class="block mt-1 w-full" type="time" name="deadline_time"
					:value="old('deadline_time')" />
			</div>

			<!-- Select Students to Assign -->
			<div class="flex items-center gap-3">
				<p>Pick students to assign or</p>
				<button class="px-5 py-2 text-white bg-indigo-400 rounded-lg border" id="checkall">Assign to all</button>
			</div>

			@error("checkbox_value")
				<p class="text-red-500 mt-3">{{ $message }}</p>
			@enderror

			<div class="flex flex-wrap gap-3 mt-2 rounded-lg @error("checkbox_value") border p-5 border-red-500 @enderror">
				@foreach ($course_students as $cs)
					{{--
						Note:
						Teacher can only assign assigments / edit assigning status to active student accounts
					--}}
					<div class="flex items-center gap-3 border rounded-lg p-5 checkbox-container" style="width: 30%; @if($cs->student->status == "disabled") display: none; @endif">
						<input type="checkbox" id="checkbox{{ $loop->iteration }}"
						class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @endif>
						<label for="checkbox{{ $loop->iteration }}">{{ $cs->student->full_name }}</label>
					</div>
				@endforeach
			</div>

			<div class="flex items-stretch gap-1 justify-end mt-6">
				<x-button class="bg-indigo-400">
					{{ __('Submit') }}
				</x-button>
				<x-button type="button" onclick="if(confirm('The filled data will be discarded, are you sure want to cancel?')) history.back();" class="bg-indigo-400">
					Cancel
				</x-button>
			</div>
		</form>
	@else
		<h1 class="text-md font-semibold italic">- No students assigned to this course yet, cannot upload assignment -</h1>
		<button type="button" onclick="history.back()" class="mt-6 px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
			Return
		</button>
	@endif

	<script>
		const collectCheckboxValues = () => {
			const checkboxes = document.querySelectorAll('input[type="checkbox"]');
			const checkboxValues = [];
			checkboxes.forEach((checkbox) => {
				checkboxValues.push(checkbox.checked ? 'on' : 'off');
			});
			return checkboxValues;
		}

		// Example of using the function when submitting the form
		const form = document.querySelector('#assignment_form');
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
			// Now you can submit the form with the additional hidden input containing checkbox values
			form.submit();
		});

		const checkAllBtn = document.querySelector("#checkall");
		checkAllBtn.addEventListener("click", (e) => {
			e.preventDefault();
			const checkboxes = document.querySelectorAll('input[type="checkbox"]');
			const checkboxContainers = document.querySelectorAll(".checkbox-container");
			let i = 0;
			checkboxes.forEach((checkbox) => {
				if(checkboxContainers[i].style.display != "none"){
					checkbox.checked = true;
				} else {
					checkbox.checked = false;
				}
				i++;
			});
		})
	</script>
@endsection
