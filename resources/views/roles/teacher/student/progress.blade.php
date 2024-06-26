@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ $student->full_name }}'s Progress</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mb-8 text-center">In Course: {{ $course->course_name }}</h1>

		@if(session()->has("successUpdateProgress"))
			<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
				<p class="text-green-900">{{ session("successUpdateProgress") }}</p>
			</div>
		@endif

		<div class="overflow-x-auto rounded-md mt-8">
			<form method="post" action="{{ route("teacher.student.update.progress", [$course->id, $student->id]) }}" id="student_progress">
				@csrf
				@method('patch')
				<x-table>
					<x-slot name="head">
						<th class="template-heads rounded-l-xl">Topic</th>
						<th class="template-heads">Material Name</th>
						<th class="template-heads">Access</th>
						<th class="template-heads rounded-r-xl">Action</th>
					</x-slot>

					@if ($newestprogress->isNotEmpty())
						@foreach ($newestprogress as $progress)
							<tr>
								<td class="template-bodies rounded-l-xl">
									<a href="">
										{{ $progress->material->topic->title }}
									</a>
								</td>

								<td class="template-bodies">
									<a href="">
										{{ $progress->material->title }}
									</a>
								</td>

								<td class="template-bodies">
									{{ $progress->status }}
								</td>
								<td class="template-bodies rounded-r-xl">
									<input type="checkbox" name="access" id="material_progress_{{ $progress->id }}"
									class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300"
									@if ($progress->status === 'unlocked') checked @endif>
								</td>
							</tr>
						@endforeach
					@else
						<tr class="text-blue-900"><td colspan="3" class="text-center">N/A</td></tr>
					@endif
				</x-table>

				<div class="flex gap-2 mt-10 mb-3 w-full justify-center">
					<x-button class="bg-orange-500 w-full md:w-1/6">Save</x-button>
					<x-button class="bg-orange-500 w-full md:w-1/6" type="button" onclick="if(confirm('All changes will be discarded, are you sure want to cancel?'))history.back();">Cancel</x-button>
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
	</x-section-container>
@endsection
