@extends("layouts.main-guest")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ $course->course_name }}</x-page-title>
		<p class="text-blue-950 font-semibold text-center mb-8">{{ $course->course_description }}</p>

		<h2 class="text-xl font-bold text-white mb-5">Course Materials:</h2>
		<div class="overflow-x-auto mb-5">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Course Topic</th>
					<th class="template-heads rounded-r-xl">Material Name</th>
				</x-slot>

				@if ($topics->count())
					@if($topics[0]->curriculum_materials->count())
						@foreach ($topics[0]->curriculum_materials as $index => $material)
							@if($index < 3)
								<tr class="@if($index == 1) opacity-60 @elseif($index == 2) opacity-30 @endif">
									<td class="template-bodies rounded-l-xl">
										{{ $topics[0]->title }}
									</td>
									<td class="template-bodies rounded-r-xl">
										@if($index < 1)
											<a href="{{ $material->link }}" class="font-bold hover:underline text-blue-200 hover:text-blue-400">
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
								{{ $topics[0]->title }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 text-center">This topic doesn't have any materials yet.</td>
						</tr>
					@endif
				@else
					<tr>
						<td colspan="2" class="p-5 bg-white rounded-xl font-semibold text-center">This course doesn't have any topics and materials yet.</td>
					</tr>
				@endif

			</x-table>

			<div class="mt-10 flex justify-center">
				<div class="border-4 border-orange-400 p-5 text-orange-400 font-extrabold">~ Want to find out more? Come join us now! ~</div>
			</div>
		</div>
	</x-section-container>
@endsection
