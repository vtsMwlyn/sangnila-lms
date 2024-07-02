@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $material->topic->course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">Delete Material</x-page-title>

		<form method="POST" action="{{ route("teacher.material.destroy", $material->id) }}" class="rounded-2xl p-5 bg-blue-800">
			@csrf
			@method('DELETE')
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white text-center">
					Are you sure you want to delete material
					<span class="text-red-500 font-bold">{{ $material->title }}</span>
					from
					<span class="text-green-500 font-bold">{{ $material->topic->title }}</span>
					in
					<span class="text-green-500 font-bold">{{ $material->topic->course->course_name }}</span>
					?
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
