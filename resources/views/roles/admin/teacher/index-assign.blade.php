@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<h2 class="text-2xl font-semibold text-blue-900 mb-4">Assign Teacher to Course</h2>

	@if($courses->count())
		<form action="{{ route("admin.teacher.assign.store", $user->id) }}" method="post" class="border rounded-lg p-5">
			@csrf
			<x-label for="visibility" :value="__('Select a course to assign')" />
			<select name="course_name" id="course_name" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full mt-4 py-2 px-4">
				@foreach ($courses as $course)
					<option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
				@endforeach
			</select>

			<div class="flex mt-4 items-center gap-1">
				<x-button type="button" onclick="history.back()" class="bg-indigo-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25">
					Cancel
				</x-button>

				<x-button class="bg-indigo-400">
					{{ __('Add') }}
				</x-button>
			</div>

		</form>
	@else
		<p class="text-gray-500 italic">- No more courses to assign -</p>
	@endif

	<h2 class="text-xl font-semibold mt-6">Courses assigned to this teacher:</h2>

	<ul class="list-disc pl-6 mb-6 mt-3">
		@forelse ($user->teached_courses as $course)
			<li class="text-black">{{ $course->course_name }}</li>
		@empty
			<li class="text-gray-500">No course</li>
		@endforelse
	</ul>
@endsection
