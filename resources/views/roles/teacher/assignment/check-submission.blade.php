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
		<x-back-button href="{{ route('teacher.assignment.show', $assignment->course->id) }}"></x-back-button>
		<x-page-title>{{ $assignment->title }}</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Students Submissions</h1>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Submission Time</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Submission title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Submission link</th>
				</thead>
				<tbody>
					@forelse ($latest_submissions as $submission)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-2 px-4">
								<div class="whitespace-nowrap">{{ $submission->created_at->format('d M Y') }}</div>
								<div class="whitespace-nowrap">{{ $submission->created_at->format('H:i') }} GMT+7</div>
							</td>
							<td class="py-2 px-4">
								<a href="{{ route("teacher.assignment.submission-history", [$assignment->id, $submission->student->id]) }}" class="text-blue-600 hover:underline font-bold">{{ $submission->student->full_name }}</a>
							</td>
							<td class="py-2 px-4">{{ $submission->title }}</td>
							<td class="py-2 px-4">
								<div class="font-bold whitespace-nowrap @if($submission->status == 'Late') text-red @else text-light-blue @endif">{{ $submission->status }}</div>
							</td>
							<td class="py-2 px-4">
								<a class="text-blue-600 hover:underline font-bold" href="{{ $submission->link }}" target="_blank">{{ $submission->link }}</a>
							</td>
						</tr>
					@empty
						<tr><td class="rounded-xl bg-white text-center font-semibold p-5" colspan="5">- No submissions yet -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>
@endsection
