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
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
		@endif

		<form action="{{ route('admin.course.update', $course->id) }}" method="post" class="w-full flex flex-col gap-y-4">
			@csrf
			@method('patch')
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Course Name</p>
					<x-input id="course_name" class="block w-full" type="text" name="course_name" placeholder="New course name"
						:value="old('course_name', $course->course_name)" autofocus />
				</div>

				<div class="flex flex-col w-1/2">
					<p>Status</p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="status" id="status" class="w-full">
							<option selected disabled>Course Status</option>
							<option value="active" @if(old("status", $course->status) == "active") selected @endif>Active</option>
							<option value="hidden" @if(old("status", $course->status) == "hidden") selected @endif>Hidden</option>
						</x-select>
					</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Level</p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="level" id="level" class="w-full">
							<option selected disabled>Course Level</option>
							<option value="basic" @if(old("level", $course->level) == "basic") selected @endif>Basic</option>
							<option value="intermediate" @if(old("level", $course->level) == "intermediate") selected @endif>Intermediate</option>
							<option value="advanced" @if(old("level", $course->level) == "advanced") selected @endif>Advanced</option>
						</x-select>
					</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Format</p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="format" id="format" class="w-full">
							<option selected disabled>Course Format</option>
							<option value="20" @if(old("format", $course->format) == 20) selected @endif>20 Sessions</option>
							<option value="40" @if(old("format", $course->format) == 40) selected @endif>40 Sessions</option>
						</x-select>
					</div>
				</div>
			</div>

			<div class="flex flex-col w-full">
				<p>Course Description</p>
				<div class="flex flex-col w-full items-stretch">
					<x-textarea name="course_description" rows="4" placeholder="Course Descriptions">
						{{ old("course_description", $course->course_description) }}
					</x-textarea>
				</div>
			</div>

			<div class="flex items-stretch gap-3 justify-end mt-4 mb-3">
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Submit') }}
				</x-button>

			</div>
		</form>

	</x-section-container>
@endsection
