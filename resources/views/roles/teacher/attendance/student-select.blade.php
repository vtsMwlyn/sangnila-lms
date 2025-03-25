@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
	> <span>Select Students</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>New Attendance Report</x-page-title>
		<h1 class="font-bold text-lg text-blue mt-1">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		@php
			$iterasus = 1;
		@endphp

		<h1>Select Students to Include in New Attendance Report:</h1>
		<form action="{{ route('teacher.attendance.submit-and-proceed', $course->id) }}" method="post" class="flex flex-col py-6">
			@csrf

			<div class="bg-white border rounded-2xl p-5 flex flex-col">
				<h1 class="text-blue font-semibold">Students Currently Teached in This Course</h1>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
				@forelse ($course_students->where('learning_status', 'learning') as $cs)
					<div class="my-2 flex gap-3 items-center">
						<input type="checkbox" id="checkbox{{ $iterasus }}" data-sid="{{ $cs->student_id }}"
						class="mr-2 h-5 w-5">
						@if($cs->student->details->profpic)
							<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
						@else
							<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
						@endif
						<label for="checkbox{{ $iterasus }}">{{ $cs->student->full_name }} <span class="ml-2 bg-light-blue text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ ucwords($cs->learning_status) }}</span></label>
					</div>

					@php
						$iterasus++;
					@endphp
				@empty

				@endforelse
			</div>

			<div class="bg-white border rounded-2xl py-5 flex flex-col mt-8">
				<div class="px-5">
					<div class="w-full flex justify-between items-center">
						<h1 class="text-blue font-semibold">Other Students</h1>
						<div class="flex gap-3 w-1/3 items-center">
							<x-input id="search-student" class="grow" placeholder="Search Student..."/>
							<x-button type="button" id="clear-button">Clear</x-button>
						</div>
					</div>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
				</div>
				<div class="w-full overflow-y-auto px-5" style="max-height: 40vh;">
					@forelse ($course_students->where('learning_status', '!=', 'learning') as $cs)
						<div class="my-2 flex gap-3 items-center">
							<input type="checkbox" id="checkbox{{ $iterasus }}" data-sid="{{ $cs->student_id }}"
							class="mr-2 h-5 w-5">
							@if($cs->student->details->profpic)
								<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
							@else
								<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
							@endif
							<label for="checkbox{{ $iterasus }}">{{ $cs->student->full_name }} <span class="@if($cs->learning_status == 'undone') bg-red @elseif($cs->learning_status == 'complete') bg-green-600 @endif text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ ucwords($cs->learning_status) }}</span></label>
						</div>

						@php
							$iterasus++;
						@endphp
					@empty

					@endforelse

					@forelse ($remaining_students as $rs)
						<div class="my-4 other-students flex gap-3 items-center">
							<input type="checkbox" id="checkbox{{ $iterasus }}" data-sid="{{ $rs->id }}"
							class="mr-2 h-5 w-5">
							@if($rs->details->profpic)
								<img src="{{ Storage::url("app/public/" . $rs->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
							@else
								<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
							@endif
							<label for="checkbox{{ $iterasus }}">{{ $rs->full_name }}</label>
						</div>

						@php
							$iterasus++;
						@endphp
					@empty

					@endforelse
				</div>
			</div>

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Continue') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$(document).ready(() => {
			$("#search-student").on("input", function(){
				const keyword = $(this).val().toLowerCase();
				$(".other-students").each(function(){
					if(!$(this).find("label").text().toLowerCase().includes(keyword)){
						$(this).hide();
					} else {
						$(this).show();
					}
				});
			});

			$("#clear-button").click(() => {
				$("#search-student").val('');

				$(".other-students").each(function(){
					$(this).show();
				});
			});

			$("form").on("submit", function(e){
				e.preventDefault();

				$('input[type="checkbox"]').each(function(){
					if($(this).is(":checked")){
						$("form").append($("<input>").addClass('selected_student').attr({"type": "hidden", "name": "selected_students[]", "value": $(this).data("sid")}));
					}
				});

				this.submit();
			});
		});
	</script>

@endsection
