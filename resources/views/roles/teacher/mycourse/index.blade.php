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

						{{-- <!-- Course progress -->
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
						</div> --}}
					</div>
				</a>
			@empty
				<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
			@endforelse
		</div>
		{{-- <x-page-title>My Courses</x-page-title>
		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Course Name</th>
					<th class="template-heads">Description</th>
					<th class="template-heads rounded-r-xl">Actions</th>
				</x-slot>

				@if (Auth::user()->teached_courses->isNotEmpty())
					@foreach (Auth::user()->teached_courses as $course)
						<tr>
							<td class="template-bodies rounded-l-xl w-1/4">
								<a href="{{ route('teacher.mycourse.show', ['course_id' => $course->id]) }}"
									class="font-bold text-blue-200 hover:text-blue-400 hover:underline">
									{{ $course->course_name }}
								</a>
							</td>

							<td class="template-bodies">
								{{ substr($course->course_description, 0, 100) }}...
							</td>
							<td class="template-bodies rounded-r-xl">
								<x-anchor-button class="bg-orange-500"
									href="{{ route('teacher.student.select-student', $course->id) }}">
									View Students Progress
								</x-anchor-button>
							</td>
						</tr>
					@endforeach
				@else
					<tr><td class="p-5 bg-white rounded-xl font-semibold text-center" colspan="3">- No courses assigned yet -</td></tr>
				@endif
			</x-table>
		</div> --}}
@endsection
