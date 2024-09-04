@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.assignment.show', $assignment->course->id) }}" class="font-bold text-yellow-500">{{ $assignment->course->course_name }}</a>
	> <span>{{ $assignment->title }}</span>
	> <span>Submissions</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Students Submissions in assignment "{{ $assignment->title }}"</x-page-title>
		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Submission time</th>
					<th class="template-heads">Student</th>
					<th class="template-heads">Submission title</th>
					<th class="template-heads">Submission status</th>
					<th class="template-heads rounded-r-xl">Submission link</th>
				</x-slot>

				@if(count($latest_submissions))
					@foreach($latest_submissions as $submission)
						<tr @if($submission->status == "Late") class="bg-red-400" @endif>
							<td class="template-bodies rounded-l-xl">{{ $submission->created_at }}</td>
							<td class="template-bodies"><a href="{{ route("teacher.assignment.submission-history", [$assignment->id, $submission->student->id]) }}" class="text-blue-200 hover:text-blue-400 hover:underline font-bold">{{ $submission->student->full_name }}</a></td>
							<td class="template-bodies">{{ $submission->title }}</td>
							<td class="template-bodies">{{ $submission->status }}</td>
							<td class="template-bodies rounded-r-xl"><a class="text-blue-200 hover:text-blue-400 hover:underline font-bold" href="{{ $submission->link }}" target="blank">{{ $submission->link }}</a></td>
						</tr>
					@endforeach
				@else
					<tr><td class="rounded-xl bg-white text-center font-semibold p-5" colspan="5">- No submissions yet -</td></tr>
				@endif
			</x-table>
		</div>
	</x-section-container>
@endsection
