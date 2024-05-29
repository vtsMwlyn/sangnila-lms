@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
	<x-page-title>{{ __("List of Active Students") }}</x-page-title>

	<div class="overflow-x-auto rounded-3xl mt-10 px-10 py-5 bg-indigo-200">
		<table class="min-w-full border-collapse sm:table text-sm" style="border-collapse: separate;
		border-spacing: 0 20px;">
			<thead>
				<tr class="bg-blue-900 text-white">
					<th class="border-blue-400 font-bold px-4 py-5 sm:w-1/4 rounded-l-xl">Student Name</th>
					<th class="border-blue-400 font-bold px-4 py-5 sm:w-1/4">Email</th>
					<th class="border-blue-400 font-bold px-4 py-5 sm:w-1/4" >Enrolled Courses</th>
					<th class="border-blue-400 font-bold px-4 py-5 sm:w-1/4 rounded-r-xl">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if ($students->isNotEmpty())
					@foreach ($students as $student)
						@if($student->status == "disabled")
							@continue
						@endif
						<tr class="bg-blue-800 text-white">
							<td class="border-blue-300 px-4 py-5 sm:w-1/4 rounded-l-xl">
								<a href="{{ route('admin.student.show', ['student_id' => $student->id]) }}"
									class="text-blue-200 hover:text-blue-400 font-semibold hover:underline">
									{{ $student->full_name }}
								</a>
							</td>
							<td class="border-blue-300 px-4 py-5 sm:w-1/4">
								{{ $student->email }}
							</td>
							<td class="border-blue-300 px-6 py-5 sm:w-1/4">
								@if($student->enrolled_courses->count())
									<ul>
										@foreach ($student->enrolled_courses as $course)
											<li class="flex justify-between">
												<span>{{ $course->course_name }}</span>
												{{-- <span>[Progress: 0/0]</span> --}}
												<span>
													@php
														$all_progress_in_current_course = [];
														foreach($student->progress as $pgr){
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

														$cs_data = App\Models\CourseStudent::where("user_id", $student->id)->where("course_id", $course->id)->first();
														$maximum_sessions = $cs_data->max_course_session;

														echo "[Progress: " . $count . "/" . /*count($all_progress_in_current_course)*/ $maximum_sessions . "]";
													@endphp
												</span>
											</li>
										@endforeach
									</ul>
								@else
									<p class="text-center">- No courses assigned yet -</p>
								@endif
							</td>
							<td class="border-blue-300 px-4 py-5 sm:w-1/4 rounded-r-xl">
								<div class="flex w-full justify-center gap-1">
									<x-anchor-button class="bg-orange-500"
									href="{{ route('admin.student.show', $student->id) }}">
										<i class="bi bi-eye"></i>
									</x-anchor-button>
									<x-anchor-button class="bg-orange-500"
										href="{{ route('admin.student.edit', $student->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
								</div>
							</td>
						</tr>
					@endforeach
				@else
					<tr class="bg-white">
						<td colspan="4" class="rounded-xl text-center px-4 py-5 sm:w-1/4">
							- No students yet -
						</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
