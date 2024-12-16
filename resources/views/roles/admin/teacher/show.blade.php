@extends("layouts.main-admin")

@section("title")
	<h1>Teacher's Details</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ (($teacher->details->gender == 1)? "Mr. " : "Ms. ") . $teacher->full_name }}</span>
@endsection

@section("popup")
	<!-- Unassign Teacher -->
	<x-delete-confirmation method="delete" popup_title="Unassign Teacher" id="unassign-teacher-popup">
		Are you sure want to <span class="font-bold text-red">unassign</span> this teacher from <span class="font-bold text-light-blue" id="unassign-course-name"></span>?
	</x-delete-confirmation>

	<!-- Assign course to teacher -->
	<x-popup popup_title="Assign Teacher to Course" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="assign-teacher-popup">
		<form method="post" class="w-full flex flex-col mt-3">
			@csrf
			<div class="flex flex-col gap-3 w-full">
				<div class="flex flex-col w-full">
					<x-label for="status" :value="__('Course Name')"/>
					<x-select name="course_name" id="course_name" class="w-full">
					</x-select>
				</div>

				<div class="flex gap-3 w-full justify-center">
					<x-button type="submit" class="bg-orange-500 w-1/6 mt-5">
						Assign
					</x-button>
				</div>
			</div>
		</form>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('admin.teacher.index') }}"></x-back-button>
		<x-page-title style="margin-bottom: 0;">{{ ($teacher->details->gender == 1)? "Mr. " : "Ms. " }} {{ $teacher->full_name }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("successAssignToCourse"))
			<x-badge-success badge_text="{{ session('successAssignToCourse') }}"></x-badge-success>
		@elseif(session()->has("successUnassignFromCourse"))
			<x-badge-warning badge_text="{{ session('successUnassignFromCourse') }}"></x-badge-warning>
		@elseif(session()->has("successUpdateTeacherData"))
			<x-badge-success badge_text="{{ session('successUpdateTeacherData') }}"></x-badge-success>
		@endif

		<!-- Teacher informations -->
		<div class="w-full flex flex-col gap-y-4">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Teacher Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Phone Number</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $teacher->details->phone_number ?? 'N/A' }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>City of Birth</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $teacher->details->city_of_birth ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Date of Birth</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $teacher->details->date_of_birth ? Carbon\Carbon::parse($teacher->details->date_of_birth)->format('d F Y') : 'N/A' }}</div>
				</div>
			</div>
		</div>

		<div class="my-4 w-full flex justify-end">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.edit', $teacher->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<!-- Courses and Students List -->
		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="flex my-4 w-full justify-between items-center">
			<h2 class="font-extrabold text-xl text-dark-blue">List of Assigned Courses and Students</h2>
			<x-button type="button" id="assign-teacher-btn" class="bg-orange-500" data-route="{{ route('admin.teacher.assign.store', $teacher->id) }}">
				<i class="bi bi-plus-lg"></i> Assign to Course
			</x-button>
		</div>
		<div class="w-full bg-slate-400" style="height: 2px;"></div>

		@forelse($teacher->teached_courses as $index => $course)
			<div class="flex flex-col w-full gap-4 p-5 @if($index % 2 == 0) bg-white @endif">
				<div class="flex gap-4">
					<a class="text-blue-950 font-bold text-xl hover:text-cyan-500" href="{{ route('admin.course.show', $course->id) }}">{{ $course->course_name }}</a>
					<button type="button" data-route="{{ route('admin.teacher.unassign.destroy', ['teacher_id' => $teacher->id, 'course_id' => $course->id]) }}" data-unassign_course_name="{{ $course->course_name }}" class="unassign-teacher-btn text-white bg-red flex items-center justify-center px-4 py-2 rounded-xl hover:bg-slate-800 hover:scale-105 active:bg-slate-900 focus:scale-95 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 text-xs" style="box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
						Unassign
					</button>
				</div>

				<div class="mt-2 flex flex-wrap">
					@forelse (App\Models\CourseStudent::where('course_id', $course->id)->where('teacher_id', $teacher->id)->get() as $index => $cs)
						<a href="{{ route('admin.student.show', $cs->student->id) }}" class="w-1/6 mb-6 hover:text-cyan-500">
							<div class="flex flex-col items-center">
								@if($cs->student->details->profpic)
									<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;">
								@else
									<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full hover:border-cyan-500 hover:border-4 border-slate-400 w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
								@endif
								<h1 class="text-lg font-bold text-center">{{-- explode(" ", $cs->student->full_name)[0] --}}{{ $cs->student->full_name }}</h1>
							</div>
						</a>
					@empty
						- No students assigned yet to this course -
					@endforelse
				</div>
			</div>
		@empty
			<div class="flex w-full justify-center font-semibold bg-white rounded-xl p-5 mt-5">
				<span>- No courses assigned yet to the teacher -</span>
			</div>
		@endforelse
	</x-section-container>

	<script>
		const allCourses = @json(App\Models\Course::where('status', 'active')->get());

		$(document).ready(() => {
			// Unassign teacher
			$('.unassign-teacher-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#unassign-teacher-popup").find('form').attr("action", $(this).data('route'));
				$("#unassign-course-name").text($(this).data('unassign_course_name'));

				// Show the popup
				$("#unassign-teacher-popup").parent().show();
			});

			$("#assign-teacher-btn").on('click', function() {
				// Get the list of courses already assigned to the teacher
				const courseList = @json($teacher->teached_courses);

				// Filter all courses to exclude those already assigned
				const filtered = allCourses.filter(item =>
					!courseList.some(course => course.id === item.id)
				);

				// Clear existing options in the dropdown (optional)
				$("#course_name").empty();

				// Add the filtered courses to the dropdown
				filtered.forEach(element => {
					const newOption = $("<option>")
						.attr("value", element.id) // Use `id` as the value
						.text(element.course_name);
					$("#course_name").append(newOption);
				});

				// Show the popup
				$("#assign-teacher-popup").find('form').attr('action', $(this).data('route'));
				$("#assign-teacher-popup").parent().show();
			});
		});

	</script>
@endsection
