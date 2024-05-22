@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Manage Attendance</h1>
	<h1 class="text-2xl font-semibold text-blue-900 mb-4">Pick a Course</h1>
	<div class="overflow-x-auto rounded-md">
		<table class="min-w-full bg-white border-collapse ">
			<thead>
				<tr>
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Course Name</th>
				</tr>
			</thead>
			<tbody>
				@if (Auth::user()->teached_courses->isNotEmpty())
					@foreach (Auth::user()->teached_courses as $course)
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								<a href="{{ route('teacher.attendance.show', $course->id) }}"
									class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
									{{ $course->course_name }}
								</a>
							</td>
						</tr>
					@endforeach
				@else
					<tr class="text-blue-900"><td>N/A</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
