@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Students</h1>
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

		<!-- For larger screen -->
		<div class="lg:flex mt-4 w-full flex-wrap hidden">
			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id, 'content' => 'activity access']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'activity access' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				Activity Access
			</a>

			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id,'content' => 'meeting links']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'meeting links') border-bottom: 4px solid #1db9cf; @endif">
				Meeting Links
			</a>
		</div>

		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(request('content') == 'activity access' || !request('content'))
			<div class="w-full overflow-x-auto">
				<form method="post" action="{{ route("teacher.student.update.progress.activity-access", [$course->id, $student->id]) }}" id="activity_access">
					@csrf
					@method('patch')

					<table class="w-full">
						<thead>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Name</th>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Material Access</th>
						</thead>
						<tbody>
							@forelse ($newestprogress as $progress)
								<tr class="@if($loop->index % 2 == 0) bg-white @endif">
									<td class="py-2 px-4 text-center">{{ $progress->activity->session }}</td>
									<td class="py-2 px-4">{{ $progress->activity->topic->title }}</td>
									<td class="py-2 px-4">{{ $progress->activity->title }}</td>
									<td class="py-2 px-4">
										<div class="w-full flex justify-center">
											<input type="checkbox" id="activity_progress_{{ $progress->id }}"
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
							<x-button class=" w-full md:w-1/6">Save</x-button>
							<x-cancel-button class="w-full md:w-1/6" href="{{ route('teacher.student.select-student', $course->id) }}">Cancel</x-cancel-button>
						</div>
					@endif
				</form>
			</div>
		@endif

		@if(request('content') == 'meeting links')
			<div class="w-full overflow-x-auto">
				<form method="post" action="{{ route("teacher.student.update.progress.meeting-link", [$course->id, $student->id]) }}" id="meeting_link">
					@csrf
					@method('patch')

					<input type="hidden" name="content" value="meeting links">

					<table class="w-full">
						<thead>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Name</th>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Meeting Link</th>
						</thead>
						<tbody>
							@forelse ($newestprogress as $i => $progress)
								<tr class="@if($loop->index % 2 == 0) bg-white @endif">
									<td class="py-2 px-4 text-center">{{ $progress->activity->session }}</td>
									<td class="py-2 px-4">{{ $progress->activity->topic->title }}</td>
									<td class="py-2 px-4">{{ $progress->activity->title }}</td>
									<td class="py-2 px-4">
										<x-input type="text" class="w-full" name="meeting_links[]" placeholder="Add meeting link" value="{{ old('meeting_links.' . $i, $progress->meeting_link) }}"/>
										@error('meeting_links.' . $i)
											<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> Please insert a valid URL.</p>
										@enderror
									</td>
								</tr>
							@empty
								<tr><td colspan="4" class="text-center font-semibold bg-white rounded-xl p-5">- No activities yet added to this course -</td></tr>
							@endforelse
						</tbody>
					</table>

					@if($newestprogress->isNotEmpty())
						<div class="flex gap-2 mt-10 mb-3 w-full justify-center">
							<x-button class=" w-full md:w-1/6">Save</x-button>
							<x-cancel-button class="w-full md:w-1/6" href="{{ route('teacher.student.select-student', $course->id) }}">Cancel</x-cancel-button>
						</div>
					@endif
				</form>
			</div>
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

			$('#activity_access').on('submit', function(event) {
				event.preventDefault();
				const checkboxValues = collectCheckboxValues();

				checkboxValues.forEach((value, index) => {
					$('#activity_access').append($('<input>').attr({'type': 'hidden', 'name': 'checkbox_value[]', 'value': value}));
				});

				this.submit();
			});
		</script>
	</x-section-container>
@endsection
