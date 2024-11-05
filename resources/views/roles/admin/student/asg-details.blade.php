@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.student.show', $student->id) }}" class="font-bold text-yellow-500">{{ $student->full_name }}</a>
	> <a href="{{ route('admin.student.show', $student->id) }}#student-summary" class="font-bold text-yellow-500">Assignments</a>
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __($student->full_name . "'s Assignments in Course: " . $course->course_name) }}</x-page-title>

		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Assignment Title</th>
					<th class="template-heads">Assignment Description</th>
					<th class="template-heads">Uploaded by</th>
					<th class="template-heads">Submission Status</th>
					<th class="template-heads rounded-r-xl">Latest Submission Time</th>
				</x-slot>
				@forelse ($assignments as $asg)
					<tr>
						<td class="template-bodies rounded-l-xl">{{ $asg->title }}</td>
						<td class="template-bodies">{{ $asg->desc }}</td>
						<td class="template-bodies">{{ ($asg->posted_by->details->gender == 1)? "Mr." : "Ms." }} {{ $asg->posted_by->full_name }}</td>
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
								echo "<td class='template-bodies font-bold text-center'>Submitted</td><td class='template-bodies rounded-r-xl text-center' style='color: rgb(74 222 128);'>". $latest_submission->created_at ."</td>";
							} else {
								echo "<td class='template-bodies font-bold rounded-r-xl' colspan='2' style='color: rgb(248 113 113);'>No Submissions Yet</td>";
							}
						@endphp

					</tr>
				@empty
					<tr>
						<td class="bg-white px-3 py-5 text-center rounded-xl font-semibold" colspan="5">- No assignments assigned to the student yet -</td>
					</tr>
				@endforelse
			</x-table>
		</div>
	</x-section-container>
@endsection
