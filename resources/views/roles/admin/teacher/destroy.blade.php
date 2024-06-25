@extends("layouts.main-admin")

@section("title")
	<h1>{{ $teacher->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Unassign Teacher from Course") }}</x-page-title>
		<form method="POST" action="{{ route('admin.teacher.unassign.destroy', ['teacher_id' => $teacher->id, 'course_id' => $course->id]) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-8">
			@csrf
			@method('delete')
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white text-center">
					Are you sure you want to unassign
					<span class="text-red-500 font-bold">{{ $teacher->full_name }}</span>
					from
					<span class="text-green-500 font-bold">{{ $course->course_name }}</span>?
				</h1>
			</div>

			<!-- Yes/No Buttons -->
			<div class="flex gap-3 justify-center w-full">
				<x-button type="submit" class="bg-orange-500 w-full md:w-1/12">Yes</x-button>
				<x-button type="button" onclick="history.back()" class="bg-orange-500 w-full md:w-1/12">No</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
