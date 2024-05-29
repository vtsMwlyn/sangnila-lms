@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Assign Student to Course") }}</x-page-title>

	<div class="rounded-xl bg-indigo-200 p-5 mt-10">
		@if($courses->count())
			<form action="{{ route('admin.student.assign.store', $student->id) }}" method="post" class="rounded-lg py-5 px-10 bg-blue-800">
				@csrf
				<!-- Select course -->
				<div class="mt-3">
					<x-label for="visibility" :value="__('Select a course to assign')" style="color: white;"/>
					<select name="course_name" id="course_name" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full mt-1 py-2 px-4">
						@foreach ($courses as $course)
							<option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
						@endforeach
					</select>
				</div>

				<!-- Max Course Sessions -->
				<div class="mt-3">
					<x-label for="max_course_session" :value="__('Maximum sessions in this course')" style="color: white;"/>
					<x-input id="max_course_session" class="block mt-1 w-full" type="number" name="max_course_session" placeholder="Maximum sessions"  />
				</div>

				<div class="flex mt-8 items-center gap-1">
					<x-button type="button" onclick="history.back()" class="bg-orange-500">
						Cancel
					</x-button>

					<x-button class="bg-orange-500">
						{{ __('Add') }}
					</x-button>
				</div>

			</form>
		@else
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- No more courses to assign -</p>
				<x-button type="button" onclick="history.back()" class="bg-orange-500 mt-4">
					Return
				</x-button>
			</div>
		@endif
	</div>

	<div class="rounded-xl py-5 px-10 mt-10 text-white bg-blue-900">List of Assigned Courses</div>

	<div class="rounded-xl bg-indigo-200 py-5 px-10 mt-3">
		<div class="flex gap-x-10 overflow-x-auto">
			@forelse ($student->enrolled_courses as $course)
				<div class="text-white border bg-orange-500 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center gap-3 font-semibold" style="min-width: 200px; min-height: 50px; max-height: 50px;">
					{{ $course->course_name }}
				</div>
			@empty
				<li class="text-gray-500">No course</li>
			@endforelse
		</div>
	</div>
@endsection
