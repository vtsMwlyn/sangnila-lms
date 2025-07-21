@extends("layouts.main-admin")

@section("title")
	<h1>Manage Attendances</h1>
@endsection

@section('popup')
    {{-- Lecturer attendance detail --}}
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

    {{-- Student attendance detail --}}
    <x-popup popup_title="Student Attendance Detail" class="w-1/2 flex flex-col items-stretch justify-center" id="student-attendance-detail-popup">
		<div class="overflow-y-auto" style="max-height: 600px;">
			<h5 class="mt-4 text-blue font-semibold text-base">Activity Learned</h5>
			<p id="activity-learned"></p>

			<h5 class="mt-4 text-blue font-semibold text-base">Description from Lecturer</h5>
			<p id="attendance-detail"></p>
		</div>
	</x-popup>

    {{-- Delete lecturer attendance --}}
	<x-confirmation method="delete" popup_title="Delete Lecturer Attendance" id="delete-lecturer-attendance-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> the lecturer attendance data <span class="font-bold remark"></span>?
	</x-confirmation>

    {{-- Delete student attendance --}}
	<x-confirmation method="delete" popup_title="Delete Student Attendance" id="delete-student-attendance-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> the student attendance data <span class="font-bold remark"></span>?
	</x-confirmation>

    {{-- Delete trial class attendance --}}
	<x-confirmation method="delete" popup_title="Delete Trial Class Attendance" id="delete-trial-class-attendance-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> the trial class attendance data <span class="font-bold remark"></span>?
	</x-confirmation>

    {{-- Input student attendance --}}
    <x-popup popup_title="Input Student Attendance" class="w-1/2 flex flex-col items-stretch justify-center" id="input-student-attendance-popup">
		<form action="#" id="pick-student-form">
            <div class="select2-container w-full flex flex-col mt-4">
                <p>Please pick a student</p>
                <x-select class="w-full mt-1 select-2">
                    @foreach(App\Models\User::where('role_id', 3)->where('status', 'enabled')->orderBy('full_name')->get() as $student)
                        @php
                            $still_learning = false;
                            foreach($student->course_students as $cs){
                                if($cs->learning_status == 'learning'){
                                    $still_learning = true;
                                    break;
                                }
                            }
                        @endphp
                        
                        @if($still_learning)
                            <option value="{{ route('admin.attendance.input-student-attendance', $student->id) }}">{{ $student->full_name }}</option>
                        @endif
                    @endforeach
                </x-select>
            </div>

            <div class="flex gap-3 justify-center mt-6 mb-3">
                <x-button class="w-full md:w-1/4">
                    Continue
                </x-button>
            </div>
		</form>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
        <div class="w-full flex justify-between items-center">
            <x-page-title>Attendance List</x-page-title>

            @if(!request('content') || request('content') == 'teacher')
                <div class="flex items-center justify-center">
                    <form class="flex w-full justify-center items-center gap-3" action="{{ route("admin.attendance.index") }}">
                        <input type="hidden" name="content" value="{{ request('content') }}">
                        <x-input type="text" name="teacher" placeholder="Search by teacher..." :value="request('teacher')"/>
                        <x-input type="text" name="course" placeholder="Search by course..." :value="request('course')"/>
                        <x-button type="submit"><i class="bi bi-search"></i> Filter</x-button>
                    </form>
                </div>
            @elseif(request('content') == 'student')
                <div class="flex items-center justify-center">
                    <form class="flex w-full justify-center items-center gap-3" action="{{ route("admin.attendance.index") }}">
                        <input type="hidden" name="content" value="{{ request('content') }}">
                        <x-input type="text" name="student" placeholder="Search by student..." :value="request('student')"/>
                        <x-input type="text" name="course" placeholder="Search by course..." :value="request('course')"/>
                        <x-input type="text" name="uploader" placeholder="Search by uploader..." :value="request('uploader')"/>
                        <x-button type="submit"><i class="bi bi-search"></i> Filter</x-button>
                    </form>
                </div>
            @elseif(request('content') == 'trial class')
                <div class="flex items-center justify-center">
                    <form class="flex w-full justify-center items-center gap-3" action="{{ route("admin.attendance.index") }}">
                        <input type="hidden" name="content" value="{{ request('content') }}">
                        <x-input type="text" name="candidate" placeholder="Search by candidate..." :value="request('candidate')"/>
                        <x-input type="text" name="course" placeholder="Search by course..." :value="request('course')"/>
                        <x-button type="submit"><i class="bi bi-search"></i> Filter</x-button>
                    </form>
                </div>
            @endif
        </div>
        <div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

        <div class="flex my-4 w-full flex-wrap">
			<a href="{{ route('admin.attendance.index', ['content' => 'teacher']) }}"
				class="py-2 w-1/2 xl:w-1/5 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'teacher' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				Teacher Attendances
			</a>

			<a href="{{ route('admin.attendance.index', ['content' => 'student']) }}"
				class="py-2 w-1/2 xl:w-1/5 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'student') border-bottom: 4px solid #1db9cf; @endif">
				Student Attendances
			</a>

			<a href="{{ route('admin.attendance.index', ['content' => 'trial class']) }}"
				class="py-2 w-1/2 xl:w-1/5 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'trial class') border-bottom: 4px solid #1db9cf; @endif">
				Trial Class Attendances
			</a>
		</div>

        @if(!request('content') || request('content') == 'teacher')
            <div class="w-full overflow-x-auto" style="max-height: 500px;">
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
                        @forelse ($all_lecturer_self_attendances as $la)
                            @php
                                $checkInPhotoL = Storage::url( $la->attendance_evidence);

                                $courseStudents = App\Models\CourseStudent::where('teacher_id', $la->user->id)
                                    ->where('course_id', $la->course->id)->get();

                                $filtered = $all_student_self_attendances
                                    ->where('self_attendance_date', $la->self_attendance_date)
                                    ->filter(function($ssa) use ($courseStudents) {
                                        return $courseStudents->contains(function ($cs) use ($ssa) {
                                            return $cs->course_id === $ssa->course_id && $cs->student_id === $ssa->user_id;
                                        });
                                    });

                                $sourceStudents = [];
                                foreach($filtered as $f){
                                    $ss = [];

                                    $ss['src'] = Storage::url( $f->attendance_evidence);
                                    $ss['validator'] = $f->user->full_name . ' (' . $f->check_in_time . '-' . $f->check_out_time . ')';

                                    array_push($sourceStudents, $ss);
                                }
                            @endphp

                            <tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
                                <td class="py-3 px-4">{{ Carbon\Carbon::parse($la->self_attendance_date)->format('d M Y') }}</td>
                                <td class="py-3 px-4">{{ ($la->user->details->gender == 1)? "Mr." : "Ms." }} {{ $la->user->full_name }}</td>
                                <td class="py-3 px-4">{{ $la->course->course_name }} - {{ ucwords($la->course->level) }}</td>
                                <td class="py-3 px-4">{{ $la->check_in_time }}</td>
                                <td class="py-3 px-4">{{ $la->check_out_time ?? 'N/A' }}</td>
                                {{-- <td class="py-3 px-4">{{ $la->validation_status }}</td> --}}
                                <td class="py-3 px-4">
                                    <div class="flex gap-1 w-full">
                                        <button type="button" class="lecturer-attendance-detail-btn" data-desc="{!! $la->description? nl2br($la->description) : 'N/A' !!}" data-source_teacher="{{ $checkInPhotoL }}" data-student_validations="{{ json_encode($sourceStudents) }}">
                                            <img src="{{ asset('img/photo.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
                                        </button>
                                        <button type="button" class="lecturer-attendance-delete-btn" data-remark="{{ _((($la->user->details->gender == 1)? 'Mr. ' : 'Ms. ') . $la->user->full_name . ' in ' . $la->course->course_name . ' - ' . ucwords($la->course->level) . ' (' . Carbon\Carbon::parse($la->self_attendance_date)->format('d/m/Y') . ')') }}" data-route="{{ route('admin.attendance.destroy', $la->id) }}">
                                            <img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
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

            <div class="mt-4">
                {{ $all_lecturer_self_attendances->links() }}
            </div>
        @endif

        @if(request('content') == 'student')
            <div class="mt-2">
                <x-button type="button" id="input-attendance-btn"><i class="bi bi-database-add"></i> Input Attendances Data</x-button>
            </div>

            <div class="w-full overflow-x-auto mt-4" style="max-height: 500px;">
                <table class="w-full">
                    <thead>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Session</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Time</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Uploader</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
                    </thead>
                    <tbody>
                        @forelse ($all_student_attendances as $sa)
                            <tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
                                <td class="py-3 px-4">{{ Carbon\Carbon::parse($sa->attendance->attendance_date)->format('d M Y') }}</td>
                                <td class="py-3 px-4">{{ $sa->attendance->course->course_name }} - {{ ucwords($sa->attendance->course->level) }}</td>
                                <td class="py-3 px-4">
                                    <a class="text-blue-600 hover:underline font-bold" href="{{ route('admin.student.show', $sa->student->id) }}">
                                        {{ $sa->student->full_name }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $cs = App\Models\CourseStudent::where('student_id', $sa->student_id)->where('course_id', $sa->attendance->course_id)->first();

                                        if($cs){
                                            echo $session_counter[$sa->id] . ' of ' . $cs->max_course_session;
                                        } else {
                                            echo 'N/A';
                                        }
                                    @endphp
                                </td>
                                <td class="py-3 px-4">{{ Carbon\Carbon::parse($sa->start_time)->format('H:i') }}-{{ Carbon\Carbon::parse($sa->end_time)->format('H:i') }} GMT+7</td>
                                <td class="py-3 px-4">{{ $sa->attendance->posted_by->full_name }}</td>
                                <td class="py-3 px-4">
                                    <div class="w-full flex gap-1">
                                        <button type="button" class="view-student-attendance-btn" data-sa="{{ $sa }}">
                                            <img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
                                        </button>
                                        <a href="{{ route('admin.attendance.edit-student-attendance', $sa->id) }}" title="Edit this student attendance data">
                                            <img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
                                        </a>
                                        <button type="button" class="delete-student-attendance-btn" data-remark="{{ _($sa->student->full_name . ' in ' . $sa->attendance->course->course_name . ' - ' . ucwords($sa->attendance->course->level) . ' (' . Carbon\Carbon::parse($sa->attendance->attendance_date)->format('d/m/Y') . ')') }}" data-route="{{ route('admin.attendance.destroy-student-attendance', $sa->id) }}">
                                            <img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
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

            <div class="mt-4">
                {{ $all_student_attendances->links() }}
            </div>
        @endif

        @if(request('content') == 'trial class')
            <div class="w-full overflow-x-auto" style="max-height: 500px;">
                <table class="w-full">
                    <thead>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Time</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
                    </thead>
                    <tbody>
                        @forelse ($all_trial_class_attendances as $tca)
                            <tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
                                <td class="py-3 px-4">{{ Carbon\Carbon::parse($tca->attendance_date)->format('d M Y') }}</td>
                                <td class="py-3 px-4">{{ $tca->course->course_name }} - {{ ucwords($tca->course->level) }}</td>
                                <td class="py-3 px-4">{{ $tca->candidate_name }}</td>
                                <td class="py-3 px-4">{{ Carbon\Carbon::parse($tca->start_time)->format('H:i') }}-{{ Carbon\Carbon::parse($tca->end_time)->format('H:i') }} GMT+7</td>
                                <td class="py-3 px-4">{{ $tca->attendance_detail }}</td>
                                <td class="py-3 px-4">
                                    <div class="w-full flex gap-1">
                                        <a href="{{ route('admin.attendance.edit-trial-class-attendance', $tca->id) }}" title="Edit this student attendance data">
                                            <img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
                                        </a>
                                        <button type="button" class="delete-trial-class-attendance-btn" data-remark="{{ _($tca->candidate_name . ' in ' . $tca->course->course_name . ' - ' . ucwords($tca->course->level) . ' (' . Carbon\Carbon::parse($tca->attendance_date)->format('d/m/Y') . ')') }}" data-route="{{ route('admin.attendance.destroy-trial-class-attendance', $tca->id) }}">
                                            <img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
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

            <div class="mt-4">
                {{ $all_trial_class_attendances->links() }}
            </div>
        @endif
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

            $('.view-student-attendance-btn').on('click', function(){
                const studentAttendance = $(this).data('sa');
                $('#student-attendance-detail-popup').find('#activity-learned').html(`${studentAttendance.activity_progress} <span class="ml-2 ${studentAttendance.learning_status == 'Done'? 'bg-green-600' : 'bg-light-blue'} text-white text-base rounded-lg px-2 py-0.5 font-normal">${studentAttendance.learning_status}</span>`);
                $('#student-attendance-detail-popup').find('#attendance-detail').text(studentAttendance.attendance_detail);
                $('#student-attendance-detail-popup').parent().show();
            });

			$('.lecturer-attendance-delete-btn').on('click', function(){
				$('#delete-lecturer-attendance-popup').find('form').attr('action', $(this).data('route'));
                $('#delete-lecturer-attendance-popup').find('.remark').text($(this).data('remark'));
				$('#delete-lecturer-attendance-popup').parent().show();
			});

            $('.delete-student-attendance-btn').on('click', function(){
                $('#delete-student-attendance-popup').find('form').attr('action', $(this).data('route'));
                $('#delete-student-attendance-popup').find('.remark').text($(this).data('remark'));
				$('#delete-student-attendance-popup').parent().show();
            });

            $('.delete-trial-class-attendance-btn').on('click', function(){
                $('#delete-trial-class-attendance-popup').find('form').attr('action', $(this).data('route'));
                $('#delete-trial-class-attendance-popup').find('.remark').text($(this).data('remark'));
				$('#delete-trial-class-attendance-popup').parent().show();
            });

            $('#input-attendance-btn').on('click', function(){
                $('#input-student-attendance-popup').parent().show();
            });

            $('#pick-student-form').on('submit', function (e) {
                e.preventDefault(); // Stop the form from submitting normally

                const selectedUrl = $(this).find('select').val(); // Get selected option's value

                if (selectedUrl) {
                    window.location.href = selectedUrl; // Redirect to the selected URL
                }
            });
		});
	</script>
@endsection
