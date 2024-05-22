@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Students</h1>

	@if ($students->isNotEmpty())
		<div class="overflow-x-auto rounded-md">
			<table class="min-w-full bg-white border-collapse ">
				<thead>
					<tr>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Student Name</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Email</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4" >Enrolled Courses and Progress</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($students as $student)
						@if($student->status == "disabled")
							@continue
						@endif
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								<a href="{{ route('admin.student.show', ['student_id' => $student->id]) }}"
									class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
									{{ $student->full_name }}
								</a>
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								{{ $student->email }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-6 py-2 sm:w-1/4">
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

														echo "[Progress: " . $count . "/" . count($all_progress_in_current_course) . "]";
													@endphp
												</span>
											</li>
										@endforeach
									</ul>
								@else
									<p class="text-center">- No courses assigned yet -</p>
								@endif
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
								<div class="flex w-full justify-center gap-1">
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
									href="{{ route("admin.student.show", $student->id) }}">
										View
									</a>
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
										href="{{ route("admin.student.edit", $student->id) }}">
										Edit
									</a>
								</div>
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
