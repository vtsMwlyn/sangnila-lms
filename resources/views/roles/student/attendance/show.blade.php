@extends("layouts.main-student")

@section("title")
	<h1>My Attendances</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">My Attendance in Course {{ $course->course_name }}</h1>
	<div class="overflow-x-auto">
		<table class="min-w-full bg-white border border-black mt-3" style="border-radius: 0;">
			<thead>
				<tr>
					<th class="border border-black px-3">Date and time</th>
					<th class="border border-black px-3">Attendance status</th>
					<th class="border border-black px-3">Attendance detail</th>
				</tr>
			</thead>
			<tbody>
				@if ($attendances->count())
					@foreach ($attendances as $attendance)
						<tr class="@if($attendance->is_attend) bg-green-500 @else bg-red-400 @endif">
							<td class="border border-black px-3">{{ $attendance->created_at }}</td>
							<td class="border border-black px-3">@if($attendance->is_attend) Present @else Absent @endif</td>
							<td class="border border-black px-3">{{ $attendance->attendance_detail }}</td>
						</tr>
					@endforeach
				@else
					<tr><td colspan="3" class="border border-black px-3 text-center">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
