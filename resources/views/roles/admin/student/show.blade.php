@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $student->full_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Student's Details") }}</x-page-title>

		@if(App\Models\ImportedStudent::where("student_id", $student->id)->first())
			<div class="w-full bg-yellow-300 px-5 py-3 my-8 rounded-lg">
				<p class="text-yellow-700 font-semibold" ><i class="bi bi-info-circle"></i> <span class="font-bold">This student is imported.</span> You can unset the imported status when the attendance data of the students are fully inserted by <a href="{{ route("admin.student.normalize.confirmation", [$student->id]) }}" class="hover:underline font-bold hover:font-extrabold">clicking here</a>.</p>
			</div>
		@endif

		@if(session()->has("successAssignToCourse"))
			<x-badge-success badge_text="{{ session('successAssignToCourse') }}"></x-badge-success>
		@elseif(session()->has("successNormalize"))
			<x-badge-success badge_text="{{ session('successNormalize') }}"></x-badge-success>
		@elseif(session()->has("successUnassignFromCourse"))
			<x-badge-warning badge_text="{{ session('successUnassignFromCourse') }}"></x-badge-warning>
		@elseif(session()->has("successUpdateStudentData"))
			<x-badge-success badge_text="{{ session('successUpdateStudentData') }}"></x-badge-success>
		@elseif(session()->has("successUpdateMaxSession"))
			<x-badge-success badge_text="{{ session('successUpdateMaxSession') }}"></x-badge-success>
		@endif

		<div class="h-fit my-8">
			<x-anchor-button type="button" class="bg-orange-500" href="{{ route('admin.student.edit', $student->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<div class="overflow-x-auto">
			<x-horizontal-table>
				<tbody>
					<tr>
						<td class="template-hheads w-1/3">Full Name</td>
						<td class="template-hbodies">@if($student->full_name){{ $student->full_name }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="template-hheads w-1/3">Phone number</td>
						<td class="template-hbodies">@if($student->details->phone_number){{ $student->details->phone_number }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="template-hheads w-1/3">City of Birth</td>
						<td class="template-hbodies">@if($student->details->city_of_birth){{ $student->details->city_of_birth }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="template-hheads w-1/3">Date of Birth</td>
						<td class="template-hbodies">@if($student->details->date_of_birth){{ $student->details->date_of_birth }}@else{{ __("N/A") }}@endif</td>
					</tr>
				</tbody>

				<tbody id="more_details" class="overflow-hidden opacity-0" style="display: none;">
					<tr>
						<td class="template-hheads w-1/3">School Name</td>
						<td class="template-hbodies">@if($student->details->school_name){{ $student->details->school_name }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="template-hheads w-1/3">Education Level</td>
						<td class="template-hbodies">@if($student->details->student_level){{ $student->details->student_level }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="template-hheads w-1/3">Parent's Name
						</td>
						<td class="template-hbodies">@if($student->details->name_parent){{ $student->details->name_parent }}@else{{ __("N/A") }}@endif</td>
					</tr>
					<tr>
						<td class="template-hheads w-1/3">Parent's Phone Number</td>
						<td class="template-hbodies">@if($student->details->phone_parent){{ $student->details->phone_parent }}@else{{ __("N/A") }}@endif</td>
					</tr>
				</tbody>
			</x-horizontal-table>
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

		<h1 class="mt-8 text-blue-950 font-bold text-2xl" style="text-align: left;">{{ __("Courses Enrolled") }}</h1>
		<div class="mt-8">
			<div class="">
				<x-anchor-button class="bg-orange-500"
					href="{{ route('admin.student.assign.create', $student->id) }}">
					<i class="bi bi-plus-lg"></i> Assign to course
				</x-anchor-button>
			</div>
			<div class="flex gap-10 flex-wrap mt-5">
				@forelse ($student->enrolled_courses as $course)
					<!-- Card -->
					<div class="flex flex-col justify-center items-center gap-5 border-2 border-white rounded-xl text-white w-full md:w-1/3 px-8 " style="background: linear-gradient(to bottom, rgba(40, 55, 133, 0.53) 25%, rgba(235, 126, 37, 0.58)); min-height: 400px;">
						<h1 class="text-3xl font-bold">{{ $course->course_name }}</h1>
						<span class="border border-white rounded-lg px-4 py-2 text-md">Teacher: {{ (App\Models\CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->first()->teacher->details->gender == 1)? "Mr." : "Ms." }} {{ App\Models\CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->first()->teacher->full_name }}</span>

						<span class="text-white">{{ __("Maximum Sessions") }}</span>
						<div class="flex flex-col md:flex-row w-full items-center justify-center mt-3 gap-5">
							<form action="{{ route("admin.student.max-session.update", [$student->id, $course->id]) }}" method="post" class="flex gap-2">
								@csrf
								<input type="number" name="{{ __('max_course_session' . $student->id . $course->id) }}" class="rounded-md shadow-sm border text-blue-800 @error('max_course_session' . $student->id . $course->id) border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50  @else border-blue-800 focus:border-indigo-400 focus:ring focus:ring-indigo-400 focus:ring-opacity-50 @enderror" value="{{ App\Models\CourseStudent::where("course_id", $course->id)->where("student_id", $student->id)->first()->max_course_session }}"  style="max-width: 100px;"/>
								<x-button class="bg-orange-700 text-white" onclick="return confirm('Are you sure want to change the maximum session of this student in this course?');"><i class="bi bi-pencil-square"></i></x-button>
							</form>
							<x-anchor-button
								href="{{ route('admin.student.unassign.delete', ['student_id' => $student->id, 'course_id' => $course->id]) }}"
								class="bg-white text-orange-500 text-sm">
								Unassign
							</x-anchor-button>
						</div>

						@error('max_course_session' . $student->id . $course->id)
							<p class="text-red-500 mt-2 text-left">{{ $message }}</p>
						@enderror
					</div>
				@empty
					<div class="bg-white p-5 w-full rounded-xl font-semibold text-center">- No courses enrolled yet -</div>
				@endforelse
			</div>
		</div>

		<h1 class="mt-10 text-blue-950 font-bold text-2xl" style="text-align: left;" id="student-summary">{{ __("Student's Attendances and Assignments") }}</h1>
		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Course</th>
					<th class="template-heads">Attendance & Progress</th>
					<th class="template-heads rounded-r-xl">Assignments</th>
				</x-slot>
				@if($student->enrolled_courses->count())
					@for($i = 0; $i < $student->enrolled_courses->count(); $i++)
						<tr>
							<td class="template-bodies rounded-l-xl">{{ $student->enrolled_courses[$i]->course_name }}</td>
							<td class="template-bodies">
								<div class="flex w-full items-center justify-center gap-3">
									<span>{{ $current_progress[$i] }}/{{ $full_progress[$i] }} done</span>
									<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.atd-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
										Details
									</x-anchor-button>
								</div>
							</td>
							<td class="template-bodies rounded-r-xl">
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
					<tr><td colspan="3" class="text-center font-semibold p-5 bg-white rounded-xl">- Student isn't assigned to any courses yet -</td></tr>
				@endif
			</x-table>
		</div>

	</x-section-container>



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
