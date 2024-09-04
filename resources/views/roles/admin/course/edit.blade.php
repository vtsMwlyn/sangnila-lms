@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Edit Course") }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route('admin.course.update', $course->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Course Name -->
			<div class="flex @error('course_name') items-start @else items-stretch @enderror gap-3">
				<x-boxed-label for="course_name" :value="__('Course Name')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="course_name" class="block w-full" type="text" placeholder="Course name" name="course_name" :value="$course->course_name"
					autofocus />
				</div>
			</div>

			<!-- Course Description -->
			<div class="mt-4 flex @error('course_description') items-start @else items-stretch @enderror gap-3">
				<x-boxed-label for="course_description" :value="__('Course Description')" />
				{{-- <x-input id="course_description" class="block w-full bg-blue-950" type="text" name="course_description" :value="$course->course_description" style="color: white"/> --}}
				<div class="flex flex-col w-full items-stretch">
					<x-input id="course_description" class="block w-full" type="text" name="course_description" placeholder="Course description" :value="$course->course_description"/>
				</div>
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
				<x-cancel-button msg="The changes will be discarded, are you sure want to cancel?" class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
