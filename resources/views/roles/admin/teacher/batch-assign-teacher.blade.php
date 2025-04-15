@extends("layouts.main-admin")

@section("title")
	<h1>Manage Course</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title>Teacher Batch Assign</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		@error('selected_teachers')
			<x-badge-danger badge_text="Please select minimum 1 teacher to assign to this course!"></x-badge-danger>
		@enderror

		@php
			$iterasus = 1;
		@endphp

		<h1>Select Teachers to Assign to Course <strong>{{ $course->course_name }} - {{ ucwords($course->level) }}</strong>:</h1>
		<form action="{{ route('admin.course.batch-assign-teacher.store', $course->id) }}" method="post" class="flex flex-col py-6">
			@csrf

			<div class="bg-white border rounded-2xl py-5 flex flex-col">
				<div class="px-5">
					<div class="w-full flex justify-between items-center">
						<h1 class="text-blue font-semibold">Teacher List</h1>
						<div class="flex gap-3 w-1/3 items-center">
							<x-input id="search-teacher" name="_dummy_search" class="grow" placeholder="Search Teacher..."/>
							<x-button type="button" id="clear-button">Clear</x-button>
						</div>
					</div>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
				</div>
				<div class="w-full overflow-y-auto px-5" style="max-height: 60vh;">
					@forelse ($teachers as $teacher)
						<div class="my-4 teachers flex gap-3 items-center">
							<input type="checkbox" id="checkbox{{ $iterasus }}" data-sid="{{ $teacher->id }}"
							class="mr-2 h-5 w-5">
							@if($teacher->details->profpic)
								<img src="{{ Storage::url("app/public/" . $teacher->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@else
								<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
							@endif
							<label for="checkbox{{ $iterasus }}">{{ $teacher->full_name }}</label>
						</div>

						@php
							$iterasus++;
						@endphp
					@empty

					@endforelse
				</div>
			</div>

			<div class="flex items-stretch gap-2 justify-center w-full mt-10 mb-3">
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Continue') }}
				</x-button>
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$(document).ready(() => {
			$("#search-teacher").on("input", function(){
				const keyword = $(this).val().toLowerCase();
				$(".teachers").each(function(){
					if(!$(this).find("label").text().toLowerCase().includes(keyword)){
						$(this).hide();
					} else {
						$(this).show();
					}
				});
			});

			$("#clear-button").click(() => {
				$("#search-teacher").val('');

				$(".teachers").each(function(){
					$(this).show();
				});
			});

			$("form").on("submit", function(e){
				e.preventDefault();

				$('input[type="checkbox"]').each(function(){
					if($(this).is(":checked")){
						$("form").append($("<input>").attr({"type": "hidden", "name": "selected_teachers[]", "value": $(this).data("sid")}));
						n++;
					}
				});

				this.submit();
			});
		});
	</script>
@endsection
