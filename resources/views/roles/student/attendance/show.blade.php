@extends("layouts.main-student")

@section("title")
	<h1>My Attendances</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>My Attendance in Course {{ $course->course_name }}</x-page-title>
		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Date and time</th>
					<th class="template-heads">Attendance status</th>
					<th class="template-heads rounded-r-xl">Attendance detail</th>
				</x-slot>

				@if (count($attendances))
					@foreach ($attendances as $attendance)
						<tr>
							<td class="template-bodies font-semibold rounded-l-xl">{{ $attendance->created_at }}</td>
							<td class="template-bodies font-semibold" style="color: @if($attendance->is_attend == 1) #219926 @else rgb(248 113 113) @endif;">@if($attendance->is_attend) Present @else Absent @endif</td>
							<td class="template-bodies font-semibold rounded-r-xl">{{ $attendance->attendance_detail }}</td>
						</tr>
					@endforeach
				@else
					<tr><td colspan="3" class="p-5 rounded-xl bg-white font-semibold text-center">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif
			</x-table>
		</div>
	</x-section-container>
@endsection
