@extends("layouts.main-admin")

@section("title")
	<h1>Teacher's Details</h1>
@endsection


@section("popup")
	{{-- Unassign Teacher --}}
	<x-confirmation method="delete" popup_title="Unassign Teacher" id="unassign-teacher-popup">
		Are you sure want to <span class="font-bold text-red">unassign</span> this teacher from <span class="font-bold text-light-blue" id="unassign-course-name"></span>?
	</x-confirmation>

	{{-- Assign course to teacher --}}
	<x-popup popup_title="Assign Teacher to Course" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="assign-teacher-popup">
		<form method="post" class="w-full flex flex-col mt-3">
			@csrf
			<div class="flex flex-col gap-3 w-full">
				<div class="flex flex-col w-full select2-container">
					<x-label for="course_name">Course Name<span class="text-red">*</span></x-label>
					<x-select name="course_name" id="course_name" class="w-full select-2">
					</x-select>
				</div>

				<div class="flex flex-col w-full">
					<x-label for="rate">Rate<span class="text-red">*</span></x-label>
					<x-input type="number" name="rate" id="rate" class="w-full"/>
				</div>

				<div class="flex gap-3 w-full justify-center">
					<x-button type="submit" class=" w-1/6 mt-5">
						Assign
					</x-button>
				</div>
			</div>

			{{-- Helper --}}
			<input type="hidden" name="h-last-popup" class="h-last-popup">
			<input type="hidden" name="h-route" class="h-route">
			<input type="hidden" name="h-course-teacher" class="h-course-teacher">
		</form>
	</x-popup>

	{{-- Edit assign course teacher info --}}
	<x-popup popup_title="Edit Teacher Course-Assign Info" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-assign-teacher-popup">
		<form method="post" class="w-full flex flex-col mt-3">
			@csrf
			<div class="flex flex-col gap-3 w-full">
				<div class="flex flex-col w-full select2-container">
					<x-label for="e_course_name">Course Name<span class="text-red">*</span></x-label>
					<x-select name="course_name" id="e_course_name" class="w-full select-2">
					</x-select>
				</div>

				<div class="flex flex-col w-full">
					<x-label for="e_rate">Rate<span class="text-red">*</span></x-label>
					<x-input type="number" name="rate" id="e_rate" class="w-full" value="75000"/>
				</div>

				<div class="flex gap-3 w-full justify-center">
					<x-button type="submit" class=" w-1/6 mt-5">
						Save Info
					</x-button>
				</div>
			</div>

			{{-- Helper --}}
			<input type="hidden" name="h-last-popup" class="h-last-popup">
			<input type="hidden" name="h-route" class="h-route">
			<input type="hidden" name="h-course-teacher" class="h-course-teacher">
		</form>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('admin.teacher.index') }}"></x-back-button>
		<x-page-title style="margin-bottom: 0;">{{ ($teacher->details->gender == 1)? "Mr. " : "Ms. " }} {{ $teacher->full_name }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		{{-- Teacher informations --}}
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
			<x-anchor-button  href="{{ route('admin.teacher.edit', $teacher->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		{{-- Courses and Students List --}}
		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="flex my-4 w-full justify-between items-center">
			<h2 class="font-extrabold text-xl text-dark-blue">List of Assigned Courses and Students</h2>
			<div class="relative">
				<x-button type="button" id="assign-teacher-btn"  data-route="{{ route('admin.teacher.assign.store', $teacher->id) }}">
					<i class="bi bi-plus-lg"></i> Assign to Course
				</x-button>
				@if($teacher->teached_courses->count() == 0)
					<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
				@endif
			</div>
		</div>
		<div class="w-full bg-slate-400" style="height: 2px;"></div>

		@forelse($teacher->teached_courses as $index => $course)
			<div class="flex flex-col w-full gap-4 p-5 rounded-xl my-6 bg-white">
				@php
					$ct = App\Models\CourseTeacher::where('user_id', $teacher->id)->where('course_id', $course->id)->first();
				@endphp
				<div class="flex gap-2 items-start">
					<div class="flex flex-col">
						<a class="text-blue-950 font-bold text-xl hover:text-cyan-500" href="{{ route('admin.course.show', $course->id) }}">{{ $course->course_name }} - {{ ucwords($course->level) }}</a>
						<div>Rate: Rp {{ number_format($ct->rate, 2, ',', '.') }}/session</div>
					</div>
					<x-button class="edit-assign-teacher-btn ml-4" data-route="{{ route('admin.teacher.assign.update', $ct->id) }}" data-course_teacher="{{ $ct }}"><i class="bi bi-pencil-square"></i> Edit</x-button>
					<button type="button" data-route="{{ route('admin.teacher.unassign.destroy', ['teacher_id' => $teacher->id, 'course_id' => $course->id]) }}" data-unassign_course_name="{{ $course->course_name }}" class="unassign-teacher-btn text-white bg-red flex items-center justify-center px-4 py-2 rounded-xl hover:bg-slate-800 hover:scale-105 active:bg-slate-900 focus:scale-95 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 font-bold" style="">
						Unassign
					</button>
				</div>

				<div class="flex w-full flex-col">
					@forelse (App\Models\CourseStudent::where('course_id', $course->id)->where('teacher_id', $teacher->id)->orderByRaw('CASE WHEN learning_status = "learning" THEN 0 WHEN learning_status = "complete" THEN 1 ELSE 2 END')->get()->groupBy('learning_status') as $lstatus => $gcs)
						<div class="w-full flex flex-col">
							<button type="button" class="tuguraa flex items-center w-full justify-between mb-4 @if($lstatus == 'learning') bg-light-blue @elseif($lstatus == 'complete') bg-green-600 @else bg-red @endif text-white text-base rounded-lg px-4 py-3 font-normal">
								<span>{{ ucwords($lstatus) }} Students</span>
								<i class="bi @if($lstatus != 'learning') bi-chevron-down @else bi-chevron-up @endif"></i>
							</button>

							<div class="mt-2 flex flex-wrap menyu" style="@if($lstatus != 'learning')display: none;@endif">
								@forelse($gcs as $cs)
									<a href="{{ route('admin.student.show', $cs->student->id) }}" class="w-1/6 mb-6 hover:text-cyan-500">
										<div class="flex flex-col items-center">
											@if($cs->student->details->profpic)
												<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
											@else
												<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full hover:border-cyan-500 hover:border-4 border-slate-400 w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
											@endif
											<h1 class="text-lg font-bold text-center">{{-- explode(" ", $cs->student->full_name)[0] --}}{{ $cs->student->full_name }}</h1>
										</div>
									</a>
								@empty
								@endforelse
							</div>
						</div>
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

		function initializeEditCourseTeacherPopup(route, whichpopup, courseTeacher){
			// Get the list of courses already assigned to the teacher
			const courseList = @json($teacher->teached_courses);

			// Clear existing options in the dropdown (optional)
			$("#e_course_name").empty();

			// Old values
			const oldCourse = '{{ old("course_name") }}';
			const oldRate = '{{ old("rate") }}';

			// Add the filtered courses to the dropdown
			allCourses.forEach(element => {
				const newOption = $("<option>")
					.attr("value", element.id) // Use `id` as the value
					.text(`${element.course_name} - ${element.level.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}`);

				newOption.prop('selected', element.id == (oldCourse ? oldCourse : courseTeacher.course_id) ? true : false);	
				
				$("#e_course_name").append(newOption);
			});

			$('#e_rate').val(oldRate ? oldRate : courseTeacher.rate);

			$('.h-last-popup').val(whichpopup);
			$('.h-route').val(route);
			$('.h-course-teacher').val(courseTeacher);

			// Show the popup
			$(`#${whichpopup}`).find('form').attr('action', route);
			$(`#${whichpopup}`).parent().show();
		}

		function initializeAssignCourseTeacherPopup(route, whichpopup){
			// Get the list of courses already assigned to the teacher
			const courseList = @json($teacher->teached_courses);

			// Filter all courses to exclude those already assigned
			const filtered = allCourses.filter(item =>
				!courseList.some(course => course.id === item.id)
			);

			// Old values
			const oldCourse = '{{ old("course_name") }}';
			const oldRate = '{{ old("rate") }}';

			// Clear existing options in the dropdown (optional)
			$("#course_name").empty();

			// Add the filtered courses to the dropdown
			filtered.forEach(element => {
				const newOption = $("<option>")
					.attr("value", element.id) // Use `id` as the value
					.text(`${element.course_name} - ${element.level.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}`);

				if(oldCourse){
					newOption.prop('selected', element.id == oldCourse ? true : false);
				}
				
				$("#course_name").append(newOption);
			});

			$('.h-last-popup').val(whichpopup);
			$('.h-route').val(route);

			$('#rate').val(oldRate ? oldRate : 75000);

			// Show the popup
			$(`#${whichpopup}`).find('form').attr('action', route);
			$(`#${whichpopup}`).parent().show();
		}

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
				initializeAssignCourseTeacherPopup($(this).data('route'), 'assign-teacher-popup');
			});

			$(".edit-assign-teacher-btn").on('click', function() {
				initializeEditCourseTeacherPopup($(this).data('route'), 'edit-assign-teacher-popup', $(this).data('course_teacher'));
			});

			// Redisplay popup and fill with prev data (for invalidated data)
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_popup = @json(old('h-last-popup'));

				if(old_popup == "edit-assign-teacher-popup"){
					const old_route = @json(old('h-route'));
					const old_popup = @json(old('h-last-popup'));
					const old_course_teacher = @json(old('h-course-teacher'));

					initializeEditCourseTeacherPopup(old_route, old_popup, old_course_teacher);
				}
				else if(old_popup == "assign-teacher-popup"){
					const old_route = @json(old('h-route'));
					const old_popup = @json(old('h-last-popup'));

					initializeAssignCourseTeacherPopup(old_route, old_popup);
				}
			@endif

			$('.tuguraa').on('click', function(){
				$(this).closest('div').find('.menyu').slideToggle();
			});
		});

	</script>
@endsection
