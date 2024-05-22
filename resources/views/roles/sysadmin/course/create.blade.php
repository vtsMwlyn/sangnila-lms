@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Create Course</h1>
	<form action="{{ route('sysadmin.course.store') }}" method="post" class="mx-auto">
		@csrf
		<!-- Course Name -->
		<div class="mb-4">
			<x-label for="course_name" :value="__('Course Name')" />
			<x-input id="course_name" class="block mt-1 w-full" type="text" name="course_name"
				:value="old('course_name')" autofocus />
		</div>

		<!-- Course Description -->
		<div class="mb-4">
			<x-label for="course_description" :value="__('Course Description')" />
			<x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description"
				:value="old('course_description')" />
		</div>

		<!-- Visibility Selection -->
		<div class="mb-4">
			<x-label for="visibility" :value="__('Visibility')" />
			<select name="visibility" id="visibility" class="block w-full max-w-full py-2 px-3 border border-gray-300 rounded-md max-h-screen">
				<option value="public">Public</option>
				<option value="private">Private</option>
			</select>
		</div>


		<div class="flex items-stretch gap-1 justify-end mt-6">
			<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
				Cancel
			</button>
			<x-button class="bg-indigo-400">
				{{ __('Submit') }}
			</x-button>
		</div>
	</form>
@endsection
