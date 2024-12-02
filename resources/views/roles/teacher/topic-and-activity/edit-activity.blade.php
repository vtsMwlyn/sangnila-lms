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
		<x-page-title class="mt-4" style="margin-bottom: 0">
			<a href="{{ route("teacher.mycourse.show", $activity->topic->course->id) }}">{{ $activity->topic->course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center mt-4">{{ $activity->topic->title }} - Edit Activity "{{ $activity->title }}"</h1>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.mycourse.activity.update', $activity->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Activity Title -->
			<div class="flex gap-2 @error('title') items-start @else items-stretch @enderror">
				<x-boxed-label for="title" :value="__('Activity Title')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="title" class="w-full" type="text" name="title" :value="old('title', $activity->title)"
						autofocus />
					</div>
				</div>

			<!-- Activity Description -->
			<div class="mt-4 flex gap-2 @error('desc') items-start @else items-stretch @enderror">
				<x-boxed-label for="desc" :value="__('Activity Description')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="desc" class="w-full" type="text" name="desc" :value="old('desc', $activity->desc)" />
					</div>
				</div>

			<!-- Activity Link -->
			<div class="mt-4 flex gap-2 @error('link') items-start @else items-stretch @enderror">
				<x-boxed-label for="link" :value="__('Activity Link')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="link" class="w-full" type="text" name="link" :value="old('link', $activity->link)" />
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
