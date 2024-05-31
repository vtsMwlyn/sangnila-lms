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
				@if(!$nosubmissions)
					@foreach($student_assignments as $asg)
						@php
							$n = $asg->submissions->count() - 1;
						@endphp
						@if($asg->submissions->count())
							<tr @if($asg->submissions[$n]->status == "Late") class="bg-red-400" @endif>
								<td class="border px-3">{{ $asg->submissions[$n]->created_at }}</td>
								<td class="border px-3"><a href="{{ route("teacher.assignment.submission-history", [$asg->id, $asg->student->id]) }}" class="text-blue-600 font-bold">{{ $asg->student->full_name }}</a></td>
								<td class="border px-3">{{ $asg->submissions[$n]->title }}</td>
								<td class="border px-3">{{ $asg->submissions[$n]->status }}</td>
								<td class="border px-3"><a class="text-blue-600" href="{{ $asg->submissions[$n]->link }}">{{ $asg->submissions[$n]->link }}</a></td>
							</tr>
						@endif
					@endforeach
				@else
					<tr><td class="border text-center px-3" colspan="5">- No submissions yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
