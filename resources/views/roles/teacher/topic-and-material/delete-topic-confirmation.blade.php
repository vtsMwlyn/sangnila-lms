@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $topic->course->id) }}" class="font-bold text-yellow-500">{{ $topic->course->course_name }}</a>
	> <a href="{{ route('teacher.topic.show', [$topic->course->id, $topic->id]) }}" class="font-bold text-yellow-500">{{ $topic->title }}</a>
	> <span>Delete</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Delete Topic</x-page-title>
		<form method="POST" action="{{ route("teacher.topic.destroy", [$topic->course->id, $topic->id]) }}" class="bg-blue-800 rounded-2xl p-5">
			@csrf
			@method('delete')
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl text-center font-semibold text-white">
					Are you sure you want to delete topic
					<span class="text-red-500 font-bold">{{ $topic->title }}</span>
					from
					<span class="text-green-500 font-bold">{{ $topic->course->course_name }}</span>?
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
