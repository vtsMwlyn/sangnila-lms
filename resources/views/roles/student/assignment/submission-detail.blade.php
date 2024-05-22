@extends("layouts.main-student")

@section("title")
	<h1>My Assignments</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">My Submission and Feedback in assignment "{{ $assignment->title }}"</h1>
	<div class="overflow-x-auto">
		<table class="min-w-full bg-white border mt-3" style="border-radius: 0;">
			<thead>
				<tr>
					<th class="border px-3">Submission time</th>
					<th class="border px-3">Submission title</th>
					<th class="border px-3">Submission status</th>
					<th class="border px-3">Feedback</th>
				</tr>
			</thead>
			<tbody>
				@if ($assignment->submissions->count())
					@for ($i = $assignment->submissions->count() - 1; $i >= 0; $i--)
						<tr @if($assignment->submissions[$i]->status == "Late") class="bg-red-400" @endif>
							<td class="border px-3">{{ $assignment->submissions[$i]->created_at }}</td>
							<td class="border px-3">{{ $assignment->submissions[$i]->title }}</td>
							<td class="border px-3">{{ $assignment->submissions[$i]->status }}</td>
							<td class="border px-3">@if($assignment->submissions[$i]->feedback == "") - No feedback - @else {{ $assignment->submissions[$i]->feedback }} @endif</td>
						</tr>
					@endfor
				@else
					<tr class="border px-3" colspan="3"><td class="text-center">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
