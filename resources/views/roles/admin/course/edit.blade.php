@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Edit Course") }}</x-page-title>

	<form action="{{ route('admin.course.update', $course->id) }}" method="post" class="bg-indigo-200 p-10 rounded-3xl mt-10">
		@csrf
		@method('PATCH')
		<!-- Course Name -->
		<div>
			<x-label for="course_name" :value="__('Course Name')" />
			<x-input id="course_name" class="block mt-1 w-full" type="text" placeholder="Course name" name="course_name" :value="$course->course_name"
				autofocus />
		</div>

		<!-- Course Description -->
		<div class="mt-4">
			<x-label for="course_description" :value="__('Course Description')" />
			{{-- <x-input id="course_description" class="block mt-1 w-full bg-blue-950" type="text" name="course_description" :value="$course->course_description" style="color: white"/> --}}
			<x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description" placeholder="Course description" :value="$course->course_description"/>
		</div>

		<!-- Course Visibility -->
		<div class="mt-4">
			<x-label for="visibility" :value="__('Course Visibility')" />
			<x-select name="visibility" id="visibility" class="w-1/3 mt-1">
				<option value="public" @if($course->visibility == "public") selected @endif>Public</option>
				<option value="private" @if($course->visibility == "private") selected @endif>Private</option>
			</x-select>
		</div>


		<div class="flex items-stretch justify-end mt-4 gap-1">
			<x-button type="button" onclick="history.back()" class="bg-orange-500">
				Cancel
			</x-button>
			<x-button class="bg-orange-500">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
