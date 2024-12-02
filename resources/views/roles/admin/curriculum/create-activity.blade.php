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
		<x-page-title class="mt-4">
			<a href="{{ route("admin.course.show", $course->id) }}">{{ $course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">Upload Activity to Topic "{{ $curriculum_topic->title }}"</h1>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route("admin.course.curriculum.activity.store", [$course->id, $curriculum_topic->id]) }}" method="post">
			@csrf
			<!-- Activity Title -->
			<div class="flex gap-2 @error('title') items-start @else items-stretch @enderror">
				<x-boxed-label for="title" :value="__('Activity Title')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="title" class="w-full" type="text" name="title" placeholder="Enter activity title" :value="old('title')" autofocus />
				</div>
			</div>

			<!-- Activity Description -->
			<div class="mt-4 flex gap-2 @error('desc') items-start @else items-stretch @enderror">
				<x-boxed-label for="desc" :value="__('Activity Description')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="desc" class="w-full" type="text" name="desc" placeholder="Enter activity description" :value="old('desc')" />
				</div>
			</div>

			<!-- Activity Link -->
			<div class="mt-4 flex gap-2 @error('link') items-start @else items-stretch @enderror">
				<x-boxed-label for="link" :value="__('Activity Link')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="link" class="w-full" type="text" name="link" placeholder="Enter activity link" :value="old('link')" />
				</div>
			</div>

			<div class="flex gap-2 items-stretch justify-center w-full mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
