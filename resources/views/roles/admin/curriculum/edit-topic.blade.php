@extends("layouts.main-admin")

@section("title")
	<h1>Edit Curriculum Topic</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $curriculum_topic->id]) }}" class="font-bold text-yellow-500">{{ $curriculum_topic->title }}</a>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Edit Curriculum Topic") }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('admin.course.curriculum.topic.edit', [$course->id, $curriculum_topic->id]) }}" method="post">
			@csrf
			<!-- Curriculum Topic Title -->
			<div class="mb-4 flex gap-3 @error('topic_title') items-start @else items-stretch @enderror">
				<x-boxed-label for="topic_title" :value="__('Topic Title')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="topic_title" class="block w-full" type="text" name="topic_title" placeholder="New curriculum topic title"
					:value="old('topic_title', $curriculum_topic->title)" autofocus />
				</div>
			</div>

			<div class="flex items-stretch gap-3 justify-center mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Submit') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
