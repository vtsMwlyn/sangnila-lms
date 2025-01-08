@extends("layouts.main-admin")

@section("title")
	<h1>Lecturer Attendances</h1>
@endsection

@section('popup')
	<x-popup popup_title="Lecturer Attendance Detail" class="w-1/2 flex flex-col items-stretch justify-center" id="lecturer-attendance-detail-popup">
		<div class="overflow-y-auto" style="max-height: 600px;">
			<h5 class="mt-4 text-blue font-semibold text-base">Check In Photo from Lecturer</h5>
			<img alt="N/A" class="w-full mt-2" id="check-in-photo-lecturer" style="object-fit: cover; object-position: center; max-height: 60vh;">

			<h5 class="mt-4 text-blue font-semibold text-base">Validation Photo from Students</h5>
			<div id="check-in-photo-students">
			</div>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of Lecturer Attendances") }}</x-page-title>

		<div class="flex items-center justify-center mt-6">
			{{-- <div class="w-1/4">
				<x-anchor-button  href="{{ route('admin.course.create') }}"><i class="bi bi-plus-lg"></i> Add New Course</x-anchor-button>
			</div> --}}

			<form class="flex w-1/2 justify-center" action="{{ route("admin.lecturer-attendance.index") }}">
				<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Lecturer</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Check In</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Check Out</th>
					{{-- <th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th> --}}
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($all_lecturer_attendances as $la)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4">{{ Carbon\Carbon::parse($la->self_attendance_date)->format('d M Y') }}</td>
							<td class="py-2 px-4">{{ ($la->teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $la->teacher->full_name }}</td>
							<td class="py-2 px-4">{{ $la->course->course_name }}</td>
							<td class="py-2 px-4">{{ $la->check_in_time }}</td>
							<td class="py-2 px-4">{{ $la->check_out_time ?? 'N/A' }}</td>
							{{-- <td class="py-2 px-4">{{ $la->validation_status }}</td> --}}
							<td class="py-2 px-4">
								@php
									$checkInPhoto = Storage::url('app/public/' . $la->attendance_evidence);

									$validationFromStudentData = App\Models\LecturerValidation::where('course_id', $la->course->id)->where('teacher_id', $la->teacher->id)->get();
									// $filtered = $validationFromStudentData->filter(function ($item) {
									// 	$hour_submitted = Carbon\Carbon::parse($item->created_at)->format('H:i:s');
									// 	return $hour_submitted >= $la->check_in_time && $hour_submitted <= $la->check_out_time;
									// });

									$sourceStudents = [];

									if($validationFromStudentData->count()){
										foreach($validationFromStudentData as $vfsd){
											$errey = [];
											$errey['src'] = Storage::url('app/public/' . $vfsd->evidence);
											$errey['validator'] = $vfsd->validator->full_name;
											array_push($sourceStudents, $errey);
										}
									}
								@endphp

								<div class="flex gap-1 w-full">
									<x-button type="button" class="lecturer-attendance-detail-btn" data-source_teacher="{{ $checkInPhoto }}" data-student_validations="{{ json_encode($sourceStudents) }}"><i class="bi bi-image"></i></x-button>
								</div>
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
		$(document).ready(() => {
			$('.lecturer-attendance-detail-btn').click(function(){
				$('#check-in-photo-lecturer').attr('src', $(this).data('source_teacher'));
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
		});
	</script>
@endsection
