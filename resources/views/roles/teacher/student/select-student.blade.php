@extends("layouts.main-teacher")

@section("title")
	<h1>Student Progress</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Manage Students</h1>
	<h1 class="text-2xl font-semibold text-blue-900 mb-4">Pick a Student</h1>
	@if ($students->isNotEmpty())
		<div class="overflow-x-auto rounded-md">
			<table class="min-w-full bg-white border-collapse ">
				<thead>
					<tr>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Student Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($students as $student)
						@if($student->status != "disabled")
							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
									<a href="{{ route('teacher.student.show.progress', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
										class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
										{{ $student->full_name }}
									</a>
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	@else
		<div class="text-blue-900">N/A</div>
	@endif
@endsection
