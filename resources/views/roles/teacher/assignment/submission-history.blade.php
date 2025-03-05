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
		<x-back-button href="{{ route('teacher.assignment.check', $assignment->id) }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $assignment->title }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">{{ $student->full_name }}'s Submission History</h1>

		@if(session()->has("successModifFeedback"))
			<x-badge-success badge_text="{{ session('successModifFeedback') }}"></x-badge-success>
		@elseif(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Time</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Link</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Feedback</th>
				</thead>
				<tbody>
					@forelse ($history as $submission)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-2 px-4">
								<div class="whitespace-nowrap">{{ $submission->created_at->format('d M Y') }}</div>
								<div class="whitespace-nowrap">{{ $submission->created_at->format('H:i') }} GMT+7</div>
							</td>
							<td class="py-2 px-4">{{ $submission->title }}</td>
							<td class="py-2 px-4">
								<a href="{{ $submission->link }}" target="_blank" class="text-blue-600 hover:underline font-bold">{{ $submission->link }}</a>
							</td>
							<td class="py-2 px-4">
								<div class="font-bold whitespace-nowrap @if($submission->status == 'Late') text-red @else text-light-blue @endif">{{ $submission->status }}</div>
							</td>
							<td class="py-2 px-4">
								<form action="{{ route("teacher.assignment.feedback", [$submission->id, $submission->student->id]) }}" method="post" class="flex gap-1 justify-center items-center w-full">
									@csrf
									<!-- Feedback -->
									<div>
										<x-input id="feedback" style="min-width: 250px" type="text" name="feedback" placeholder="Your feedback"
											:value="old('feedback', $submission->feedback)" />
									</div>
									<x-button >
										{{ __('Save') }}
									</x-button>
								</form>
							</td>
						</tr>
					@empty
						<tr><td class="bg-white font-semibold p-5 rounded-xl text-center" colspan="5">- The student hasn't uploaded any submissions yet -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>
@endsection

