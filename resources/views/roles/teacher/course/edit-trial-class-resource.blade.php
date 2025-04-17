@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title style="margin-bottom: 0;">Edit Trial Class Resource</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		<form action="{{ route('teacher.course.trial-class.update-resource', [$course->id, $trial_class_resource->id]) }}" method="post" class="mt-3">
			@csrf
			<div class="flex gap-5 w-full flex-col md:flex-row">
				{{-- Topic Title --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="topic_title">Topic Title<span class="text-red">*</span></x-label>
					<x-input id="topic_title" class="w-full" type="text" name="topic_title" placeholder="Enter activity topic title" value="{{ old('topic_title', $trial_class_resource->topic_title) }}" autofocus />
				</div>

				{{-- Activity Title --}}
				<div class="flex flex-col w-full md:w-1/2">
					<x-label for="activity_title">Activity Title<span class="text-red">*</span></x-label>
					<x-input id="activity_title" class="w-full" type="text" name="activity_title" placeholder="Enter activity activity title" value="{{ old('activity_title', $trial_class_resource->activity_title) }}" autofocus />
				</div>
			</div>

			{{-- Material Link --}}
			<div class="w-full flex flex-col mt-4">
				<x-label for="material_link">Material Link<span class="text-red">*</span></x-label>
				<x-input id="material_link" class="w-full" type="text" name="material_link" placeholder="Enter material material link" value="{{ old('material_link', $trial_class_resource->material_link) }}" />
			</div>

			{{-- Activity Description --}}
			<div class="w-full flex flex-col mt-4">
				<x-label for="description">Activity Description<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="description" class="w-full" type="text" name="description" placeholder="Enter activity description" >{!! old('description', $trial_class_resource->description) !!}</x-textarea>
			</div>

			<div class="flex gap-2 items-stretch justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Save') }}
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
