@extends("layouts.main-teacher")

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
	<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>
	<h2 class="text-xl font-semibold mb-2">Student List:</h2>
	<ul class="list-disc pl-6 mb-6">
		@forelse ($course->students as $student)
			<li class="text-black">{{ $student->full_name }}</li>
		@empty
			<li class="text-gray-500">No student enrolled in this course</li>
		@endforelse
	</ul>
	<h2 class="text-xl font-semibold mb-2">Course Materials:</h2>
	<div class="mt-5 mb-5">
		<form action="{{ route("teacher.topic.store", $course->id) }}" method="post" class="border rounded-lg p-5">
			@csrf
			<!-- Topic Title -->
			<div>
				<x-label for="title" :value="__('Topic Title')" />
				<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('Material Title')"
					autofocus />
			</div>
			<a href={{ route("teacher.mycourse.show", $course->id) }} class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">CANCEL</a>
			<x-button class="mt-3">
				{{ __('SAVE') }}
			</x-button>
		</form>
	</div>
@endsection
