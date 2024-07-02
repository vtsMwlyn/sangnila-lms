@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Delete Assignment") }}</x-page-title>

		<form method="POST" action="{{ route("teacher.assignment.destroy", $assignment->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-8">
			@csrf
			@method('delete')
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white text-center">
					Are you sure you want to delete assignment
					<span class="text-red-500 font-bold">{{ $assignment->title }}</span>
					from
					<span class="text-green-500 font-bold">{{ $course->course_name }}</span>
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
