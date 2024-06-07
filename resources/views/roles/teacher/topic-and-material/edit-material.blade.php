@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $material->topic->course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-1">
		<a href="{{ route("teacher.mycourse.show", $material->topic->course->id) }}">{{ $material->topic->course->course_name }}</a>
	</h1>
	<h1 class="text-xl font-semibold text-blue-900 mb-4">
		<a href="{{ route("teacher.mycourse.show", [$material->topic->course->id, $material->topic->id]) }}">{{ $material->topic->title }}</a>
	</h1>
	<h1 class="text-2xl font-semibold text-blue-900 mb-4">Edit Material: "{{ $material->title }}"</h1>
	<form action="{{ route('teacher.material.update', $material->id) }}" method="post">
		@csrf
		@method('PATCH')
		<!-- Material Title -->
		<div>
			<x-label for="title" :value="__('Material Title')" />
			<x-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{ $material->title }}"
				autofocus />
		</div>

		<!-- Material Link -->
		<div class="mt-4">
			<x-label for="link" :value="__('Material Link')" />
			<x-input id="link" class="block mt-1 w-full" type="text" name="link" value="{{ $material->link }}" />
		</div>

		<div class="flex items-stretch justify-end mt-4">
			<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Cancel
			</button>
			<x-button class="ml-4 bg-indigo-400">
				{{ __('SAVE') }}
			</x-button>
		</div>
	</form>
@endsection
