@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("content")
	@foreach ($payment_reminders as $reminder)
		@if($reminder["should_pay_soon"])
			<div class="bg-yellow-400 text-orange-700 py-3 px-6 rounded-lg font-semibold mb-3">
				<i class="bi bi-exclamation-square"></i>
				<span>Your progress in <span class="font-bold">{{ $reminder["course"] }}</span> course is reaching its maximum session. Please do the payment to extend your study in the course.</span>
			</div>
		@endif
	@endforeach

	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->enrolled_courses->where('visibility', 'public') as $course)
			<a href="{{ route('student.mycourse.show', $course->id) }}"  style="width: 32%;" class="transition duration-300 hover:scale-105">
				<div class="bg-white rounded-3xl p-5 shadow-lg">
					<!-- Course information -->
					<p class="font-bold text-dark-blue">{{ $course->course_name }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
					@php
						$teacher = App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher;
					@endphp
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
						{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
					</div>
					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">N/A
					</div>

					<!-- Course progress -->
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

	{{-- @if (Auth::user()->enrolled_courses->where('visibility', 'public')->isNotEmpty())
		<div class="w-full flex flex-wrap justify-center my-8 gap-16">
			@foreach (Auth::user()->enrolled_courses->where('visibility', 'public') as $course)
				<!-- Course card  -->
				<a href="{{ route('student.mycourse.show', ['course_id' => $course->id]) }}" class="w-full md:w-1/3">
					<div class="flex flex-col justify-center items-center gap-5 border-2 border-white rounded-xl text-white px-8  hover:scale-105 transition duration-300 ease-in-out" style="background: linear-gradient(to bottom, rgba(40, 55, 133, 0.53) 25%, rgba(235, 126, 37, 0.58)); min-height: 400px; cursor: url('{{ asset('img/cursor2.cur') }}'), pointer;">
						<h1 class="text-3xl font-bold">{{ $course->course_name }}</h1>
						<div class="border border-white rounded-lg px-4 py-2 text-md">Teacher: {{ (App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->details->gender == 1)? "Mr." : "Ms." }} {{ App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->full_name }}</div>

						<h3 class="text-sm">Progress</h3>
						<div class="w-full h-8 bg-slate-400 rounded-lg overflow-hidden relative">
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

								$progress_text = $count . "/" . $max_course_session;
								$progress_percentage = ceil(($count / $max_course_session) * 100);
							@endphp

							<div class="h-full rounded-lg" style="background: linear-gradient(to right, rgb(34 197 94) 60%, transparent 100%); @if($progress_percentage == 0) width: 0% @elseif($progress_percentage < 50) width: {{ $progress_percentage }}% @elseif($progress_percentage == 100) width: 150% @else width: {{ $progress_percentage + 10 }}% @endif;"></div>
							<div class="absolute top-0 left-0 w-full h-full flex items-center justify-center text-black">
								{{ $progress_text }}
							</div>
						</div>
					</div>
				</a>
			@endforeach
		</div>
	@else
		<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
	@endif --}}

@endsection
