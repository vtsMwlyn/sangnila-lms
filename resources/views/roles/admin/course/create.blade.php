@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Add</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Add New Course") }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route('admin.course.store') }}" method="post" class="w-full">
			@csrf
			<!-- Course Name -->
			<div class="mb-4 flex gap-3 @error('course_name') items-start @else items-stretch @enderror">
				<x-boxed-label for="course_name" :value="__('Course Name')" />
				<div class="flex flex-col items-stretch w-full">
					<x-input id="course_name" class="block w-full" type="text" name="course_name" placeholder="New course name"
						:value="old('course_name')" autofocus />
				</div>
			</div>

			<!-- Course Description -->
			<div class="mb-4 flex gap-3 @error('course_description') items-start @else items-stretch @enderror">
				<x-boxed-label for="course_description" :value="__('Course Description')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="course_description" class="block w-full" type="text" name="course_description" placeholder="New course description"
						:value="old('course_description')" />
				</div>
			</div>

			<!-- Visibility Selection -->
			<div class="mb-4 flex gap-3 @error('visibility') items-start @else items-stretch @enderror">
				<x-boxed-label for="visibility" :value="__('Visibility')" />
				<div class="flex flex-col w-full items-stretch">
					<x-select name="visibility" id="visibility" class="w-full">
						<option selected disabled>Course Visibility</option>
						<option value="public" @if(old("visibility") == "public") selected @endif>Public</option>
						<option value="private" @if(old("visibility") == "private") selected @endif>Private</option>
					</x-select>
				</div>
			</div>


			<div class="flex items-stretch gap-3 justify-center mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Submit') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
