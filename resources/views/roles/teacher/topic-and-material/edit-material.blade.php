@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $material->topic->course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-4">
			<a href="{{ route("teacher.mycourse.show", $material->topic->course->id) }}">{{ $material->topic->course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">{{ $material->topic->title }} - Edit Material "{{ $material->title }}"</h1>

		<form action="{{ route('teacher.material.update', $material->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Material Title -->
			<div class="flex gap-2 items-stretch">
				<x-boxed-label for="title" :value="__('Material Title')" />
				<x-input id="title" class="w-full" type="text" name="title" :value="old('title', $material->title)"
					autofocus />
			</div>

			<!-- Material Description -->
			<div class="mt-4 flex gap-2 items-stretch">
				<x-boxed-label for="desc" :value="__('Material Description')" />
				<x-input id="desc" class="w-full" type="text" name="desc" :value="old('desc', $material->desc)" />
			</div>

			<!-- Material Link -->
			<div class="mt-4 flex gap-2 items-stretch">
				<x-boxed-label for="link" :value="__('Material Link')" />
				<x-input id="link" class="w-full" type="text" name="link" :value="old('link', $material->link)" />
			</div>

			<div class="flex gap-2 items-stretch justify-center w-full mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/6">
					Cancel
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
