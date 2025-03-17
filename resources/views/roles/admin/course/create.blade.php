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
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('admin.course.store') }}" method="post" class="w-full flex flex-col gap-y-4">
			@csrf
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Course Name<span class="text-red">*</span></p>
					<x-input id="course_name" class="block w-full" type="text" name="course_name" placeholder="New course name"
						:value="old('course_name')" autofocus />
				</div>

				<div class="flex flex-col w-1/2">
					<p>Status<span class="text-red">*</span></p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="status" id="status" class="w-full">
							<option value="active" @if(old("status") == "active") selected @endif>Active</option>
							<option value="hidden" @if(old("status") == "hidden") selected @endif>Hidden</option>
						</x-select>
					</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Level<span class="text-red">*</span></p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="level" id="level" class="w-full">
							<option value="basic" @if(old("level") == "basic") selected @endif>Basic</option>
							<option value="intermediate" @if(old("level") == "intermediate") selected @endif>Intermediate</option>
							<option value="advanced" @if(old("level") == "advanced") selected @endif>Advanced</option>
						</x-select>
					</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Format<span class="text-red">*</span></p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="format" id="format" class="w-full">
							<option value="20" @if(old("format") == 20) selected @endif>20 Sessions</option>
							<option value="40" @if(old("format") == 40) selected @endif>40 Sessions</option>
						</x-select>
					</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Delivery Mode<span class="text-red">*</span></p>
					<div class="flex flex-col w-full items-stretch">
						<x-select name="delivery_mode" id="delivery_mode" class="w-full">
							<option value="onsite" @if(old("delivery_mode") == "onsite") selected @endif>Onsite</option>
							<option value="online" @if(old("delivery_mode") == "online") selected @endif>Online</option>
						</x-select>
					</div>
				</div>
				<div class="flex flex-col w-1/2"></div>
			</div>

			<div class="flex flex-col w-full">
				<p>Course Description<span class="text-red">*</span></p>
				<div class="flex flex-col w-full items-stretch">
					<x-textarea name="course_description" rows="4" placeholder="Course Descriptions">
						{{ old("course_description") }}
					</x-textarea>
				</div>
			</div>

			<div class="flex items-stretch gap-3 justify-end mt-4 mb-3">
				<x-cancel-button class="w-full md:w-40 lg:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 lg:w-1/6">
					{{ __('Submit') }}
				</x-button>

			</div>
		</form>

	</x-section-container>
@endsection
