@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->teached_courses as $course)
			<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id]) }}"  style="width: 32%;" class="transition duration-300 hover:scale-105">
				<div class="bg-white rounded-3xl p-5 shadow-lg">
					<!-- Course information -->
					<p class="font-bold text-dark-blue">{{ $course->course_name }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
						{{ App\Models\CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get()->count() }} Students Teached
					</div>
					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ $course->topics->count() }} Topics
					</div>
					<div class="flex gap-2 items-center">
						@php
							$n_mat = 0;
							foreach($course->topics as $t){
								$n_mat += $t->materials->count();
							}
						@endphp
						<i class="bi bi-book-half text-slate-400"></i>{{ $n_mat }} Activities/Materials
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>
@endsection
