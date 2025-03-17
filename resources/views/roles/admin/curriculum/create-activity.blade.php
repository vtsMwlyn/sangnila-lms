@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $curriculum_topic->id]) }}" class="font-bold text-yellow-500">{{ $curriculum_topic->title }}</a>
	> <span>Add Activity</span>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('admin.course.curriculum.topic.details', [$course->id, $curriculum_topic->id]) }}"></x-back-button>
		<x-page-title style="margin-bottom: 0;">New Curriculum Activity</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Topic: {{ $curriculum_topic->title }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route("admin.course.curriculum.activity.store", [$course->id, $curriculum_topic->id]) }}" method="post" class="mt-3">
			@csrf
			<div class="flex w-full gap-4">
				{{-- Activity Title --}}
				<div class="flex flex-col w-1/2">
					<x-label for="title" :value="__('Activity Title')" />
					<x-input id="title" class="w-full mt-1" type="text" name="title" placeholder="Enter activity title" :value="old('title')" autofocus />
				</div>

				{{-- Session --}}
				<div class="flex flex-col w-1/2">
					<x-label for="session" :value="__('Session')" />
					<x-input id="session" class="w-full mt-1" type="text" name="session" placeholder="Enter activity session" :value="old('session')" autofocus />
				</div>
			</div>

			{{-- Material Link --}}
			<div class="mt-4 flex flex-col w-full">
				<x-label for="link" :value="__('Material Link')" />
				<x-input id="link" class="w-full mt-1" type="text" name="link" placeholder="Enter activity link" :value="old('link')" />
			</div>

			{{-- Activity Description --}}
			<div class="mt-4 flex flex-col w-full">
				<x-label for="desc" :value="__('Activity Description')" />
				<x-textarea rows="4" id="desc" class="w-full mt-1" type="text" name="desc" placeholder="Enter activity description">{!! old('desc') !!}</x-textarea>
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
						class="mt-1 mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old('learning_outcome.' . $loop->index) == "on") checked @endif >
						<label for="checkbox{{ $iterasus }}">LO {{ $lo->number }}: {{ $lo->title }}</label>
					</div>

					@php
						$iterasus++;
					@endphp
				@empty
					- This course has no learning outcomes yet -
				@endforelse
			</div>
			@error('learning_outcome')
				<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> Please select minimum 1 item.</p>
			@enderror

			<div class="flex gap-2 items-stretch justify-center w-full mt-20 mb-3">
				<x-button class=" w-full md:w-40 lg:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button class="w-full md:w-40 lg:w-1/6">
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
