@extends("layouts.main-student")

@section("title")
	<h1>My Courses</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">My Courses</x-page-title>
		@foreach ($payment_reminders as $reminder)
			@if($reminder["should_pay_soon"])
				<div class="bg-yellow-400 text-orange-700 py-3 px-6 rounded-lg font-semibold mb-3">
					<i class="bi bi-exclamation-square"></i>
					<span>Your progress in <span class="font-bold">{{ $reminder["course"] }}</span> course is reaching its maximum session. Please do the payment to extend your study in the course.</span>
				</div>
			@endif
		@endforeach

		@if (Auth::user()->enrolled_courses->where('visibility', 'public')->isNotEmpty())
			{{-- <div class="overflow-x-auto rounded-md mt-8">
				<table class="min-w-full bg-white border-collapse">
					<thead>
						<tr>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Course Name</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Description</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Lecturer</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Progress</td>
						</tr>
					</thead>
					<tbody>
						@foreach (Auth::user()->enrolled_courses->where('visibility', 'public') as $course)
							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									<a href="{{ route('student.mycourse.show', ['course_id' => $course->id]) }}"
										class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
										{{ $course->course_name }}
									</a>
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									{{ $course->course_description }}
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
									{{ (App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->full_name }}
								</td>

								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
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

										echo "[Progress: " . $count . "/" . $max_course_session . "]";
									@endphp
								</td>

							</tr>
						@endforeach
					</tbody>
				</table>
			</div> --}}

			<div class="w-full flex flex-wrap justify-center my-8 gap-16">
				@foreach (Auth::user()->enrolled_courses->where('visibility', 'public') as $course)
					<!-- Course card  -->
					<a href="{{ route('student.mycourse.show', ['course_id' => $course->id]) }}" class="w-full md:w-1/3">
						<div class="flex flex-col justify-center items-center gap-5 border-2 border-white rounded-xl text-white px-8  hover:scale-105 transition duration-300 ease-in-out" style="background: linear-gradient(to bottom, rgba(40, 55, 133, 0.53) 25%, rgba(235, 126, 37, 0.58)); min-height: 400px; cursor: url('{{ asset('img/cursor2.cur') }}'), pointer;">
							<h1 class="text-3xl font-bold">{{ $course->course_name }}</h1>
							<div class="border border-white rounded-lg px-4 py-2 text-md">Teacher: {{ (App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->full_name }}</div>

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
			<div class="text-blue-900">N/A</div>
		@endif
	</x-section-container>
@endsection
