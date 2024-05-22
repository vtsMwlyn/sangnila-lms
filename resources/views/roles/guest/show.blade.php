@extends("layouts.main-guest")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
	<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>

	<h2 class="text-xl font-semibold mb-2">Course Materials:</h2>
	<div class="overflow-x-auto mb-5">
		<table class="min-w-full table-auto">
			<thead>
				<tr class="border-b border-blue-900 bg-blue-200">
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Course Topic</th>
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Material Name</th>
				</tr>
			</thead>
			<tbody>
				@if ($course->course_topics->count())
					@forelse ($course->course_topics[0]->course_materials as $material)
						<tr class="hover:bg-gray-100 border-b border-blue-900">
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								{{ $course->course_topics[0]->title }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								<a href="{{ $material->link }}" class="font-bold hover:underline text-blue-600">
									{{ $material->title }}
								</a>
							</td>
						</tr>
					@empty
						<tr class="hover:bg-gray-100 border-b border-blue-900">
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								{{ $course->course_topics[0]->title }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 text-center">This topic doesn't have any materials yet.</td>
						</tr>
					@endforelse
				@else
					<tr>
						<td colspan="2" class="px-4 py-2 text-center border-t border-blue-900">This course doesn't have any topics and materials yet.</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
