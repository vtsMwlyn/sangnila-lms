@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __($student->full_name . "'s Attendance in Course: " . $course->course_name) }}</x-page-title>

		<div class="overflow-x-auto mt-8">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Date</th>
					<th class="template-heads">Attendance status</th>
					<th class="template-heads">Attendance detail</th>
					<th class="template-heads rounded-r-xl">Uploaded by</th>
				</x-slot>

				@if (count($attendances))
					@foreach ($attendances as $attendance)
						@if($attendance->attendance_detail == "Account disabled")
							@continue
						@endif
						<tr class="@if($attendance->is_attend == 1) bg-green-700 @else bg-red-800 @endif text-white">
							<td class="px-3 py-5 text-center rounded-l-xl">{{ $attendance->created_at }}</td>
							<td class="px-3 py-5 text-center font-bold">@if($attendance->is_attend == 1) Present @else Absent @endif</td>
							<td class="px-3 py-5 text-center">
								<div class="flex flex-col gap-3">
									<p>{{ $attendance->attendance_detail }}</p>
									<p class="font-semibold italic text-yellow-200">{{ $attendance->material_progress }} - {{ $attendance->learning_status }}</p>
								</div>
							</td>
							<td class="px-3 py-5 text-center rounded-r-xl">{{ ($attendance->attendance->posted_by->details->gender == 1)? "Mr." : "Ms." }} {{ $attendance->attendance->posted_by->full_name }}</td>
						</tr>
					@endforeach
				@else
					<tr><td colspan="4" class="bg-white px-3 py-5 text-center rounded-xl font-semibold">- The teacher haven't uploaded any attendance data yet -</td></tr>
				@endif
			</x-table>
		</div>
	</x-section-container>
@endsection
