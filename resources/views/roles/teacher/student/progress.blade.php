@extends("layouts.main-teacher")

@section("title")
	<h1>Material Access</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.student.select-course') }}" class="text-yellow-500 font-bold">Select Course</a>
	> <span>{{ $course->course_name }}</span>
	> <a href="{{ route('teacher.student.select-student', $course->id) }}" class="text-yellow-500 font-bold">Select Student</a>
	> <span>{{ $student->full_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $student->full_name }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">In Course: {{ $course->course_name }}</h1>

		@if(session()->has("successUpdateProgress"))
			<x-badge-success badge_text="{{ session('successUpdateProgress') }}" class="mb-4"></x-badge-success>
		@endif

		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<form method="post" action="{{ route("teacher.student.update.progress", [$course->id, $student->id]) }}" id="student_progress">
				@csrf
				@method('patch')

				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Name</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Material Access</th>
					</thead>
					<tbody>
						@forelse ($newestprogress as $progress)
							<tr class="@if($loop->index % 2 == 0) bg-white @endif">
								<td class="py-2 px-4">{{ $progress->activity->topic->title }}</td>
								<td class="py-2 px-4">{{ $progress->activity->title }}</td>
								<td class="py-2 px-4">
									<div class="w-full flex justify-center">
										<input type="checkbox" name="access" id="activity_progress_{{ $progress->id }}"
										class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300"
										@if ($progress->status === 'unlocked') checked @endif>
									</div>
								</td>
							</tr>
						@empty
							<tr><td colspan="4" class="text-center font-semibold bg-white rounded-xl p-5">- No activities yet added to this course -</td></tr>
						@endforelse
					</tbody>
				</table>

				@if($newestprogress->isNotEmpty())
					<div class="flex gap-2 mt-10 mb-3 w-full justify-center">
						<x-button class="bg-orange-500 w-full md:w-1/6">Save</x-button>
						<x-cancel-button class="w-full md:w-1/6" href="{{ route('teacher.student.select-student', $course->id) }}">Cancel</x-cancel-button>
					</div>
				@endif
			</form>
		</div>

		{{-- <div class="overflow-x-auto rounded-md mt-8">
			<form method="post" action="{{ route("teacher.student.update.progress", [$course->id, $student->id]) }}" id="student_progress">
				@csrf
				@method('patch')

				<x-table>
					<x-slot name="head">
						<th class="template-heads rounded-l-xl">Topic</th>
						<th class="template-heads">Activity Name</th>
						<th class="template-heads">Access</th>
						<th class="template-heads rounded-r-xl">Action</th>
					</x-slot>

					@if ($newestprogress->isNotEmpty())
						@foreach ($newestprogress as $progress)
							<tr>
								<td class="template-bodies rounded-l-xl">
									<a href="">
										{{ $progress->activity->topic->title }}
									</a>
								</td>

								<td class="template-bodies">
									<a href="">
										{{ $progress->activity->title }}
									</a>
								</td>

								<td class="template-bodies">
									{{ $progress->status }}
								</td>
								<td class="template-bodies rounded-r-xl">
									<input type="checkbox" name="access" id="activity_progress_{{ $progress->id }}"
									class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300"
									@if ($progress->status === 'unlocked') checked @endif>
								</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="4" class="text-center font-semibold bg-white rounded-xl p-5">- No activities yet added to this course -</td></tr>
					@endif
				</x-table>

				@if($newestprogress->isNotEmpty())
					<div class="flex gap-2 mt-10 mb-3 w-full justify-center">
						<x-button class="bg-orange-500 w-full md:w-1/6">Save</x-button>
						<x-cancel-button class=" w-full md:w-1/6" msg="All changes will be discarded, are you sure want to cancel?">Cancel</x-cancel-button>
					</div>
				@endif

			</form>
		</div> --}}

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
