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

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" style="margin-top: 0;" class="mb-8"></x-badge-danger>
		@endif

		<h2 class="mb-4 font-extrabold text-xl text-dark-blue">Select topic and materials from syllabus:</h2>
		<div class="w-full bg-slate-400" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto @error('selected') border-red @enderror">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Materials/Activities</th>
				</thead>
				<tbody >
					@php
						$iterasus = 1;
					@endphp

					@forelse ($curriculum_topics as $topic)
						<tr class="@if($iterasus % 2 == 1) bg-white @endif">
							<td class="py-2 px-4">{{ $topic->title }}</td>

							<td class="py-2 px-4">
								<div class="h-full w-2/3 flex flex-col gap-2">
									@foreach ($topic->curriculum_materials as $material)
										<div class="flex w-full justify-between">
											<div>{{ $material->title }}</div>
											<input type="checkbox" id="material_{{ $material->id }}"
											class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300">
										</div>
									@endforeach
								</div>
							</td>
						</tr>


						@php
							$iterasus++;
						@endphp
					@empty
						<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No topics and materials added yet to this course -</td></tr>
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
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Confirm') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/5">
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
