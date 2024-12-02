@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <a href="{{ route('admin.course.curriculum.topic.details', [$course->id, $curriculum_activity->curriculum_topic->id]) }}" class="font-bold text-yellow-500">{{ $curriculum_activity->curriculum_topic->title }}</a>
	> <span>{{ $curriculum_activity->title }}</span>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Delete Course") }}</x-page-title>

		<form method="POST" action="{{ route('admin.course.curriculum.activity.delete', [$course->id, $curriculum_activity->curriculum_topic->id, $curriculum_activity->id]) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-8">
			@csrf

			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white text-center">
					Are you sure you want to permanently delete activity <span class="text-yellow-500 font-bold">{{ $curriculum_activity->title }}</span> from
					<span class="text-red-500 font-bold">{{ $curriculum_activity->curriculum_topic->title }}</span>?
				</h1>
			</div>

			<!-- Yes/No Buttons -->
			<div class="flex w-full justify-center gap-3">
				<x-button type="submit" class="bg-orange-500 w-full md:w-1/12">Yes</x-button>
				<x-button type="button" onclick="history.back()" class="bg-slate-600 w-full md:w-1/12">No</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
