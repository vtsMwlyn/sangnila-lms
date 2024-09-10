@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.assignment.show', $assignment->course->id) }}" class="font-bold text-yellow-500">{{ $assignment->course->course_name }}</a>
	> <span>{{ $assignment->title }}</span>
	> <a href="{{ route('teacher.assignment.check', $assignment->id) }}" class="font-bold text-yellow-500">Submissions</a>
	> <span>{{ $student->full_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ $student->full_name }}'s Submissions History in Assignment "{{ $assignment->title }}"</x-page-title>

		@if(session()->has("successModifFeedback"))
			<x-badge-success badge_text="{{ session('successModifFeedback') }}"></x-badge-success>
		@endif

		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Submission time</th>
					<th class="template-heads">Submission title</th>
					<th class="template-heads">Submission link</th>
					<th class="template-heads">Submission status</th>
					<th class="template-heads rounded-r-xl">Feedback</th>
				</x-slot>

				@if (!empty($history))
					@foreach ($history as $submission)
						<tr>
							<td class="template-bodies rounded-l-xl">{{ $submission->created_at }}</td>
							<td class="template-bodies">{{ $submission->title }}</td>
							<td class="template-bodies"><a href="{{ $submission->link }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $submission->link }}</a></td>
							<td class="template-bodies">{{ $submission->status }}</td>
							<td class="template-bodies rounded-r-xl">
								<form action="{{ route("teacher.assignment.feedback", [$submission->id, $submission->student->id]) }}" method="post" class="flex gap-1 justify-center items-center w-full">
									@csrf
									<!-- Feedback -->
									<div>
										<x-input id="feedback" style="min-width: 250px" type="text" name="feedback" placeholder="Your feedback"
											:value="old('feedback', $submission->feedback)" />
									</div>
									<x-button class="bg-orange-500">
										{{ __('Save') }}
									</x-button>
								</form>
							</td>
						</tr>
					@endforeach
				@else
					<tr><td class="bg-white font-semibold p-5 rounded-xl text-center" colspan="5">- The student hasn't uploaded any submissions yet -</td></tr>
				@endif

			</x-table>
		</div>
	</x-section-container>
@endsection

