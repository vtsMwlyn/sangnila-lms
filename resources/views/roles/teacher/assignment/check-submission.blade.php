@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Students Submissions in assignment "{{ $assignment->title }}"</h1>
	<div class="overflow-x-auto">
		<table class="min-w-full bg-white border mt-3" style="border-radius: 0;">
			<thead>
				<tr>
					<th class="border px-3">Submission time</th>
					<th class="border px-3">Student</th>
					<th class="border px-3">Submission title</th>
					<th class="border px-3">Submission status</th>
					<th class="border px-3">Submission link</th>
				</tr>
			</thead>
			<tbody>
				@if(count($latest_submissions))
					@foreach($latest_submissions as $submission)
						<tr @if($submission->status == "Late") class="bg-red-400" @endif>
							<td class="border px-3">{{ $submission->created_at }}</td>
							<td class="border px-3"><a href="{{ route("teacher.assignment.submission-history", [$assignment->id, $submission->student->id]) }}" class="text-blue-600 font-bold">{{ $submission->student->full_name }}</a></td>
							<td class="border px-3">{{ $submission->title }}</td>
							<td class="border px-3">{{ $submission->status }}</td>
							<td class="border px-3"><a class="text-blue-600" href="{{ $submission->link }}">{{ $submission->link }}</a></td>
						</tr>
					@endforeach
				@else
					<tr><td class="border text-center px-3" colspan="5">- No submissions yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
