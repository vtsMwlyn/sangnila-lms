@extends("layouts.main-student")

@section("title")
	<h1>My Courses</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Courses</h1>
	@if (Auth::user()->enrolled_courses->where('visibility', 'public')->isNotEmpty())
		<div class="overflow-x-auto rounded-md">
			<table class="min-w-full bg-white border-collapse ">
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
								{{ (App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->details->gender == 1)? "Mrs." : "Ms./Mrs." }} {{ App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", Auth::user()->id)->first()->teacher->full_name }}
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
		</div>
	@else
		<div class="text-blue-900">N/A</div>
	@endif

@endsection
