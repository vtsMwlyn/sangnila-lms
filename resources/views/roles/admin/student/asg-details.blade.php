@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __($student->full_name . "'s Assignments in Course: " . $course->course_name) }}</x-page-title>

	<div class="overflow-x-auto py-5 px-10 bg-indigo-200 rounded-3xl mt-10">
		<table class="w-full text-sm" style="border-collapse: separate;
		border-spacing: 0 20px;">
			<thead class="bg-blue-900 text-white">
				<th class="px-3 py-5 rounded-l-xl">Assignment Title</th>
				<th class="px-3 py-5">Assignment Description</th>
				<th class="px-3 py-5">Uploaded by</th>
				<th class="px-3 py-5">Submission Status</th>
				<th class="px-3 py-5 rounded-r-xl">Latest Submission Time</th>
			</thead>
			<tbody>
				@forelse ($assignments as $asg)
					<tr class="bg-blue-800 text-white">
						<td class="border border-blue-950 px-3 py-5 text-center rounded-l-xl">{{ $asg->title }}</td>
						<td class="border border-blue-950 px-3 py-5 text-center">{{ $asg->desc }}</td>
						<td class="border border-blue-950 px-3 py-5 text-center">{{ ($asg->posted_by->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ $asg->posted_by->full_name }}</td>
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
								echo "<td class='border border-blue-950 px-3 py-5 font-bold text-green-500 text-center'>Submitted</td><td class='border border-blue-950 px-3 py-5 rounded-r-xl text-center'>". $latest_submission->created_at ."</td>";
							} else {
								echo "<td class='border border-blue-950 px-3 py-5 text-center font-bold text-red-400 rounded-r-xl' colspan='2'>No Submissions Yet</td>";
							}
						@endphp

					</tr>
				@empty
					<tr>
						<td class="bg-white px-3 py-5 text-center rounded-xl" colspan="5">- No assignments assigned to the student yet -</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
@endsection
