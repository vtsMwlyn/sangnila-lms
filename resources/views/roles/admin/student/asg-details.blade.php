@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __($student->full_name . "'s Assignments in Course: " . $course->course_name) }}</x-page-title>

	<div class="overflow-x-auto p-10 bg-indigo-200 rounded-3xl">
		<table class="w-full bg-white border border-black">
			<thead class="bg-blue-800 text-white">
				<th class="border border-blue-400 px-3">Assignment Title</th>
				<th class="border border-blue-400 px-3">Assignment Description</th>
				<th class="border border-blue-400 px-3">Submission Status</th>
				<th class="border border-blue-400 px-3">Latest Submission Time</th>
			</thead>
			<tbody>
				@forelse ($assignments as $asg)
					<tr>
						<td class="border border-blue-400 px-3">{{ $asg->title }}</td>
						<td class="border border-blue-400 px-3">{{ $asg->desc }}</td>
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
								echo "<td class='border border-blue-400 px-3 font-bold text-green-700'>Submitted</td><td class='border border-blue-400 px-3'>". $latest_submission->created_at ."</td>";
							} else {
								echo "<td class='border border-blue-400 px-3 text-center font-bold text-red-400' colspan='2'>No Submissions Yet</td>";
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
