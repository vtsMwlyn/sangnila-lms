@extends("layouts.main-guest")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8 hover:scale-110" alt="back"></button>
		<x-page-title>{{ $course->course_name }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
		<p class="font-semibold mb-8">{{ $course->course_description }}</p>

		<h2 class="text-xl text-blue-800 font-bold mb-5">Topics and Activities:</h2>
		<div class="overflow-x-auto mb-5">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course Topic</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Name</th>
				</thead>

				<tbody>
					@if ($topics->count())
						@if($topics[0]->curriculum_activities->count())
							@foreach ($topics[0]->curriculum_activities as $index => $activity)
								@if($index < 3)
									<tr class="@if($index == 1) opacity-60 @elseif($index == 2) opacity-30 @endif @if($loop->iteration % 2 == 1) bg-white @endif">
										<td class="py-3 px-4">
											{{ $topics[0]->title }}
										</td>
										<td class="py-3 px-4">
											@if($index < 1)
												<a href="{{ $activity->link }}" class="font-bold hover:underline text-blue-600 hover:text-blue-800">
													{{ $activity->title }}
												</a>
											@else
											{{ $activity->title }}
											@endif
										</td>
									</tr>
								@else
									@break
								@endif
							@endforeach
						@else
							<tr class="hover:bg-gray-100 border-b border-blue-900">
								<td class="py-3 px-4 bg-blue-100 border-b border-blue-300">
									{{ $topics[0]->title }}
								</td>
								<td class="py-3 px-4 bg-blue-100 border-b border-blue-300 text-center">This topic doesn't have any activities yet.</td>
							</tr>
						@endif
					@else
						<tr>
							<td colspan="2" class="p-5 bg-white font-semibold text-center">This course doesn't have any topics and activities yet.</td>
						</tr>
					@endif
				</tbody>
			</table>

			<div class="mt-10 flex justify-center">
				<div class="border-4 border-light-blue p-5 text-light-blue font-extrabold">~ Want to find out more? Come join us now! ~</div>
			</div>
		</div>
	</x-section-container>
@endsection
