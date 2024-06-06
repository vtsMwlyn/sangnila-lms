@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Add New Course") }}</x-page-title>

	<form action="{{ route('admin.course.store') }}" method="post" class="p-10 rounded-3xl bg-indigo-200 mt-10">
		@csrf
		<!-- Course Name -->
		<div class="mb-4">
			<x-label for="course_name" :value="__('Course Name')" />
			<x-input id="course_name" class="block mt-1 w-full" type="text" name="course_name" placeholder="New course name"
				:value="old('course_name')" autofocus />
		</div>

		<!-- Course Description -->
		<div class="mb-4">
			<x-label for="course_description" :value="__('Course Description')" />
			<x-input id="course_description" class="block mt-1 w-full" type="text" name="course_description" placeholder="New course description"
				:value="old('course_description')" />
		</div>

		<!-- Visibility Selection -->
		<div class="mb-4">
			<x-label for="visibility" :value="__('Visibility')" />
			<x-select name="visibility" id="visibility" class="mt-1 w-1/3">
				<option selected disabled>Course Visibility</option>
				<option value="public" @if(old("visibility") == "public") selected @endif>Public</option>
				<option value="private" @if(old("visibility") == "private") selected @endif>Private</option>
			</x-select>
		</div>


		<div class="flex items-stretch gap-1 justify-end mt-6">
			<x-button type="button" onclick="history.back()" class="bg-orange-500">
				Cancel
			</x-button>
			<x-button class="bg-orange-500">
				{{ __('Submit') }}
			</x-button>
		</div>
	</form>
@endsection
