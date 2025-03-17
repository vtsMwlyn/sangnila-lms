@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->teached_courses as $course)
			@php
				$topics = $course->topics->where('user_id', Auth::user()->id);
			@endphp
			<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id]) }}"  class="oneperthree transition duration-300 hover:scale-105 relative">
				<div class="rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
					{{-- Course information --}}
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
						{{ App\Models\CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get()->count() }} Students Teached
					</div>
					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ $topics->count() }} Topics
					</div>
					<div class="flex gap-2 items-center">
						@php
							$n_mat = 0;
							foreach($topics as $t){
								$n_mat += $t->activities->count();
							}
						@endphp
						<i class="bi bi-book-half text-slate-400"></i>{{ $n_mat }} Activities
					</div>
				</div>
				@if($course->topics->count() == 0)
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
				@endif
			</a>

		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>
@endsection
