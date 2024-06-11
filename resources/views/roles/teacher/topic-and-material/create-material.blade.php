@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">
		<a href="{{ route("teacher.mycourse.show", $topic->course->id) }}">{{ $topic->course->course_name }}</a>
	</h1>
	<h1 class="text-2xl font-semibold text-blue-900 mb-4">Upload Material to: "{{ $topic->title }}"</h1>
	<form action="{{ route('teacher.material.store', $topic->id) }}" method="post">
		@csrf
		<!-- Material Title -->
		<div>
			<x-label for="title" :value="__('Material Title')" />
			<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" autofocus />
		</div>

		<!-- Material Description -->
		<div class="mt-4">
			<x-label for="desc" :value="__('Material Description')" />
			<x-input id="desc" class="block mt-1 w-full" type="text" name="desc" :value="old('desc')" />
		</div>

		<!-- Material Link -->
		<div class="mt-4">
			<x-label for="link" :value="__('Material Link')" />
			<x-input id="link" class="block mt-1 w-full" type="text" name="link" :value="old('link')" />
		</div>

		<div class="flex items-stretch justify-end mt-4">
			<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Cancel
			</button>
			<x-button class="ml-4 bg-indigo-400">
				{{ __('Submit') }}
			</x-button>
		</div>
	</form>
@endsection
