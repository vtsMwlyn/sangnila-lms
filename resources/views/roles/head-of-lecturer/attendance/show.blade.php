@extends('layouts.main-head-of-lecturer')

@section('title')
    <h1>Attendance</h1>
@endsection

@section('popup')
    <x-popup popup_title="Lecturer Attendance Detail" class="w-1/2 flex flex-col items-stretch justify-center" id="lecturer-attendance-detail-popup">
        <div class="overflow-y-auto" style="max-height: 600px;">
            <h5 class="mt-4 text-blue font-semibold text-base">Check In Photo from Lecturer</h5>
            <img alt="N/A" class="w-full mt-2" id="check-in-photo-lecturer" style="object-fit: cover; object-position: center; max-height: 60vh;">

            <h5 class="mt-4 text-blue font-semibold text-base">Description from Lecturer</h5>
            <p id="description-lecturer"></p>

            <h5 class="mt-4 text-blue font-semibold text-base">Photo from Students</h5>
            <div id="check-in-photo-students">
            </div>
        </div>
    </x-popup>
@endsection

@section('content')
    <x-section-container>
        <x-back-button href="{{ route('head-of-lecturer.portfolio.index') }}"></x-back-button>
		<div class="flex w-full justify-between items-center">
			<div class="flex flex-col">
				<x-page-title>Lecturer Attendances</x-page-title>
				<h1 class="font-bold text-lg text-blue mt-1">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>
			</div>
			<form class="flex justify-center" action="{{ route("head-of-lecturer.attendance.show", $course->id) }}">
				<x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search lecturer..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

        <div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

        {{-- <form class="flex w-full justify-center mt-4 items-center gap-3" action="{{ route("head-of-lecturer.portfolio.show", $course->id) }}">
            <x-input type="text" name="student" placeholder="Search by student..." :value="request('student')"/>
            <x-input type="text" name="teacher" placeholder="Search by teacher..." :value="request('teacher')"/>
            <x-button type="submit"><i class="bi bi-search"></i> Filter</x-button>
        </form> --}}

        <div class="w-full overflow-x-auto" style="max-height: 500px;">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Lecturer</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Check In</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Check Out</th>
					{{-- <th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th> --}}
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($self_attendances as $la)
						@php
							$checkInPhotoL = Storage::url("app/public/" . $la->attendance_evidence);

							$courseStudents = App\Models\CourseStudent::where('teacher_id', $la->user->id)
								->where('course_id', $la->course->id)->get();

                            $all_student_attendances = App\Models\SelfAttendance::filter(request(['search']))->whereHas('user', function($query){
                                return $query->where('role_id', 3);
                            })->orderBy('self_attendance_date')->orderBy('user_id')->get();

							$filtered = $all_student_attendances
								->where('self_attendance_date', $la->self_attendance_date)
								->filter(function($ssa) use ($courseStudents) {
									return $courseStudents->contains(function ($cs) use ($ssa) {
										return $cs->course_id === $ssa->course_id && $cs->student_id === $ssa->user_id;
									});
								});

							$sourceStudents = [];
							foreach($filtered as $f){
								$ss = [];

								$ss['src'] = Storage::url("app/public/" . $f->attendance_evidence);
								$ss['validator'] = $f->user->full_name . ' (' . $f->check_in_time . '-' . $f->check_out_time . ')';

								array_push($sourceStudents, $ss);
							}
						@endphp

						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-3 px-4">{{ Carbon\Carbon::parse($la->self_attendance_date)->format('d M Y') }}</td>
							<td class="py-3 px-4">{{ ($la->user->details->gender == 1)? "Mr." : "Ms." }} {{ $la->user->full_name }}</td>
							<td class="py-3 px-4">{{ $la->check_in_time }}</td>
							<td class="py-3 px-4">{{ $la->check_out_time ?? 'N/A' }}</td>
							{{-- <td class="py-3 px-4">{{ $la->validation_status }}</td> --}}
							<td class="py-3 px-4">
								<div class="flex gap-1 w-full">
									<button type="button" class="lecturer-attendance-detail-btn" data-desc="{!! $la->description? nl2br($la->description) : 'N/A' !!}" data-source_teacher="{{ $checkInPhotoL }}" data-student_validations="{{ json_encode($sourceStudents) }}">
										<img src="{{ asset('img/photo.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</button>
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-3 px-4 text-center" colspan="6">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
    </x-section-container>

    <script>
		$(document).ready(() => {
			$('.lecturer-attendance-detail-btn').click(function(){
				$('#check-in-photo-lecturer').attr('src', $(this).data('source_teacher'));
				$('#description-lecturer').html($(this).data('desc'));
				$('#check-in-photo-students').html('');

				const studentValidations = $(this).data('student_validations');

				if(studentValidations.length > 0){
					studentValidations.forEach(sv => {
						$('#check-in-photo-students').append($("<p>").addClass('italic mt-3').text(`From ${sv.validator}`));
						$('#check-in-photo-students').append($("<img>").attr('src', sv.src).addClass('w-full mt-1').css({'object-fit': 'cover', 'object-position': 'center', 'max-height': '60vh'}));
					});
				}
				else {
					$('#check-in-photo-students').append($("<div>").text('N/A'));
				}

				$('#lecturer-attendance-detail-popup').parent().show();
			});

			$('.lecturer-attendance-delete-btn').on('click', function(){
				$('#delete-lecturer-attendance-popup').find('form').attr('action', $(this).data('route'));
				$('#delete-lecturer-attendance-popup').parent().show();
			});
		});
	</script>
@endsection