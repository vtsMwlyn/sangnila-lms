@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->enrolled_courses->where('status', 'active') as $course)
			<a href="{{ route('student.learning-documentation.show', $course->id) }}" class="w-full md:w-1/3 transition duration-300 hover:scale-105">
				<div class="bg-white rounded-3xl p-5 shadow-lg">
					<!-- Course information -->
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
					@php
						// $teacher = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher;
					@endphp
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
						N/A Portfolio Uploaded
					</div>
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">
                        N/A Unknown
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>

@endsection
