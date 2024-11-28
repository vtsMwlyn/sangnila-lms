@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $topic->course->id) }}" class="font-bold text-yellow-500">{{ $topic->course->course_name }}</a>
	> <a href="{{ route('teacher.mycourse.topic.show', [$topic->course->id, $topic->id]) }}" class="font-bold text-yellow-500">{{ $topic->title }}</a>
	> <span>Add Material</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-4" style="margin-bottom: 0">
			<a href="{{ route("teacher.mycourse.show", $topic->course->id) }}">{{ $topic->course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center mt-4">Upload Material to Topic "{{ $topic->title }}"</h1>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.mycourse.material.store', $topic->id) }}" method="post">
			@csrf
			<!-- Material Title -->
			<div class="flex gap-2 @error('title') items-start @else items-stretch @enderror">
				<x-boxed-label for="title" :value="__('Material Title')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="title" class="w-full" type="text" name="title" placeholder="Enter material title" autofocus />
				</div>
			</div>

			<!-- Material Description -->
			<div class="mt-4 flex gap-2 @error('desc') items-start @else items-stretch @enderror">
				<x-boxed-label for="desc" :value="__('Material Description')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="desc" class="w-full" type="text" name="desc" placeholder="Enter material description" />
				</div>
			</div>

			<!-- Material Link -->
			<div class="mt-4 flex gap-2 @error('link') items-start @else items-stretch @enderror">
				<x-boxed-label for="link" :value="__('Material Link')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="link" class="w-full" type="text" name="link" placeholder="Enter material link" />
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
