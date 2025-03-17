@extends("layouts.main-admin")

@section("title")
	<h1>Student's Details</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $student->full_name }}</span>
@endsection

@section("popup")
	{{-- Change imported student information --}}
	<x-popup popup_title="Edit Student Import Information" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-student-import-info-popup">
		<form method="post" class="w-full flex flex-col mt-3">
			@csrf

			<div class="flex flex-col gap-3 w-full">
				<div class="flex flex-col w-full">
					<x-label for="status">Uninserted Attendance Count<span class="text-red">*</span></x-label>
					<x-input type="text" name="last_attendance_count" id="last_attendance_count" class="w-full mt-1" />
				</div>

				<div class="flex gap-3 w-full justify-center">
					<x-button type="submit" class=" w-1/6 mt-5">
						Edit
					</x-button>
				</div>
			</div>

			{{-- Helper --}}
			<input type="hidden" name="h-last-popup" class="h-last-popup">
			<input type="hidden" name="h-route" class="h-route">
			<input type="hidden" name="h-imported-student" class="h-imported-student">
		</form>
	</x-popup>

	{{-- Unassign Student --}}
	<x-confirmation method="delete" popup_title="Unassign Student" id="unassign-student-popup">
		Are you sure want to <span class="font-bold text-red">unassign</span> this student from <span class="font-bold text-light-blue" id="unassign-course-name"></span>?
	</x-confirmation>

	{{-- Assign course to student --}}
	<x-popup popup_title="Assign Student to Course" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="assign-student-popup">
		<form method="post" class="w-full flex flex-col mt-3">
			@csrf

			<div class="flex flex-col gap-3 w-full">
				<div class="flex flex-col w-full">
					<x-label for="status">Course Name<span class="text-red">*</span></x-label>
					<x-select name="course" id="course" class="w-full mt-1">
					</x-select>
				</div>

				<div class="flex flex-col w-full">
					<x-label for="status">Teacher<span class="text-red">*</span></x-label>
					<x-select name="teacher" id="teacher" class="w-full mt-1">
					</x-select>
				</div>

				<div class="flex flex-col w-full">
					<x-label for="status">Max Course Session<span class="text-red">*</span></x-label>
					<x-input type="number" name="max_course_session" id="max_course_session" class="w-full mt-1" value="{{ old('max_course_session') }}" placeholder="Enter max course session"/>
				</div>

				<div class="flex flex-col w-full">
					<x-label for="status">Learning Status<span class="text-red">*</span></x-label>
					<x-select name="learning_status" id="learning_status" class="w-full mt-1">
						<option>Select a learning status</option>
						<option value="learning" @if(old('learning_status') == 'learning') selected @endif>Learning</option>
						<option value="complete" @if(old('learning_status') == 'complete') selected @endif>Complete</option>
						<option value="undone" @if(old('learning_status') == 'undone') selected @endif>Undone</option>
					</x-select>
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
		</form>
	</x-popup>

	{{-- Edit student course assign information --}}
	<x-popup popup_title="Edit Course Assign Information" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="edit-assign-info-popup">
		<form method="post" class="w-full flex flex-col mt-3">
			@csrf

			<div class="flex flex-col gap-3 w-full">
				<div class="flex flex-col w-full">
					<x-label for="status">Teacher<span class="text-red">*</span></x-label>
					<x-select name="teacher" id="teacher" class="w-full mt-1">
					</x-select>
				</div>

				<div class="flex flex-col w-full">
					<x-label for="status">Max Course Session<span class="text-red">*</span></x-label>
					<x-input type="number" name="max_course_session" id="max_course_session" class="w-full mt-1" />
				</div>

				<div class="flex flex-col w-full">
					<x-label for="status">Learning Status<span class="text-red">*</span></x-label>
					<x-select name="learning_status" id="learning_status" class="w-full mt-1">
						<option value="learning">Learning</option>
						<option value="complete">Complete</option>
						<option value="undone">Undone</option>
					</x-select>
				</div>

				<div class="flex gap-3 w-full justify-center">
					<x-button type="submit" class=" w-1/6 mt-5">
						Save
					</x-button>
				</div>
			</div>

			{{-- Helper --}}
			<input type="hidden" name="h-last-popup" class="h-last-popup">
			<input type="hidden" name="h-route" class="h-route">
			<input type="hidden" name="h-courseStudent" class="h-courseStudent">
		</form>
	</x-popup>

	{{-- Student attendance information --}}
	<x-popup popup_title="Attendance Information" id="attendance-information-popup" class="w-5/6 flex flex-col items-stretch justify-center overflow-y-auto">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">Session</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attended</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Time</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Uploader</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
					</thead>
					<tbody id="attendance-information-tbody">
					</tbody>
				</table>
			</div>
		</div>
	</x-popup>

	{{-- Student assignment information --}}
	<x-popup popup_title="Assignment Information" id="assignment-information-popup" class="w-4/5 flex flex-col items-stretch justify-center overflow-y-auto">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Assignment Title</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Description</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Uploader</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Submission Status</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Latest Submission</th>
					</thead>
					<tbody id="assignment-information-tbody">
					</tbody>
				</table>
			</div>
		</div>
	</x-popup>

	{{-- Student assessment information --}}
	<x-popup popup_title="Assessment from Lecturer" id="assessment-popup" class="w-4/5 flex flex-col items-stretch justify-center overflow-y-auto">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Category</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Score</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Evaluation/Comments</th>
					</thead>
					<tbody id="assessment-tbody">
					</tbody>
				</table>
			</div>
		</div>

		<div id="certificate_access_status" class="flex items-center gap-1 mt-4"></div>

		<div class="flex w-full justify-center mt-5 gap-2">
			<x-anchor-button class="w-1/6" id="preview-certificate-btn" target="_blank"><i class="bi bi-eye"></i> Preview Certificate</x-anchor-button>
			<x-anchor-button class="w-1/6" id="edit-assessment-btn"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('admin.student.index') }}"></x-back-button>
		<x-page-title style="margin-bottom: 0;">{{ $student->full_name }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		@php
			$isImportedData = App\Models\ImportedStudent::where("student_id", $student->id)->get()
		@endphp

		@if($isImportedData->count())
			<div class="w-full bg-yellow-300 px-5 py-3 rounded-lg">
				<p class="text-yellow-700 font-semibold"><i class="bi bi-info-circle"></i> <span class="font-extrabold">This student is imported.</span> Total existing attendance with status 'attended' of this student will always be added by the uninserted attendance counts. You can unset the imported status when the attendance data of the students are fully inserted.</p>
			</div>

			<h1 class="font-bold text-lg text-blue mt-4">Previous Progress Information</h1>

			<div class="w-full overflow-x-auto mt-2 mb-8">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Uninserted Attendance Count</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@foreach ($isImportedData as $impdat)
							<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
								<td class="py-2 px-4">{{ $impdat->course->course_name }} - {{ ucwords($impdat->course->level) }}</td>
								<td class="py-2 px-4">{{ $impdat->last_attendance_count }}</td>
								<td class="py-2 px-4">
									<div class="w-full flex gap-3 justify-start">
										<x-button type="button" class="edit-student-import-info-btn" data-route="{{ route('admin.student.imported-data.update', [$impdat->student->id, $impdat->course_id]) }}" data-uninserted_attendance_count="{{ $impdat->last_attendance_count }}"><i class="bi bi-pencil-square"></i> Edit</x-button>

										<form action="{{ route('admin.student.normalize.proceed', [$impdat->student->id, $impdat->course->id]) }}" method="post">
											@csrf
											<x-button type="submit" onclick="return confirm('Are you sure want to normalize this student?');"><i class="bi bi-database-check"></i> Normalize</x-button>
										</form>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endif

		{{-- Students Information --}}
		<h1 class="font-bold text-lg text-blue">Student Information</h1>

		<div class="w-full flex flex-col gap-y-4 mt-2">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Student Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->full_name }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Phone Number</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->phone_number ?? 'N/A' }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>City of Birth</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->city_of_birth ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Date of Birth</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->date_of_birth ? Carbon\Carbon::parse($student->details->date_of_birth)->format('d F Y') : 'N/A' }}</div>
				</div>
			</div>
		</div>

		<div class="w-full flex flex-col gap-y-4 mt-4" id="more_details" style="display: none;">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Parent's Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->name_parent ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Parent's Phone Number</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->phone_parent ?? 'N/A' }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>School Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold font-bold" style="border-width: 3px">{{ $student->details->school_name ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Education Level</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->student_level ?? 'N/A' }}</div>
				</div>
			</div>
		</div>

		<div class="w-full flex justify-end items-start mt-5 gap-3">
			<x-button type="button"  id="show_more_less_button"><i class="bi bi-eye"></i> Show Details</x-button>
			<x-anchor-button type="button"  href="{{ route('admin.student.edit', $student->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<div class="w-full bg-slate-400 mt-16" style="height: 2px;"></div>
			<div class="w-full flex items-center justify-between">
				<h2 class="my-4 font-extrabold text-xl text-dark-blue">List of Enrolled Courses</h2>
				<div class="relative">
					<x-button type="button" id="assign-student-btn"  data-route="{{ route('admin.student.assign.store', $student->id) }}">
						<i class="bi bi-plus-lg"></i> Assign to course
					</x-button>
					@if($student->enrolled_courses->count() == 0)
						<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
					@endif
				</div>
			</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-4">
			<div class="flex flex-wrap gap-4">
				@forelse ($student->enrolled_courses as $course)
					{{-- Card --}}
					<div class="flex flex-col bg-white rounded-xl p-5" style="width: 32%;">
						@php
							$cs = App\Models\CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->with(['course', 'teacher'])->first();
							$teacher = $cs->teacher;
						@endphp

						<h1 class="text-xl font-bold"><a href="{{ route('admin.course.show', $course->id) }}" class="text-blue-950 hover:text-cyan-500">{{ $course->course_name }} - {{ ucwords($course->level) }}</a></h1>
						<div class="w-full bg-slate-400 my-2" style="height: 2px;"></div>

						<div class="flex gap-2 items-center">
							<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
							@if($cs->learning_status == 'learning')
								<span class="font-bold text-light-blue">Learning</span>
							@elseif($cs->learning_status == 'complete')
								<span class="font-bold text-green-600">Complete</span>
							@else
								<span class="font-bold text-red">Undone</span>
							@endif
						</div>
						<div class="flex gap-2 items-center">
							<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
							{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
						</div>
						<div class="flex gap-2 items-center">
							<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
							{{ $cs->max_course_session }} Sessions (Max)
						</div>

						<div class="flex w-full justify-between items-center text-sm mt-4">
							<div class="flex items-center gap-2">
								<x-button type="button" data-route="{{ route('admin.student.assign.update', $cs->id) }}" data-cs="{{ $cs }}" class="text-white edit-assign-student-btn"><i class="bi bi-pencil-square"></i> Edit</x-button>
								<button type="button" data-route="{{ route('admin.student.unassign.destroy', ['student_id' => $student->id, 'course_id' => $course->id]) }}" data-unassign_course_name="{{ $course->course_name }}" class="unassign-student-btn text-white bg-red flex items-center justify-center px-4 py-2 rounded-xl hover:bg-slate-800 hover:scale-105 active:bg-slate-900 focus:scale-95 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 font-bold" style="">
									Unassign
								</button>
							</div>
						</div>
					</div>
				@empty
					<div class="bg-white p-5 w-full rounded-xl font-semibold text-center">- No courses enrolled yet -</div>
				@endforelse
			</div>
		</div>

		<div class="w-full bg-slate-400 mt-16" style="height: 2px;"></div>
		<div class="w-full flex items-center justify-between">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Learning Information</h2>
			<x-anchor-button href="{{ route('admin.student.input-attendance', $student->id) }}"><i class="bi bi-database-add"></i> Input Attendances Data</x-anchor-button>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attendances</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Assignments</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Assessment</th>
				</thead>
				<tbody>
					@forelse ($student->enrolled_courses as $i => $ec)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4 w-1/4">
								{{ $ec->course_name }} - {{ ucwords($ec->level) }}
							</td>
							<td class="py-2 px-4 w-1/4">
								<div class="flex items-center justify-between w-2/3">
									{{ $current_progress[$i] }}/{{ $full_progress[$i] }} sessions
									<button type="button" class="attendance-information-btn" data-student="{{ $student->id }}" data-std_attendances="{{ json_encode($attendance_data[$ec->id]) }}">
										<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</button>
								</div>
							</td>
							<td class="py-2 px-4 w-1/4">
								<div class="flex items-center justify-between w-2/3">
									{{ $done_assignment[$i] }}/{{ $assignment_if_full[$i] }} done
									<button type="button" class="assignment-information-btn" data-std_assignments="{{ json_encode($assignment_data[$ec->id]) }}">
										<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</button>
								</div>
							</td>
							<td class="py-2 px-4 w-1/4">
								@if($assessment_data[$i])
									<div class="flex items-center justify-between w-2/3">
										Uploaded
										<button type="button" class="view-assessment-btn" data-std_assessment="{{ json_encode($assessment_data[$i]) }}" data-route="{{ route('admin.student.edit.assessment', $assessment_data[$i]->id) }}" data-view_certificate="{{ route('admin.student.view-certificate', [$assessment_data[$i]->course->id, $assessment_data[$i]->student->id]) }}">
											<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
									</div>
								@else
									Not uploaded yet
								@endif
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-2 px-4 text-center" colspan="5">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>

	<script>
		const allCoursesWithTeachers = @json(App\Models\Course::where('status', 'active')->with('teachers')->get());
		const alreadyEnrolled = @json($student->enrolled_courses);
		const baseUrl = '{{ url('/') }}';

		function formatDate(dateString) {
			const date = new Date(dateString);
			return new Intl.DateTimeFormat('en-GB', {
				day: 'numeric',
				month: 'short',
				year: 'numeric'
			}).format(date);
		}

		function initializeEditStudentImportInformationPopup(route, whichpopup, uninsertedAttendanceCount){
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(`#${whichpopup}`).find("form").attr("action", route);

			const oldUninsertedAttendanceCount = '{{ old('last_attendance_count') }}';

			$('input[name="last_attendance_count"]').val(oldUninsertedAttendanceCount ? oldUninsertedAttendanceCount : uninsertedAttendanceCount);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		function initializeAssignStudentPopup(route, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$(`#${whichpopup}`).find("form").attr("action", route);

			$('select[name="course"]').empty();
			$('select[name="course"]').append($("<option>").attr('value', '').prop({"selected": true}).text('Select a course'));
			const filtered = allCoursesWithTeachers.filter(item =>
				!alreadyEnrolled.some(course => course.id === item.id)
			);

			filtered.forEach(course => {
				if(course.teachers.length != 0){
					$('select[name="course"]').append($("<option>").attr("value", course.id).text(`${course.course_name} - ${course.level.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}`));
				}
			});

			$('select[name="teacher"]').empty();
			$('select[name="teacher"]').append($("<option>").attr('value', '').prop({"selected": true}).text('Pick a teacher (select course first)'));

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		function initializeEditAssignInfoPopup(route, courseStudent, whichpopup){
			// Fill popups with data
			$(".h-route").val(route);
			$(".h-last-popup").val(whichpopup);
			$('.h-courseStudent').val(JSON.stringify(courseStudent));

			$(`#${whichpopup}`).find("form").attr("action", route);

			const oldMaxCourseSession = '{{ old("max_course_session") }}';
			const oldLearningStatus = '{{ old("learning_status") }}';

			allCoursesWithTeachers.forEach(course => {
				if(course.id == courseStudent.course_id){
					$('select[name="teacher"]').empty();
					for(teacher of course.teachers){
						$('select[name="teacher"]').append($("<option>").attr("value", teacher.id).text(teacher.full_name).prop('selected', (teacher.id == courseStudent.teacher_id? true : false)));
					}
				}
			});

			$('input[name="max_course_session"]').val(oldMaxCourseSession ? oldMaxCourseSession : courseStudent.max_course_session);
			$('select[name="learning_status"]').find(`option[value='${oldLearningStatus ? oldLearningStatus : courseStudent.learning_status}']`).prop('selected', true);

			// Display the popup
			$(`#${whichpopup}`).parent().show();
		}

		$(document).ready(() => {
			$('#show_more_less_button').click(() => {
				$('#more_details').slideToggle(function(){
					if($(this).is(":visible")){
						$('#show_more_less_button').text('Hide Details');
					}
					else {
						$('#show_more_less_button').text('Show Details');
					}
				});
			});

			$('select[name="course"]').on('change', function(){
				allCoursesWithTeachers.forEach(course => {
					if(course.id == $(this).val()){
						$('select[name="teacher"]').empty();
						for(teacher of course.teachers){
							$('select[name="teacher"]').append($("<option>").attr("value", teacher.id).text(teacher.full_name));
						}
					}
				});
			});

			$('.edit-student-import-info-btn').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const uninsertedAttendanceCount = $(this).data('uninserted_attendance_count');
				const whichpopup = "edit-student-import-info-popup";

				initializeEditStudentImportInformationPopup(route, whichpopup, uninsertedAttendanceCount);
			});

			$('#assign-student-btn').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "assign-student-popup";

				initializeAssignStudentPopup(route, whichpopup);
			});

			$('.edit-assign-student-btn').on('click', function() {
				// Retrieve and save selected data
				const route = $(this).data('route');
				const whichpopup = "edit-assign-info-popup";
				const courseStudent = $(this).data('cs');

				initializeEditAssignInfoPopup(route, courseStudent, whichpopup);
			});

			// Unassign student
			$('.unassign-student-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#unassign-student-popup").find('form').attr("action", $(this).data('route'));
				$("#unassign-course-name").text($(this).data('unassign_course_name'));

				// Show the popup
				$("#unassign-student-popup").parent().show();
			});

			// Show student attendance info
			$(".attendance-information-btn").on('click', function(){
				const std_attendances = $(this).data('std_attendances');

				$('#attendance-information-tbody').html('');

				let i = 0;
				for(let satd of std_attendances){
					const colSession = $("<td>").addClass("px-4 py-2").text(i + 1);
					const colDate = $("<td>").addClass("px-4 py-2").text(formatDate(satd.attendance.attendance_date));

					let colAttended = $("<td>").addClass("px-4 py-2");
					if(satd.is_attend == 1){
						colAttended.html('<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">');
					}
					else {
						colAttended.html('<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">');
					}

					const colLearningTime = $("<td>").addClass("px-4 py-2").text((satd.is_attend == 1)? `${satd.start_time.slice(0, 5)}-${satd.end_time.slice(0, 5)}` : 'Absent');
					const colDetails = $("<td>").addClass("px-4 py-2").html(`${satd.activity_progress} [${satd.learning_status}]<br><br>${satd.attendance_detail}`);
					const colUploader = $("<td>").addClass("px-4 py-2").text(satd.attendance.posted_by.full_name);
					const colAction = $('<td>').addClass('px-4 py-2').append(
						$('<div>').addClass('flex gap-1 w-full').append(
							$('<a>').attr('href', `${baseUrl}/admin/student/attendance/${satd.id}/edit`).html(`<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">`)
						)
						.append(
							$('<form>').attr({'method': 'post', 'action': `${baseUrl}/admin/student/attendance/${satd.id}/delete`}).append(
								$('<input>').attr('type', 'hidden').attr('name', '_token').val(document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
							).append(
								$('<button>').attr('type', 'submit').html(`<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">`).on('click', () => {
									return confirm('Are you sure want to delete this attendance data?');
								})
							)
						)
					);

					let rowBg;
					if(i % 2 == 0){
						rowBG = "rgb(237, 241, 247)";
					}
					else {
						rowBG = "white";
					}

					$('#attendance-information-tbody').append($("<tr>").css("background-color", rowBG).append(colSession).append(colDate).append(colAttended).append(colLearningTime).append(colDetails).append(colUploader).append(colAction));

					i++;
				}

				$("#attendance-information-popup").parent().show();
			});

			// Show student assignment info
			$(".assignment-information-btn").on('click', function(){
				const std_assignments = $(this).data('std_assignments');

				$('#assignment-information-tbody').html('');

				let i = 0;
				for(let sasg of std_assignments){
					// console.log(sasg);
					const colNo = $("<td>").addClass("px-4 py-2").text(i + 1);
					const colAsgDate = $("<td>").addClass("px-4 py-2").text(sasg.assignment.title);
					const colAsgDesc = $("<td>").addClass("px-4 py-2").text(sasg.assignment.desc);
					const colUploader = $("<td>").addClass("px-4 py-2").text(sasg.assignment.posted_by.full_name);

					let colStatus = $("<td>").addClass("px-4 py-2");
					let	colLatestSubmission = $("<td>").addClass("px-4 py-2");
					if(sasg.assignment.submissions.length > 0){
						colStatus.html('<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">');
						colLatestSubmission.text(formatDate(sasg.assignment.submissions[0].created_at));
					} else {
						colStatus.html('<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">');
						colLatestSubmission.text('N/A');
					}

					let rowBg;
					if(i % 2 == 0){
						rowBG = "rgb(237, 241, 247)";
					}
					else {
						rowBG = "white";
					}

					$('#assignment-information-tbody').append($("<tr>").css("background-color", rowBG).append(colNo).append(colAsgDate).append(colAsgDesc).append(colUploader).append(colStatus).append(colLatestSubmission));

					i++;
				}

				$("#assignment-information-popup").parent().show();
			});

			// Show assessment popup
			$('.view-assessment-btn').on('click', function(){
				const assessmentData = $(this).data('std_assessment');

				$('#assessment-tbody').html('')
					.append(
						$('<tr>').css('background-color', 'rgb(237, 241, 247)')
							.append(
								$('<td>').addClass('py-2 px-4').text('1')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text('Performance')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text(assessmentData.performance_score.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '))
							)
							.append(
								$('<td>').addClass('py-2 px-4').text(assessmentData.performance_description)
							)
					)
					.append(
						$('<tr>').css('background-color', 'white')
							.append(
								$('<td>').addClass('py-2 px-4').text('2')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text('Technical Skill')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text(assessmentData.technical_skill_score.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '))
							)
							.append(
								$('<td>').addClass('py-2 px-4').text(assessmentData.technical_skill_description)
							)
					)
					.append(
						$('<tr>').css('background-color', 'rgb(237, 241, 247)')
							.append(
								$('<td>').addClass('py-2 px-4').text('3')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text('Aesthetical Skill')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text(assessmentData.aesthetical_skill_score.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '))
							)
							.append(
								$('<td>').addClass('py-2 px-4').text(assessmentData.aesthetical_skill_description)
							)
					)
					.append(
						$('<tr>').css('background-color', 'white')
							.append(
								$('<td>').addClass('py-2 px-4').text('4')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text('Overall')
							)
							.append(
								$('<td>').addClass('py-2 px-4 whitespace-nowrap').text(assessmentData.overall_score.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '))
							)
							.append(
								$('<td>').addClass('py-2 px-4').text(assessmentData.overall_description)
							)
					);

				$('#certificate_access_status').html(assessmentData.certificate_accessible == 1? '<i class="bi bi-check-circle-fill text-green-600"></i> Student can access certificate' : '<i class="bi bi-x-lg text-red"></i> Student has no access to certificate');
				$('#edit-assessment-btn').attr('href', $(this).data('route'));
				$('#preview-certificate-btn').attr('href', $(this).data('view_certificate'));

				$('#assessment-popup').parent().show();
			});

			// Redisplay popup and fill with prev data (for invalidated data)
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_popup = @json(old('h-last-popup'));

				if(old_popup == "edit-student-import-info-popup"){
					const old_route = @json(old('h-route'));
					const old_popup = @json(old('h-last-popup'));
					const old_imported_student = @json(old('h-imported-student'));

					initializeEditStudentImportInformationPopup(old_route, old_popup, JSON.parse(old_imported_student));
				}
				else if(old_popup == "assign-student-popup"){
					const old_route = @json(old('h-route'));
					const old_popup = @json(old('h-last-popup'));

					initializeAssignStudentPopup(old_route, old_popup);
				}
				else if(old_popup == "edit-assign-info-popup") {
					const old_route = @json(old('h-route'));
					const old_popup = @json(old('h-last-popup'));
					const old_courseStudent = @json(old('h-courseStudent'));

					initializeEditAssignInfoPopup(old_route, JSON.parse(old_courseStudent), old_popup);
				}
			@endif
		});
	</script>
@endsection
