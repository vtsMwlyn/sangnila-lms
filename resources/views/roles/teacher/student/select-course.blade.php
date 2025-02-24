@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Select Course</span>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->teached_courses as $course)
			<!-- Counting how many students has no access at all to any activities -->
			@php
				$course_students = App\Models\CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

				$n = 0;

				foreach($course_students as $cs){
					$progress_per_student = App\Models\Progress::where("course_id", $course->id)->where("student_id", $cs->student->id)->get();

					if($progress_per_student->count() > 0){
						$no_activities_unlocked_yet = true;

						foreach($progress_per_student as $p){
							if($p->status == "unlocked"){
								$no_activities_unlocked_yet = false;
								break;
							}
						}

						if($no_activities_unlocked_yet){
							$n++;
						}
					}
					else {
						$n++;
					}
				}
			@endphp

			<a href="{{ route('teacher.student.select-student', $course->id) }}"  class="oneperthree transition duration-300 hover:scale-105 relative">
				<div class="bg-white rounded-3xl p-5 shadow-lg">
					<!-- Course information -->
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
						{{ App\Models\CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get()->count() }} Students Teached
					</div>
					<div class="flex gap-2 items-center @if($n > 0) text-red @endif">
						<i class="bi bi-book-half text-slate-400"></i>{{ $n }} Students Has No Access To Any Activities
					</div>
				</div>
				@if($n > 0)
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
				@endif
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>
@endsection
