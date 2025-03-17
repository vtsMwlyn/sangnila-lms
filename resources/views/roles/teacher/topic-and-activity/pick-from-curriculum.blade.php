@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Pick from Curriculum</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Pick from Syllabus") }}</x-page-title>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<h2 class="mb-4 font-extrabold text-xl text-dark-blue">Select topic and activities from syllabus:</h2>
		<div class="w-full bg-slate-400" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto @error('selected') border-red @enderror">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activities Selection</th>
				</thead>
				<tbody >
					@php
						$iterasus = 1;
					@endphp

					@forelse ($curriculum_topics as $topic)
						<tr class="@if($iterasus % 2 == 1) bg-white @endif">
							<td class="py-3 px-4">{{ $topic->title }}</td>

							<td class="py-3 px-4">
								<div class="h-full w-full flex flex-col gap-2">
									@foreach ($topic->curriculum_activities as $activity)
										<div class="flex w-full justify-between">
											<div class="w-2/3">{{ $activity->title }}</div>

											<div class="w-1/3 flex justify-between">
												<div class="">
													@forelse ($activity->learning_outcomes as $leaout)
														LO{{ $leaout->number }}@if($activity->learning_outcomes->count() > 1 && $loop->index != $activity->learning_outcomes->count() - 1), @endif
													@empty
														N/A
													@endforelse
												</div>
												<input type="checkbox" id="activity_{{ $activity->id }}"
												class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300">
											</div>

										</div>
									@endforeach
								</div>
							</td>
						</tr>


						@php
							$iterasus++;
						@endphp
					@empty
						<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No topics and activities added yet to this course -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>

		@error('selected')
			<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> Please select at least one item.</p>
		@enderror

		<form action="{{ route('teacher.mycourse.save-picked-course', $course->id) }}" method="POST">
			@csrf
			<div class="flex items-stretch gap-3 justify-center mt-16 mb-3">
				<x-button class=" w-full md:w-1/4">
					{{ __('Confirm') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/4">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$(document).ready(() => {
			$("form").on("submit", function(e){
				e.preventDefault();

				checkbox_values = [];
				$('input[type="checkbox"]').each(function(){
					if($(this).is(":checked")){
						checkbox_values.push("on");
					}
					else {
						checkbox_values.push("off");
					}
				});

				for(let cb of checkbox_values){
					$(this).append($("<input>").attr({"type": "hidden", "name": "selected[]", "value": cb}));
				}

				this.submit();
			})
		});
	</script>
@endsection
