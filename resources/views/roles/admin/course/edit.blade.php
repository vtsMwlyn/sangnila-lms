@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ __("Edit Course") }}</x-page-title>
		<form action="{{ route('admin.course.update', $course->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Course Name -->
			<div class="flex items-stretch gap-3">
				<x-boxed-label for="course_name" :value="__('Course Name')" />
				<x-input id="course_name" class="block w-full" type="text" placeholder="Course name" name="course_name" :value="$course->course_name"
					autofocus />
			</div>

			<!-- Course Description -->
			<div class="mt-4 flex items-stretch gap-3">
				<x-boxed-label for="course_description" :value="__('Course Description')" />
				{{-- <x-input id="course_description" class="block w-full bg-blue-950" type="text" name="course_description" :value="$course->course_description" style="color: white"/> --}}
				<x-input id="course_description" class="block w-full" type="text" name="course_description" placeholder="Course description" :value="$course->course_description"/>
			</div>

			<!-- Course Visibility -->
			<div class="mt-4 flex items-stretch gap-3">
				<x-boxed-label for="visibility" :value="__('Course Visibility')" />
				<x-select name="visibility" id="visibility" class="w-full">
					<option value="public" @if($course->visibility == "public") selected @endif>Public</option>
					<option value="private" @if($course->visibility == "private") selected @endif>Private</option>
				</x-select>
			</div>

			<div class="flex items-stretch justify-center mt-20 mb-3 gap-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Save') }}
				</x-button>
				<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/5">
					Cancel
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
