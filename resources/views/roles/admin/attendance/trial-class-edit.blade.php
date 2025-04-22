@extends("layouts.main-admin")

@section("title")
	<h1>Student Attendance</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title>Edit Trial Class Attendance</x-page-title>
		<h1 class="font-bold text-lg text-blue mt-1">{{ $trial_class_attendance->course->course_name }} - {{ ucwords($trial_class_attendance->course->level) }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('admin.attendance.update-trial-class-attendance', $trial_class_attendance->id) }}" method="post">
			@csrf

            <div class="flex gap-5">
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="attendance_date">Attendance Date<span class="text-red">*</span></x-label>
                    <x-input type="date" name="attendance_date" id="attendance_date" class="w-full date-input" value="{{ old('attendance_date', $trial_class_attendance->attendance_date) }}"/>
                </div>
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="candidate_name">Candidate Name<span class="text-red">*</span></x-label>
                    <x-input type="text" name="candidate_name" id="candidate_name" class="w-full" placeholder="Enter candidate name" value="{{ old('candidate_name', $trial_class_attendance->candidate_name) }}"/>
                </div>
            </div>

            <div class="flex gap-5">
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="start_time">Start Time<span class="text-red">*</span></x-label>
                    <x-input type="time" name="start_time" id="start_time" class="w-full" value="{{ old('start_time', $trial_class_attendance->start_time) }}"/>
                </div>
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="end_time">End Time<span class="text-red">*</span></x-label>
                    <x-input type="time" name="end_time" id="end_time" class="w-full" placeholder="Enter candidate name" value="{{ old('end_time', $trial_class_attendance->end_time) }}"/>
                </div>
            </div>

            <div class="mt-4 flex flex-col w-full">
				<x-label for="attendance_detail">Learning Details<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="attendance_detail" class="w-full mt-1" type="text" name="attendance_detail" placeholder="Enter candidate learning details">{!! old('attendance_detail', nl2br($trial_class_attendance->attendance_detail)) !!}</x-textarea>
			</div>

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3" id="button-area">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class="w-full md:w-40 xl:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
