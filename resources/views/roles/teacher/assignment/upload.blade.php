@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.assignment.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Upload</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Upload New Assignment to {{ $course->course_name }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		@if($course_students->count())
			<form action="{{ route('teacher.assignment.store', $course->id) }}" method="post" id="assignment_form">
				@csrf
				<!-- Assignment Title -->
				<div class="mb-4 flex @error('title') items-start @else items-stretch @enderror w-full gap-3">
					<x-boxed-label for="title" :value="__('Assignment Title')" />
					<div class="flex flex-col w-full items-stretch">
						<x-input id="title" class="block w-full" type="text" name="title" :value="old('title')" placeholder="Enter title" autofocus />
					</div>
				</div>

				<!-- Description -->
				<div class="mb-4 flex @error('desc') items-start @else items-stretch @enderror w-full gap-3">
					<x-boxed-label for="desc" :value="__('Description')" />
					<div class="flex flex-col w-full items-stretch">
						<x-input id="desc" class="block w-full" type="text" name="desc" :value="old('desc')" placeholder="Enter description"/>
					</div>
				</div>

				<!-- Link -->
				<div class="mb-4 flex @error('link') items-start @else items-stretch @enderror w-full gap-3">
					<x-boxed-label for="link" :value="__('Link')" />
					<div class="flex flex-col w-full items-stretch">
						<x-input id="link" class="block w-full" type="text" name="link" :value="old('link')" placeholder="Enter link"/>
					</div>
				</div>

				<!-- Deadline Date -->
				<div class="mb-4 flex @error('deadline_date') items-start @else items-stretch @enderror w-full gap-3">
					<x-boxed-label for="deadline_date" :value="__('Deadline Date')" />
					<div class="flex flex-col w-full items-stretch">
						<x-input id="deadline_date" class="block w-full date-input" type="date" name="deadline_date" placeholder="Enter deadline date" :value="old('deadline_date')"/>
					</div>
				</div>

				<!-- Deadline Time -->
				<div class="mb-4 flex @error('deadline_time') items-start @else items-stretch @enderror w-full gap-3">
					<x-boxed-label for="deadline_time" :value="__('Deadline Time')" />
					<div class="flex flex-col w-full items-stretch">
						<x-input id="deadline_time" class="block w-full" onblur="this.type = 'text';" onfocus="this.type = 'time';" name="deadline_time" :value="old('deadline_time')" placeholder="Enter deadline time"/>
					</div>
				</div>

				<!-- Select Students to Assign -->
				<div class="flex items-center gap-3 mt-8">
					<x-label :value="__('Pick students to assign or')"></x-label>
					<div class="flex gap-3">
						<x-button class="bg-orange-500" type="button" id="checkall">Assign to all</x-button>
						<x-button class="bg-slate-600" type="button" id="undo" style="display: none;">Undo</x-button>
					</div>
				</div>

				@error("checkbox_value")
					<p class="text-red-800 font-bold mt-3">{{ $message }}</p>
				@enderror

				<div class="flex flex-wrap gap-3 p-3 mt-5 border-2 border-blue-800 rounded-xl bg-white @error("checkbox_value") border p-5 border-red-700 @enderror">
					@foreach ($course_students as $cs)
						@if($cs->student->status == "disabled")
							@continue
						@endif
						<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
							<input type="checkbox" id="checkbox{{ $loop->iteration }}"
							class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @endif>
							<label for="checkbox{{ $loop->iteration }}">{{ $cs->student->full_name }}</label>
						</div>
					@endforeach
				</div>

				<div class="flex items-stretch gap-2 justify-center w-full mt-16 mb-3">
					<x-button class="bg-orange-500 w-full md:w-1/6">
						{{ __('Submit') }}
					</x-button>
					<x-cancel-button class="w-full md:w-1/6">
						Cancel
					</x-cancel-button>
				</div>
			</form>
		@else
			<div class="bg-blue-900 rounded-xl p-8">
				<h1 class="font-semibold italic text-white">- No students assigned to this course yet, cannot upload assignment -</h1>
				<x-button type="button" onclick="history.back()" class="bg-slate-600 mt-5">
					Return
				</x-button>
			</div>
		@endif

		<script>
			$(document).ready(() => {
				let prevCheckBoxValues = [];

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

					prevCheckBoxValues = collectCheckboxValues();

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

					$("#undo").show();
				});

				$("#undo").click(function(){
					const checkboxes = $('input[type="checkbox"]');
					checkboxes.each((i, checkbox) => {
						if(prevCheckBoxValues[i] == "on"){
							checkbox.checked = true;
						} else {
							checkbox.checked = false;
						}
						i++;
					});

					$(this).hide();
				});
			});
		</script>
	</x-section-container>
@endsection
