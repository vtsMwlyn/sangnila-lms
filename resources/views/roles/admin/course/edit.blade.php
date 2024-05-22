@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Course</h1>
	<form action="{{ route('admin.course.update', $course->id) }}" method="post">
		@csrf
		@method('PATCH')
		<!-- Course Name -->
		<div>
			<x-label for="course_name" :value="__('Course Name')" />
			<x-input id="course_name" class="block mt-1 w-full" type="text" name="course_name" :value="$course->course_name"
				autofocus />
		</div>

		<!-- Course Description -->
		<div class="mt-4">
			<x-label for="course_description" :value="__('Course Description')" />
			<x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description" :value="$course->course_description" />
		</div>

		<!-- Course Visibility -->
		<div class="mt-4">
			<x-label for="visibility" :value="__('Course Visibility')" />
			<select name="visibility" id="visibility" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full">
				<option value="public" @if($course->visibility == "public") selected @endif>Public</option>
				<option value="private" @if($course->visibility == "private") selected @endif>Private</option>
			</select>
		</div>


		<div class="flex items-stretch justify-end mt-4">
			<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
				Cancel
			</button>
			<x-button class="ml-4 bg-indigo-400">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
