@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@foreach ($payment_reminders as $reminder)
			@if($reminder["should_pay_soon"])
				<div class="bg-yellow-400 text-orange-700 py-3 px-6 rounded-lg font-semibold mb-3 w-full">
					<i class="bi bi-exclamation-square"></i>
					<span>Your progress in <span class="font-bold">{{ $reminder["course"] }}</span> course is reaching its maximum session. Please do the payment to extend your study in the course.</span>
				</div>
			@endif
		@endforeach

		@forelse (Auth::user()->enrolled_courses->where('status', 'active') as $course)
			<a href="{{ route('student.mycourse.show', $course->id) }}" class="w-full md:w-1/3 transition duration-300 hover:scale-105">
				<div class="rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
					{{-- Course information --}}
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
					@php
						$teacher = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher;
					@endphp
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
						{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
					</div>
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">{{ ucwords($course->delivery_mode) }} Class
					</div>

					{{-- Course progress --}}
					@php
						$all_progress_in_current_course = [];
						foreach(Auth::user()->progress as $pgr){
							if($pgr->course_id == $course->id){
								array_push($all_progress_in_current_course, $pgr);
							}
						}

						$count = 0;
						foreach($all_progress_in_current_course as $curr_pgr){
							if($curr_pgr->status == "unlocked"){
								$count++;
							}
						}

						$max_course_session = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->max_course_session;

						$progress_percentage = ceil(($count / $max_course_session) * 100);
					@endphp

					<div class="w-full flex justify-between mt-12 text-xs">
						<div>Class Progress</div>
						<div class="font-bold">{{ $progress_percentage }}%</div>
					</div>
					<div class="w-full h-5 overflow-hidden relative" style="background-color: #9CA3AF">
						<div class="h-full" style="background: linear-gradient(90deg, #1EB8CD 0%, #BEE2DB 50%, #9CA3AF 80%); width: {{ $progress_percentage * 2 }}%;"></div>
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>

@endsection
