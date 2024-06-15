@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Progress</h1>
	<h1 class="text-xl font-semibold text-blue-900 mb-4">In Course: {{ $course->course_name }}</h1>

	@if(session()->has("successUpdateProgress"))
		<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateProgress") }}</p>
		</div>
	@endif

	<div class="overflow-x-auto rounded-md mt-8">
		<form method="POST" action="{{ route("teacher.student.update.progress", [$course->id, $student->id]) }}" id="student_progress">
			@csrf
			@method('PATCH')
			<table class="min-w-full bg-white border-collapse ">
				<thead>
					<tr>
						<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 text">Topic</td>
						<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 text">Material Name</td>
						<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 text">Access</td>
						<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 text">Action</td>
					</tr>
				</thead>
				<tbody>
					@if ($newestprogress->isNotEmpty())
						@foreach ($newestprogress as $progress)
							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
									<a href="">
										{{ $progress->material->topic->title }}
									</a>
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
									<a href="">
										{{ $progress->material->title }}
									</a>
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
									{{ $progress->status }}
								</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
									<input type="checkbox" name="access" id="material_progress_{{ $progress->id }}"
									class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 {{ $progress->status === 'unlocked' ? 'bg-blue-500' : 'bg-gray-300' }}"
									@if ($progress->status === 'unlocked') checked @endif>
								</td>
							</tr>
						@endforeach
					@else
						<tr class="text-blue-900"><td colspan="3" class="text-center">N/A</td></tr>
					@endif
				</tbody>
			</table>
			<div class="flex gap-1 mt-5 w-full justify-end">
				<x-button class="bg-indigo-400">Save</x-button>
				<x-button class="bg-indigo-400" type="button" onclick="if(confirm('All changes will be discarded, are you sure want to cancel?'))history.back();">Cancel</x-button>
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
		const form = document.querySelector('#student_progress');
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
	</script>
@endsection
