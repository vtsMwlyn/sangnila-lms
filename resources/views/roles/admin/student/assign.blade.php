@extends("layouts.main-admin")

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Assign Student</h1>
	<form method="POST" action="{{ route('admin.student.assign.store', $student->id) }}"
		class="bg-white rounded-2xl p-5 border-blue-300 border-2">
		@csrf
		<!-- Student Name -->
		<div class="mb-6">
			<h1 class="text-xl font-semibold text-blue-900">Assign {{ $student->full_name }} to course:</h1>
		</div>

		<!-- Course Selection -->
		<div class="mt-4">
			<x-label for="course" :value="__('Course')" />
			@if ($courses->isEmpty())
				<p class="text-lg text-gray-700">No Course to assign.</p>
				<a href="{{ route('admin.student.show', $student->id) }}"
					class="px-3 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300 mt-2 inline-block">Go back to previous page</a>
			@else
				<select name="course" id="course"
					class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
					@foreach ($courses as $course)
						<option value="{{ $course->id }}">{{ $course->course_name }}</option>
					@endforeach
				</select>
				@if ($courses->isNotEmpty())
					<div class="flex items-center justify-end mt-4">
						<x-button class="ml-3 bg-indigo-400">
							{{ __('ASSIGN') }}
						</x-button>
					</div>
				@endif
			@endif
		</div>
	</form>
@endsection
