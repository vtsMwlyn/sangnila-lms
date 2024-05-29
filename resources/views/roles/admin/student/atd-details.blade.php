@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __($student->full_name . "'s Attendance in Course: " . $course->course_name) }}</x-page-title>

	<div class="overflow-x-auto py-5 px-10 bg-indigo-200 rounded-3xl mt-10">
		<table class="min-w-full text-sm" style="border-collapse: separate;
		border-spacing: 0 20px;">
			<thead>
				<tr class="text-white bg-blue-900">
					<th class="px-3 py-5 rounded-l-xl">Date and time</th>
					<th class="px-3 py-5">Attendance status</th>
					<th class="px-3 py-5">Attendance detail</th>
					<th class="px-3 py-5 rounded-r-xl">Uploaded by</th>
				</tr>
			</thead>
			<tbody>
				@if ($attendances->count())
					@foreach ($attendances as $attendance)
						@if($attendance->attendance_detail == "Account disabled")
							@continue
						@endif
						<tr class="bg-blue-800 @if($attendance->is_attend == 1) bg-green-700 @else bg-red-800 @endif text-white">
							<td class="border border-blue-950 px-3 py-5 text-center rounded-l-xl">{{ $attendance->created_at }}</td>
							<td class="border border-blue-950 px-3 py-5 text-center font-bold">@if($attendance->is_attend == 1) Present @else Absent @endif</td>
							<td class="border border-blue-950 px-3 py-5 text-center">{{ $attendance->attendance_detail }}</td>
							<td class="border border-blue-950 px-3 py-5 text-center rounded-r-xl">{{ $attendance->teacher->full_name }}</td>
						</tr>
					@endforeach
				@else
					<tr><td colspan="4" class="bg-white px-3 py-5 text-center rounded-xl">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
