@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<h2 class="text-2xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Assignments</h2>
	<h3 class="text-xl font-semibold">Course: {{ $course->course_name }}</h3>

	<div class="overflow-x-auto mt-3">
		<table class="w-full">
			<thead>
				<th class="border px-3">Assignment Title</th>
				<th class="border px-3">Assignment Description</th>
				<th class="border px-3">Submission Status</th>
				<th class="border px-3">Latest Submission Time</th>
			</thead>
			<tbody>
				@forelse ($assignments as $asg)
					<tr>
						<td class="border px-3">{{ $asg->title }}</td>
						<td class="border px-3">{{ $asg->desc }}</td>
						@php
							$submissions = $asg->submissions;
							$found = false;
							$latest_submission = null;
							for($i = count($submissions) - 1; $i >= 0; $i--){
								if($submissions[$i]->student_id == $student->id){
									$found = true;
									$latest_submission = $submissions[$i];
									break;
								}
							}

							if($found){
								echo "<td class='border px-3'>Submitted</td><td class='border px-3'>". $latest_submission->created_at ."</td>";
							} else {
								echo "<td class='border px-3 text-center' colspan='2'>No Submissions Yet</td>";
							}
						@endphp

					</tr>
				@empty
					<tr>
						<td class="border px-3 text-center" colspan="4">No assignments assigned to the student yet</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
@endsection
