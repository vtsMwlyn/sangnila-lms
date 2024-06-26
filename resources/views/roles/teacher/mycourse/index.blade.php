@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">My Courses</x-page-title>
		@if (Auth::user()->teached_courses->isNotEmpty())
			<div class="overflow-x-auto">
				<x-table>
					<x-slot name="head">
						<th class="template-heads rounded-l-xl">Course Name</th>
						<th class="template-heads">Description</th>
						<th class="template-heads rounded-r-xl">Actions</th>
					</x-slot>

					@foreach (Auth::user()->teached_courses as $course)
						<tr>
							<td class="template-bodies rounded-l-xl w-1/4">
								<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id]) }}"
									class="font-bold text-blue-200 hover:text-blue-400 hover:underline">
									{{ $course->course_name }}
								</a>
							</td>

							<td class="template-bodies">
								{{ substr($course->course_description, 0, 100) }}...
							</td>
							<td class="template-bodies rounded-r-xl">
								<x-anchor-button class="bg-orange-500"
									href="{{ route('teacher.student.select-student', $course->id) }}">
									View Students Progress
								</x-anchor-button>
							</td>
						</tr>
					@endforeach
				</x-table>
			</div>
		@else
			<div class="text-blue-900">N/A</div>
		@endif
	</x-section-container>
@endsection
