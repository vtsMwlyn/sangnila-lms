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
        <h1 class="text-3xl font-semibold text-blue-900 mb-4">Upload New Assignment to {{ $course->course_name }}</h1>
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
			{{-- <div class="flex w-full gap-3">
				<div style="width: 50%">
					@for ($i = 0; $i < $course->students->count() / 2; $i++)
						<div class="flex items-center gap-3 mt-2 mb-2 ml-5 mr-5 border rounded-lg p-5">
							<input type="checkbox" id="checkbox{{ $i + 1 }}"
							class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $i) == "on") checked @endif>
							<label for="checkbox{{ $i + 1 }}">{{ $course->students[$i]->full_name }}</label>
						</div>
					@endfor
				</div>
				<div style="width: 50%">
					@for ($i = $course->students->count() / 2 + 1; $i < $course->students->count(); $i++)
						<div class="flex items-center gap-3 mt-2 mb-2 ml-5 mr-5 border rounded-lg p-5">
							<input type="checkbox" id="checkbox{{ $i + 1 }}"
							class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $i) == "on") checked @endif>
							<label for="checkbox{{ $i + 1 }}">{{ $course->students[$i]->full_name }}</label>
						</div>
					@endfor
				</div>
			</div> --}}
			@error("checkbox_value")
				<p class="text-red-500 mt-3">{{ $message }}</p>
			@enderror
			<div class="flex flex-wrap gap-3 mt-2 rounded-lg @error("checkbox_value") border p-5 border-red-500 @enderror">
				@foreach ($course->students as $student)
					<div class="flex items-center gap-3 border rounded-lg p-5" style="width: 30%">
						<input type="checkbox" id="checkbox{{ $loop->iteration }}"
						class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 bg-gray-300" @if(old('checkbox_value.' . $loop->index) == "on") checked @endif>
						<label for="checkbox{{ $loop->iteration }}">{{ $student->full_name }}</label>
					</div>
				@endforeach
			</div>

            <div class="flex items-center justify-end mt-6">
                <x-button>
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </div>

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
			checkboxes.forEach((checkbox) => {
				checkbox.checked = true;
			});
		})
	</script>

</body>

</html>
