@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $activity->topic->course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $activity->topic->course->id) }}" class="font-bold text-yellow-500">{{ $activity->topic->course->course_name }}</a>
	> <a href="{{ route('teacher.mycourse.topic.show', [$activity->topic->course->id, $activity->topic->id]) }}" class="font-bold text-yellow-500">{{ $activity->topic->title }}</a>
	> <span>{{ $activity->title }}</span>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title style="margin-bottom: 0;">Edit Activity</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Topic: {{ $activity->topic->title }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.mycourse.activity.update', $activity->id) }}" method="post" class="mt-3">
			@csrf
			@method('PATCH')
			<!-- Activity Title -->
			<div class="w-full flex gap-5">
				<div class="w-1/2 flex flex-col">
					<x-label for="title">Activity Title<span class="text-red">*</span></x-label>
					<x-input id="title" class="w-full" type="text" name="title" :value="old('title', $activity->title)" autofocus />
				</div>

				<!-- Session -->
				<div class="w-1/2 flex flex-col">
					<x-label for="session">Session<span class="text-red">*</span></x-label>
					<x-input id="session" class="w-full" type="text" name="session" placeholder="Enter activity session" :value="old('session', $activity->session)" />
				</div>
			</div>

			<!-- Material Link -->
			<div class="mt-4 w-full flex flex-col">
				<x-label for="link">Material Link</x-label>
				<x-input id="link" class="w-full" type="text" name="link" placeholder="Enter material link" :value="old('link', $activity->link)" />
			</div>

			<!-- Activity Description -->
			<div class="mt-4 w-full flex flex-col">
				<x-label for="desc">Activity Description<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="desc" class="w-full" type="text" name="desc" placeholder="Enter activity description">{!! old('desc', $activity->desc) !!}</x-textarea>
			</div>

			<!-- Learning Outcome -->
			<p class="font-bold mt-6">Learning Outcomes:</p>
			@php
				$iterasus = 1;
			@endphp

			<div class="w-full @error('learning_outcome') border border-red px-4 @enderror" style="@error('learning_outcome') border-width: 3px; @enderror">
				@forelse ($learning_outcomes as $lo)
					<div class="my-4 flex">
						<input type="checkbox" id="checkbox{{ $iterasus }}"
						class="mt-1 mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old('learning_outcome.' . $loop->index) == "on") checked @elseif($checkbox_values[$loop->index] == "on") checked @endif >
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
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$("form").on("submit", function(){
			$('input[type="checkbox"]').each(function(){
				if($(this).is(":checked")){
					$("form").append($("<input>").attr({"type": "hidden", "name": "learning_outcome[]", "value": "on"}));
				}
				else {
					$("form").append($("<input>").attr({"type": "hidden", "name": "learning_outcome[]", "value": "off"}));
				}
			});

			this.submit();
		});
	</script>
@endsection
