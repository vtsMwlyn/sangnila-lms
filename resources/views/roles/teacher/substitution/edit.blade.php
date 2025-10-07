@extends("layouts.main-teacher")

@section("title")
	<h1>Attendance</h1>
@endsection

@section('content')
    <x-section-container>
		<x-page-title>Edit Substitution Attendance Report</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<x-badge-danger id="emptyDataNotif" badge_text="Please input minimum 1 data to proceed." style="display: none;"></x-badge-danger>

		{{-- @if($allStudents->count() && $course->teachers->count()) --}}
			<form action="{{ route('teacher.attendance.substitution.update', $substitution_attendance->id) }}" method="post" class="my-4" id="store-attendance-form">
				@csrf

				<div id="form-area">
					<div class="flex gap-5">
                        <div class="w-1/2">
                            <x-label for="attendance_date">Attendance Date<span class="text-red">*</span></x-label>
                            <div class="flex gap-3 mt-1 items-start" id="date-inp-cont">
                                <div class="flex flex-col items-start grow">
                                    <x-input type="date" class="date-input w-full" name="attendance_date" id="attendance_date" value="{{ Carbon\Carbon::parse($substitution_attendance->attendance_date)->format('Y-m-d') }}"/>
                                    <p class="text-red font-bold mt-2 hidden" id="error-date"><i class="bi bi-exclamation-circle"></i> Attendance date field is required.</p>
                                </div>
                                <x-button type="button" id="todaybtn" class="mt-1">Today</x-button>
                            </div>
                        </div>
					</div>

					{{-- Learning data --}}
					<h1 class="font-bold text-lg text-blue mt-8">Attendance Data</h1>
					<div class="flex flex-col w-full">
                        <div class="w-full flex gap-5">
                            <div class="mt-3 w-full md:w-1/2 container-select2">
                                <x-label class="mb-1">Student<span class="text-red">*</span></x-label>
                                <x-select type="text" name="_student_name" id="_student_name" class="w-full select-2">
									<option disabled selected>Select a Student</option>
                                    @foreach($students as $student)
										<option value="{{ $student }}" @if($student->id == $substitution_attendance->student_attendances[0]->student_id) selected @endif>{{ $student->full_name }}</option>
									@endforeach
                                </x-select>
                                <input type="hidden" name="student_id" id="student_id" value="{{ $substitution_attendance->student_attendances[0]->student_id }}">
                            </div>
                            <div class="mt-3 w-full md:w-1/2 container-select2">
								<x-label class="mb-1">Course<span class="text-red">*</span></x-label>
								<x-select type="text" name="_course_name" id="_course_name" class="w-full select-2">
									<option disabled selected>Select a Course</option>
									@foreach($courses as $course)
										<option value="{{ $course }}" @if($course->id == $substitution_attendance->course_id) selected @endif>{{ $course->course_name }} - {{ ucwords($course->level) }}</option>
									@endforeach
								</x-select>
								<input type="hidden" name="course_id" id="course_id" value="{{ $substitution_attendance->course_id }}">
							</div>
						</div>

						<div class="flex gap-5 w-full mt-4 attendance-detail-fields">
							{{-- Start Time --}}
							<div class="flex flex-col w-1/2">
								<x-label for="start_time">Start Time<span class="text-red">*</span></x-label>
								<x-input class="start_time" type="time" name="start_time" id="start_time" value="{{ $substitution_attendance->student_attendances[0]->start_time }}"/>
                                <p class="text-red font-bold mt-2 hidden error-time"><i class="bi bi-exclamation-circle"></i> Invalid learning time range.</p>
							</div>

							{{-- End Time --}}
							<div class="flex flex-col w-1/2">
								<x-label for="end_time">End Time<span class="text-red">*</span></x-label>
								<x-input class="end_time" type="time" name="end_time" id="end_time" value="{{ $substitution_attendance->student_attendances[0]->end_time }}"/>
							</div>
						</div>

						<div class="w-full flex gap-5 mt-3 attendance-detail-fields">
							<div class="w-full md:w-1/2 container-select2">
								<x-label for="activity_progress" class="mb-1">Activity<span class="text-red">*</span></x-label>
								<x-select name="activity_progress" id="activity_progress" class="w-full select-2">
									@foreach($substitution_attendance->course->topics as $topic)
										@foreach($topic->activities as $activity)
											<option value="{{ $activity->title }}" @if($activity->title == $substitution_attendance->student_attendances[0]->activity_progress) selected @endif>{{ $activity->title }}</option>
										@endforeach
									@endforeach
									<option value="Other">Other</option>
								</x-select>
							</div>

							<div class="w-full md:w-1/2">
								<x-label for="learning_status" class="mb-1">Learning Status<span class="text-red">*</span></x-label>
								<x-select name="learning_status" id="learning_status" class="w-full">
									<option value="Done" @if('Done' == $substitution_attendance->student_attendances[0]->learning_status) selected @endif>Done</option>
									<option value="On Progress" @if('On Progress' == $substitution_attendance->student_attendances[0]->learning_status) selected @endif>On Progress</option>
								</x-select>
							</div>
						</div>

						<div class="w-full mt-8">
							<x-label for="attendance_details" class="mb-1">Details<span class="text-red">*</span></x-label>
							<x-textarea rows="4" name="attendance_details" id="attendance_details" class="w-full" placeholder="Input details">{{ $substitution_attendance->student_attendances[0]->attendance_detail }}</x-textarea>
                            <p class="text-red font-bold mt-2 hidden" id="error-attendance-detail"><i class="bi bi-exclamation-circle"></i> Please input learning details.</p>
						</div>
					</div>
				</div>

				<div class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
                    <x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
					<x-button class=" w-1/2 md:w-1/6">Save</x-button>
				</div>
			</form>
		{{-- @else
			<div class="rounded-lg py-5 px-10 bg-blue-800">
				<p class="text-white italic">- This course still has no students or students assigned to it, or there are no more students to assign to course -</p>
				<x-button type="button" onclick="history.back()" class=" mt-4">
					Return
				</x-button>
			</div>
		@endif --}}

		<script>
			const courseTopicsAndActivities = @json($courses);

			$(document).ready(() => {
				$('#_course_name').on('change', function(){
					$('#activity_progress').empty();

					const inpCourse = JSON.parse($('#_course_name').val());

					const targettedCourse = courseTopicsAndActivities.find(ctaa => ctaa.id === inpCourse.id);
					console.log(targettedCourse);

					targettedCourse.topics.forEach(topic => {
						topic.activities.forEach(activity => {
							$('#activity_progress').append(
								$('<option>').attr('value', activity.title).text(activity.title)
							)
						});
					});

					$('#activity_progress').append(
						$('<option>').attr('value', 'Other').text('Other (please specify on the details)')
					);

					$('#course_id').val(inpCourse.id);
				});

				$('#_student_name').on('change', function(){
					const student = JSON.parse($(this).val());

					$('#student_id').val(student.id);
				});
			})
		</script>
	</x-section-container>
@endsection