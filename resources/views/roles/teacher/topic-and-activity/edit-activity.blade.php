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
					<x-label for="title" :value="__('Activity Title')" />
					<x-input id="title" class="w-full" type="text" name="title" :value="old('title', $activity->title)" autofocus />
				</div>

				<!-- Session -->
				<div class="w-1/2 flex flex-col">
					<x-label for="session" :value="__('Session')" />
					<x-input id="session" class="w-full" type="text" name="session" placeholder="Enter activity session" :value="old('session', $activity->session)" />
				</div>
			</div>

			<!-- Activity Description -->
			<div class="mt-4 w-full flex flex-col">
				<x-label for="desc" :value="__('Activity Description')" />
				<x-input id="desc" class="w-full" type="text" name="desc" :value="old('desc', $activity->desc)" />
			</div>

			<!-- Activity Link -->
			<div class="mt-4 w-full flex flex-col">
				<x-label for="link" :value="__('Activity Link')" />
				<x-input id="link" class="w-full" type="text" name="link" :value="old('link', $activity->link)" />
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

			<div class="flex gap-2 items-stretch justify-center w-full mt-20 mb-3">
				<x-button class=" w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
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
