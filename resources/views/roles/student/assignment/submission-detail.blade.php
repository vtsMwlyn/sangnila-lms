@extends("layouts.main-student")

@section("title")
	<h1>My Assignments</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">My Submission and Feedback in assignment "{{ $assignment->title }}"</x-page-title>
		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Submission time</th>
					<th class="template-heads">Submission title</th>
					<th class="template-heads">Submission link</th>
					<th class="template-heads">Submission status</th>
					<th class="template-heads rounded-r-xl">Feedback</th>
				</x-slot>

				@if ($submissions->count())
					@foreach($submissions as $submission)
						<tr @if($submission->status == "Late") class="bg-red-400" @endif>
							<td class="template-bodies rounded-l-xl">{{ $submission->created_at }}</td>
							<td class="template-bodies w-1/3">{{ $submission->title }}</td>
							<td class="template-bodies"><a href="{{ $submission->link }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline" target="blank">{{ $submission->link }}</a></td>
							<td class="template-bodies">{{ $submission->status }}</td>
							<td class="template-bodies rounded-r-xl w-1/3">@if($submission->feedback == "") - No feedback - @else {{ $submission->feedback }} @endif</td>
						</tr>
					@endforeach
				@else
					<tr><td class="text-center bg-white rounded-xl p-5 font-semibold" colspan="3">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif

			</x-table>
		</div>
	</x-section-container>
@endsection
