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
				@if ($course->topics->count())
					@if($course->topics[0]->materials->count())
						@foreach ($course->topics[0]->materials as $index => $material)
							@if($index < 3)
								<tr class="hover:bg-gray-100 border-b border-blue-900 @if($index == 1) opacity-60 @elseif($index == 2) opacity-30 @endif">
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										{{ $course->topics[0]->title }}
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
										@if($index < 1)
											<a href="{{ $material->link }}" class="font-bold hover:underline text-blue-600">
												{{ $material->title }}
											</a>
										@else
										{{ $material->title }}
										@endif
									</td>
								</tr>
							@else
								@break
							@endif
						@endforeach
					@else
						<tr class="hover:bg-gray-100 border-b border-blue-900">
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
								{{ $course->topics[0]->title }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 text-center">This topic doesn't have any materials yet.</td>
						</tr>
					@endif
				@else
					<tr>
						<td colspan="2" class="px-4 py-2 text-center border-t border-blue-900">This course doesn't have any topics and materials yet.</td>
					</tr>
				@endif
			</tbody>
		</table>

		<div class="mt-10 flex justify-center">
			<div class="border border-orange-500 p-5 text-orange-500 font-bold">~ Want to find out more? Come join us now! ~</div>
		</div>
	</div>
@endsection
