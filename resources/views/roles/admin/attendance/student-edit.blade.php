@extends('layouts.main-admin')

@section('title')
	<h1>Manage Student</h1>
@endsection

@section('content')
	<x-section-container>
		@php
			$student = $student_attendance->student;
			$course = $student_attendance->attendance->course;
			$cs = App\Models\CourseStudent::where('course_id', $course->id)->where('student_id', $student->id)->first();
		@endphp

		<x-page-title>Edit Student's Attendance</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">{{ $student->full_name }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		<form action="{{ route('admin.attendance.update-student-attendance', $student_attendance->id) }}" method="post" class="my-4">
			@csrf

			<div id="form-area">
				{{-- Class data --}}
				<h1 class="font-bold text-lg text-blue">Class Data</h1>
				<div class="flex gap-5">
					<div class="mt-3 w-full md:w-1/2">
						<x-label class="mb-1">Course Name</x-label>
						<x-input type="text" name="_course_name" class="w-full" value="{{ $course->course_name }}" disabled />
					</div>
					<div class="mt-3 w-full md:w-1/2">
						<x-label class="mb-1">Teacher</x-label>
						<x-input type="text" name="_teacher_name" class="w-full" disabled value="{{ $cs ? $cs->teacher->full_name : 'Unknown' }}"/>
					</div>
				</div>

				{{-- Attendance data --}}
				<h1 class="font-bold text-lg text-blue mt-8">Attendance Data</h1>
				<div class="flex flex-col w-full">
					<div class="w-full flex gap-5 mt-3">
						<div class="w-full md:w-1/2">
							<x-label class="mb-1" for="attendance_date">Attendance Date</x-label>
							<x-input type="date" name="attendance_date" id="attendance_date" class="w-full date-input" value="{{ $student_attendance->attendance->attendance_date }}"/>
						</div>
						<div class="w-full md:w-1/2">
							<x-label class="mb-1" for="is_attend">Attendance Status</x-label>
							<x-select name="is_attend" id="is_attend" class="w-full">
								<option value="1" @if(old('is_attend', $student_attendance->is_attend) == 1) selected @endif>Attended</option>
								<option value="0" @if(old('is_attend', $student_attendance->is_attend) == 0) selected @endif>Absent</option>
							</x-select>
						</div>
						{{-- <div class="flex flex-col w-full md:w-1/3">
							<x-label class="mb-1" for="nth_session">N-th Session</x-label>
							<x-input type="text" name="nth_session" id="nth_session" value="{{ $student_attendance->nth_session }}"/>
						</div> --}}
					</div>

					<div class="flex gap-5 w-full mt-6 attendance-detail-fields" @if($student_attendance->is_attend == 0) style="display: none;" @endif>
						{{-- Start Time --}}
						<div class="flex flex-col w-1/2">
							<x-label for="start_time">Start Time</x-label>
							<x-input type="time" name="start_time" id="start_time" value="{{ old('start_time', $student_attendance->start_time) }}"/>
						</div>

						{{-- End Time --}}
						<div class="flex flex-col w-1/2">
							<x-label for="end_time">End Time</x-label>
							<x-input type="time" name="end_time" id="end_time" value="{{ old('end_time', $student_attendance->end_time) }}"/>
						</div>
					</div>

					<div class="w-full flex gap-5 mt-3 attendance-detail-fields" @if($student_attendance->is_attend == 0) style="display: none;" @endif>
						<div class="w-full md:w-1/2 container-select2">
							<x-label for="activity_progress" class="mb-1">Activity</x-label>
							<x-select name="activity_progress" id="activity_progress" class="w-full select-2">
                                @if($cs)
                                    @foreach ($course->topics->where('user_id', $cs->teacher->id) as $topic)
                                        @foreach ($topic->activities as $activity)
                                            <option value="{{ $activity->title }}" @if(old('activity_progress', $student_attendance->activity_progress == $activity->title)) selected @endif>{{ $activity->title }}</option>
                                        @endforeach
                                    @endforeach
                                @else
                                    @foreach ($course->topics as $topic)
                                        @foreach ($topic->activities as $activity)
                                            <option value="{{ $activity->title }}" @if(old('activity_progress', $student_attendance->activity_progress == $activity->title)) selected @endif>[{{ $activity->topic->uploader->details->gender == 1 ? 'Mr.' : 'Ms.' }} {{ $activity->topic->uploader->full_name }}] {{ $activity->title }}</option>
                                        @endforeach
                                    @endforeach
                                @endif
								<option value="{{ $activity->title }}" @if(old('activity_progress', $student_attendance->activity_progress == 'Other')) selected @endif>Other (Please specify in the attendance detail)</option>
							</x-select>
						</div>

						<div class="w-full md:w-1/2">
							<x-label for="learning_status" class="mb-1">Learning Status</x-label>
							<x-select name="learning_status" id="learning_status" class="w-full">
								<option value="Done" @if(old('learning_status', $student_attendance->learning_status == 'Done')) selected @endif>Done</option>
								<option value="On Progress" @if(old('learning_status', $student_attendance->learning_status == 'On Progress')) selected @endif>On Progress</option>
							</x-select>
						</div>
					</div>

					<div class="w-full mt-8">
						<x-label for="attendance_detail" class="mb-1">Details</x-label>
						<x-textarea rows="4" name="attendance_detail" id="attendance_detail" class="w-full" placeholder="Input details">{{ old('attendance_detail', $student_attendance->attendance_detail) }}</x-textarea>
					</div>
				</div>
			</div>

			<div class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
				<x-button class=" w-1/2 md:w-1/6">Save</x-button>
				<x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$(document).ready(() => {
			$('#is_attend').on('change', function(){
				if($(this).val() == 0){
					$('.attendance-detail-fields').hide();
				}
				else {
					$('.attendance-detail-fields').show();
				}
			});
		});
	</script>
@endsection
