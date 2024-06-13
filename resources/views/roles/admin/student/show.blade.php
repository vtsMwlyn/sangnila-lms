@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Student's Details") }}</x-page-title>

	@if(session()->has("successAssignToCourse"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successAssignToCourse") }}</p>
		</div>
	@elseif(session()->has("successUnassignFromCourse"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successUnassignFromCourse") }}</p>
		</div>
	@elseif(session()->has("successUpdateStudentData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateStudentData") }}</p>
		</div>
	@elseif(session()->has("successUpdateMaxSession"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateMaxSession") }}</p>
		</div>
	@endif

	<div class="p-10 bg-indigo-200 rounded-3xl mt-10">
		<div class="h-fit mb-5">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.edit', $student->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<div class="overflow-x-auto">
			<table class="w-full" style="border-collapse: separate; border-spacing: 15px 10px;">
				<tbody>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Full name</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->full_name){{ $student->full_name }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Phone number</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->phone_number){{ $student->details->phone_number }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">City of Birth</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->city_of_birth){{ $student->details->city_of_birth }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Date of Birth</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->date_of_birth){{ $student->details->date_of_birth }}@else{{ __("N/A") }}@endif</td>
					</tr>
				</tbody>

				<tbody id="more_details" class="overflow-hidden opacity-0" style="display: none;">
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">School Name</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->school_name){{ $student->details->school_name }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Education Level</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->student_level){{ $student->details->student_level }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Parent's Name
						</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->name_parent){{ $student->details->name_parent }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Parent's Phone Number</td>
						<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($student->details->phone_parent){{ $student->details->phone_parent }}@else{{ __("N/A") }}@endif</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="flex justify-center mt-4">
			<x-button type="button" class="bg-orange-500 w-full md:w-1/3" id="show_more_less_button">{{ __("Show More") }}</x-button>
		</div>

		<script>
			const moreDetails = document.getElementById('more_details');
			const button = document.getElementById('show_more_less_button');

			button.addEventListener('click', function() {
				if (moreDetails.classList.contains('opacity-0')) {
					moreDetails.style.display = "table-row-group";

					// Delaying animation (make the tbody is appeared then the button will go to bottom and finally play the animation)
					setTimeout(() => {
						// Fade in animation
						moreDetails.classList.remove('opacity-0');
						moreDetails.classList.add('transition-opacity', 'duration-300', 'ease-in', 'opacity-100', 'h-full');
					}, 200);

					button.textContent = 'Show Less';

				} else {
					// Fade out animation
					moreDetails.classList.remove('opacity-100');
					moreDetails.classList.add('transition-opacity', 'duration-300', 'ease-out', 'opacity-0', 'max-h-0');

					// Display none the tbody after the animation ends and finally the button will go up back to it previous position
					moreDetails.addEventListener("transitionend", function afterShowLessClicked(){
						moreDetails.removeEventListener("transitionend", afterShowLessClicked);
						moreDetails.style.display = "none";
						button.textContent = 'Show More';
					});
				}
			});
		</script>

		<x-page-title class="mt-8" style="text-align: left;">{{ __("Courses Enrolled") }}</x-page-title>
		<div class="px-10 py-5 border rounded-xl bg-blue-900">
			<div class="py-5">
				<x-anchor-button class="bg-orange-500"
					href="{{ route('admin.student.assign.create', $student->id) }}">
					<i class="bi bi-plus-lg"></i> Assign to course
				</x-anchor-button>
			</div>
			<hr>
			<div class="flex gap-x-10 overflow-x-auto bg-blue-900 rounded-b-xl mt-5">
				@forelse ($student->enrolled_courses as $course)
					<div class="text-white border bg-orange-500 rounded-lg my-5 text-center px-4 py-5 flex flex-col justify-center items-start font-semibold" style="min-width: 300px; max-width: 300px; min-height: 150px;">
						<h1 class="mb-1 font-bold">{{ $course->course_name }}</h1>
						<span class="text-white text-xs mb-5">Teacher: {{ (App\Models\CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->first()->teacher->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ App\Models\CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->first()->teacher->full_name }}</span>
						<span class="text-white text-xs">{{ __("Maximum Sessions") }}</span>
						<div class="flex w-full items-center justify-between mt-3">
							<form action="{{ route("admin.student.max-session.update", [$student->id, $course->id]) }}" method="post" class="flex justify-start gap-2">
								@csrf
								<input type="number" name="{{ __('max_course_session' . $student->id . $course->id) }}" class="rounded-md shadow-sm border text-blue-800 @error('max_course_session' . $student->id . $course->id) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50  @else border-blue-800 focus:border-indigo-400 focus:ring focus:ring-indigo-400 focus:ring-opacity-50 @enderror" style="width: 40%;" value="{{ App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", $student->id)->first()->max_course_session }}"  />
								<x-button class="bg-orange-700 text-white"><i class="bi bi-pencil-square"></i></x-button>
							</form>
							<x-anchor-button
								href="{{ route('admin.student.unassign.delete', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
								class="bg-white text-orange-500 text-xs">
								Unassign
							</x-anchor-button>
						</div>
						@error('max_course_session' . $student->id . $course->id)
							<p class="text-red-500 mt-2 text-left">{{ $message }}</p>
						@enderror
					</div>
				@empty
					<span class="text-white">No courses enrolled</span>
				@endforelse
			</div>
		</div>

		<x-page-title class="mt-10" style="text-align: left;">{{ __("Student's Attendances and Assignments") }}</x-page-title>
		<div class="overflow-x-auto">
			<table class="min-w-full border-collapse sm:table text-sm" style="border-collapse: separate;
			border-spacing: 0 20px;">
				<thead>
					<th class="px-5 py-5 bg-blue-900 text-white rounded-l-xl">Course</th>
					<th class="px-5 py-5 bg-blue-900 text-white">Progress</th>
					<th class="px-5 py-5 bg-blue-900 text-white rounded-r-xl">Assignments</th>
				</thead>
				<tbody>
					@if($student->enrolled_courses->count())
						@for($i = 0; $i < $student->enrolled_courses->count(); $i++)
							<tr class="bg-blue-800 text-white">
								<td class="px-5 py-5 rounded-l-xl text-center">{{ $student->enrolled_courses[$i]->course_name }}</td>
								<td class="px-5 py-5">
									<div class="flex w-full items-center justify-center gap-3">
										<span>{{ $current_progress[$i] }}/{{ $full_progress[$i] }} done</span>
										<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.atd-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
											Details
										</x-anchor-button>
									</div>
								</td>
								<td class="px-5 py-5 rounded-r-xl">
									<div class="flex w-full items-center justify-center gap-3">
										<span>{{ $done_assignment[$i] }}/{{ $assignment_if_full[$i] }} done</span>
										<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.asg-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
											Details
										</x-anchor-button>
									</div>
								</td>
							</tr>
						@endfor
					@else
						<tr><td colspan="3" class="text-center px-5 py-5">- Student isn't assigned to any courses yet -</td></tr>
					@endif
				</tbody>
			</table>
		</div>

	</div>



	{{-- Schedule --}}
	{{-- <h2 class="text-xl font-semibold mb-2">Student Schedules:</h2>

	@if ($student->schedules->isNotEmpty())
		<div class="overflow-x-auto">
			<table class="min-w-full bg-white border-collapse">
				<thead>
					<tr>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-5 sm:w-1/4">Course Name</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-5 sm:w-1/4">Day of Week</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-5 sm:w-1/4">Start Time</th>
						<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-5 sm:w-1/4">End Time</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($student->schedules as $schedule)
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-5 sm:w-1/4">
								<a href="{{ route('admin.course.show', ['course_id' => $schedule->schedule->course->id ]) }}"
									class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
									{{ $schedule->schedule->course->course_name }}
								</a>
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-5 sm:w-1/4">
								{{ $schedule->schedule->day_of_week }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-5 sm:w-1/4">
								{{ $schedule->schedule->start_time }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-5 sm:w-1/4">
								{{ $schedule->schedule->end_time }}
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center py-5">No schedules found for this student.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	@else
		<div class="text-blue-900">N/A</div>
	@endif --}}
@endsection
