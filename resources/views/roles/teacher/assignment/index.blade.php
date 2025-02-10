@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Select Course</span>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->teached_courses as $course)
			<a href="{{ route('teacher.assignment.show', $course->id) }}"  style="width: 32%;" class="transition duration-300 hover:scale-105">
				<div class="bg-white rounded-3xl p-5 shadow-lg">
					<!-- Counting assignments posted and nearest deadline -->
					@php
						$assignments = App\Models\Assignment::where("teacher_id", Auth::user()->id)->where("course_id", $course->id)->get();

						$nd = [];

						foreach($assignments as $asg){
							if($asg->deadline_date > now()){
								array_push($nd, $asg->deadline_date . " " . $asg->deadline_time);
							}
						}

						$nearest = (count($nd) > 0)? min($nd) : "N/A";
					@endphp

					<!-- Course information -->
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ $assignments->count() }} Assignments Posted
					</div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">Nearest Deadline: {{ $nearest == 'N/A' ? $nearest : Carbon\Carbon::parse($nearest)->format("d M Y, H:i") }} GMT+7
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>
@endsection
