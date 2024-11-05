@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $material->topic->course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $material->topic->course->id) }}" class="font-bold text-yellow-500">{{ $material->topic->course->course_name }}</a>
	> <a href="{{ route('teacher.topic.show', [$material->topic->course->id, $material->topic->id]) }}" class="font-bold text-yellow-500">{{ $material->topic->title }}</a>
	> <span>{{ $material->title }}</span>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-4">
			<a href="{{ route("teacher.mycourse.show", $material->topic->course->id) }}">{{ $material->topic->course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">{{ $material->topic->title }} - Edit Material "{{ $material->title }}"</h1>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.material.update', $material->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Material Title -->
			<div class="flex gap-2 @error('title') items-start @else items-stretch @enderror">
				<x-boxed-label for="title" :value="__('Material Title')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="title" class="w-full" type="text" name="title" :value="old('title', $material->title)"
						autofocus />
					</div>
				</div>

			<!-- Material Description -->
			<div class="mt-4 flex gap-2 @error('desc') items-start @else items-stretch @enderror">
				<x-boxed-label for="desc" :value="__('Material Description')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="desc" class="w-full" type="text" name="desc" :value="old('desc', $material->desc)" />
					</div>
				</div>

			<!-- Material Link -->
			<div class="mt-4 flex gap-2 @error('link') items-start @else items-stretch @enderror">
				<x-boxed-label for="link" :value="__('Material Link')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="link" class="w-full" type="text" name="link" :value="old('link', $material->link)" />
				</div>
			</div>

			<div class="flex gap-2 items-stretch justify-center w-full mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button msg="The changes will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
