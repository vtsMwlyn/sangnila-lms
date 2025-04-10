@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title>Edit Assignment "{{ $assignment->title }}"</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.assignment.update', $assignment->id) }}" method="post" id="assignment_form">
			@csrf
			@method("patch")

			<div class="flex w-full gap-2 md:gap-5 flex-col md:flex-row">
				{{-- Assignment Title --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="title">Assignment Title<span class="text-red">*</span></x-label>
					<x-input id="title" class="block w-full" type="text" name="title" placeholder="Enter title"
						:value="old('title', $assignment->title)" autofocus />
				</div>

				{{-- Deadline Time --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="deadline_time">Deadline Time<span class="text-red">*</span></x-label>
					<x-input id="deadline_time" class="block w-full" type="time" name="deadline_time" placeholder="Enter deadline time" :value="old('deadline_time', $assignment->deadline_time)" />
				</div>
			</div>

			<div class="flex w-full gap-2 md:gap-5 flex-col md:flex-row mt-4">
				{{-- Link --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="link">Link<span class="text-red">*</span></x-label>
					<x-input id="link" class="block w-full" type="text" name="link" placeholder="Enter link" :value="old('link', $assignment->link)" />
				</div>

				{{-- Deadline Date --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="deadline_date">Deadline Date<span class="text-red">*</span></x-label>
					<x-input id="deadline_date" class="block w-full date-input" type="date" name="deadline_date" placeholder="Enter deadline date" :value="old('deadline_date', $assignment->deadline_date)" />
				</div>
			</div>

			{{-- Description --}}
			<div class="flex flex-col w-full mt-4">
				<x-label for="desc">Description<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="desc" class="block w-full" type="text" name="desc" placeholder="Enter description">{!! old('desc', $assignment->desc) !!}</x-textarea>
			</div>

			{{-- Select Students to Assign --}}
			<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
			<div class="w-full flex items-center justify-between">
				<h2 class="my-4 font-extrabold text-xl text-dark-blue">Select students to assign<span class="text-red">*</span></h2>
				<div class="flex gap-3">
					<x-button  type="button" id="checkall">Assign to all</x-button>
					<x-button class="bg-slate-600" type="button" id="undo" style="display: none;">Undo</x-button>
				</div>
			</div>
			<div class="w-full bg-slate-400 " style="height: 2px;"></div>

			<div class="flex flex-wrap gap-3 p-3 mt-5 @error("checkbox_value") border-2 p-5 border-red @enderror">
				@foreach ($course_students as $i => $cs)
					@if($cs->student->status == "disabled")
						@continue
					@endif

					<div class="flex items-center gap-3 checkbox-container w-full md:w-[32%] xl:w-[23%]">
						<input type="checkbox" id="checkbox{{ $i }}"
						class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $i) == "on" ) checked @elseif($checkboxes_values[$i] == "on") checked @endif>
						@if($cs->student->details->profpic)
							<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
						@else
							<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
						@endif
						<label for="checkbox{{ $i }}">{{ $cs->student->full_name }}</label>
					</div>
				@endforeach
			</div>

			@error("checkbox_value")
				<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
			@enderror

			<div class="flex items-stretch gap-2 justify-end w-full mt-10">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>

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
