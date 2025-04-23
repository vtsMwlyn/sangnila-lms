@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title style="margin-bottom: 0;">New Activity</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Topic: {{ $topic->title }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.course.activity.store', $topic->id) }}" method="post" class="mt-3" id="create-activity-form">
			@csrf
			<div class="flex gap-5 w-full flex-col md:flex-row">
				{{-- Activity Title --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="title">Activity Title<span class="text-red">*</span></x-label>
					<x-input id="title" class="w-full" type="text" name="title" placeholder="Enter activity title" value="{{ old('title') }}" autofocus />
				</div>

				{{-- Session --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="session">Session<span class="text-red">*</span></x-label>
					<x-input id="session" class="w-full" type="text" name="session" placeholder="Enter activity session" value="{{ old('session') }}" />
				</div>
			</div>

			{{-- Material Link --}}
			<div class="w-full flex flex-col mt-4">
				<x-label for="link">Material Link</x-label>
				<x-input id="link" class="w-full" type="text" name="link" placeholder="Enter material link" value="{{ old('link') }}" />
			</div>

			{{-- Activity Description --}}
			<div class="w-full flex flex-col mt-4">
				<x-label for="desc">Activity Description<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="desc" class="w-full" type="text" name="desc" placeholder="Enter activity description" >{!! old('desc') !!}</x-textarea>
			</div>

			{{-- Learning Outcome --}}
			<p class="font-bold mt-6">Learning Outcomes:</p>
			@php
				$iterasus = 1;
			@endphp

			<div class="w-full @error('learning_outcome') border border-red px-4 @enderror" style="@error('learning_outcome') border-width: 3px; @enderror">
				@forelse ($learning_outcomes as $lo)
					<div class="my-4 flex">
						<input type="checkbox" id="checkbox{{ $iterasus }}"
						class="mt-1 mr-2 h-5 w-5" @if(old('learning_outcome.' . $loop->index) == "on") checked @endif >
						<label for="checkbox{{ $iterasus }}">LO {{ $lo->number }}: {{ $lo->title }}</label>
					</div>

					@php
						$iterasus++;
					@endphp
				@empty

				@endforelse
			</div>
			@error('learning_outcome')
				<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> Please select minimum 1 item.</p>
			@enderror

			<div class="flex gap-2 items-stretch justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Save') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$("#create-activity-form").on("submit", function(){
			$('input[type="checkbox"]').each(function(){
				if($(this).is(":checked")){
					$("#create-activity-form").append($("<input>").attr({"type": "hidden", "name": "learning_outcome[]", "value": "on"}));
				}
				else {
					$("#create-activity-form").append($("<input>").attr({"type": "hidden", "name": "learning_outcome[]", "value": "off"}));
				}
			});

			this.submit();
		});
	</script>
@endsection
