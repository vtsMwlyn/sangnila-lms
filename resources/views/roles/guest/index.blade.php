@extends("layouts.main-guest")

@section("title")
	<h1>Our Courses</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
	@if ($courses->isNotEmpty())
		<div class="overflow-x-auto rounded-md">
			<table class="w-full table-auto">
				<thead>
					<tr>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Course Name</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Description</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($courses as $course)
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								<a href="{{ route('guest.show', ['course_id' => $course->id]) }}"
									class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
									{{ $course->course_name }}
								</a>
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								{{ $course->course_description }}
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
