@extends("layouts.main-teacher")

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
	@if (Auth::user()->teached_courses->isNotEmpty())
		<div class="overflow-x-auto rounded-md">
			<table class="w-full bg-white border-collapse">
				<thead>
					<tr>
						<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Course Name</td>
						<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Description</td>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Auth::user()->teached_courses as $course)
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id]) }}"
									class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
									{{ $course->course_name }}
								</a>
							</td>

							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								{{ $course->course_description }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								<a class="block w-full text-center px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
									href="{{ route('teacher.student.select-student', $course->id) }}">
									View Students Progress
								</a>
							</td>

						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@else
		<div class="text-blue-900">N/A</div>
	@endif
@endsection
