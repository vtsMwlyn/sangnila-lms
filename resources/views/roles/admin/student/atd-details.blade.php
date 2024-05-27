@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __($student->full_name . "'s Attendance in Course: " . $course->course_name) }}</x-page-title>

	<div class="overflow-x-auto p-10 bg-indigo-200 rounded-3xl">
		<table class="min-w-full" style="border-radius: 0;">
			<thead>
				<tr class="text-white bg-blue-800">
					<th class="border border-blue-400 px-3 py-2">Date and time</th>
					<th class="border border-blue-400 px-3 py-2">Attendance status</th>
					<th class="border border-blue-400 px-3 py-2">Attendance detail</th>
					<th class="border border-blue-400 px-3 py-2">Uploaded by</th>
				</tr>
			</thead>
			<tbody>
				@if ($attendances->count())
					@foreach ($attendances as $attendance)
						@if($attendance->attendance_detail == "Account disabled")
							@continue
						@endif
						<tr class="bg-white">
							<td class="border border-blue-400 px-3 py-2">{{ $attendance->created_at }}</td>
							<td class="border border-blue-400 px-3 py-2 font-bold @if($attendance->is_attend == 1) text-green-700 @else text-red-400 @endif">@if($attendance->is_attend == 1) Present @else Absent @endif</td>
							<td class="border border-blue-400 px-3 py-2">{{ $attendance->attendance_detail }}</td>
							<td class="border border-blue-400 px-3 py-2">{{ $attendance->teacher->full_name }}</td>
						</tr>
					@endforeach
				@else
					<tr><td colspan="4" class="border border-black px-3 text-center">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
