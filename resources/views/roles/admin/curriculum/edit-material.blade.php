@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $curriculum_material->curriculum_topic->id]) }}" class="font-bold text-yellow-500">{{ $curriculum_material->curriculum_topic->title }}</a>
	> <span>{{ $curriculum_material->title }}</span>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-4">
			<a href="{{ route("admin.course.show", $course->id) }}">{{ $course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">Edit Material "{{ $curriculum_material->title }}"</h1>

		<form action="{{ route("admin.course.curriculum.material.update", [$course->id, $curriculum_material->curriculum_topic->id, $curriculum_material->id]) }}" method="post">
			@csrf
			<!-- Material Title -->
			<div class="flex gap-2 items-stretch">
				<x-boxed-label for="title" :value="__('Material Title')" />
				<x-input id="title" class="w-full" type="text" name="title" placeholder="Enter new material title" :value="old('title', $curriculum_material->title)" autofocus />
			</div>

			<!-- Material Description -->
			<div class="mt-4 flex gap-2 items-stretch">
				<x-boxed-label for="desc" :value="__('Material Description')" />
				<x-input id="desc" class="w-full" type="text" name="desc" placeholder="Enter new material description" :value="old('desc', $curriculum_material->desc)"/>
			</div>

			<!-- Material Link -->
			<div class="mt-4 flex gap-2 items-stretch">
				<x-boxed-label for="link" :value="__('Material Link')" />
				<x-input id="link" class="w-full" type="text" name="link" placeholder="Enter material link" :value="old('link', $curriculum_material->link)"/>
			</div>

			<div class="flex gap-2 items-stretch justify-center w-full mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button msg="The filled data will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
