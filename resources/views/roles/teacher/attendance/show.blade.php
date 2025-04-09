@extends("layouts.main-teacher")

@section("title")
	<h1>Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("popup")
	<x-popup popup_title="Attendance Details" class="w-5/6 flex flex-col items-stretch justify-center overflow-y-auto" id="attendance-details">
		{{-- Popup content --}}
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Session</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Time</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attended</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">In Class Progress</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Notes</th>
					</thead>
					<tbody id="attendance-details-tbody">
					</tbody>
				</table>
			</div>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.attendance.index') }}"></x-back-button>
		<div class="flex flex-col xl:flex-row items-start justify-between mt-3">
			<x-page-title>{{ $course->course_name }} - {{ ucwords($course->level) }}</x-page-title>
		
			<div class="flex gap-2 mt-4 xl:mt-0">
				@if(!$unfinishedSelfAttendance)
					<x-anchor-button href="{{ route('teacher.attendance.check-in', $course->id) }}">
						<i class="bi bi-stopwatch"></i> Check In
					</x-anchor-button>
				@else
					<button disabled class="bg-gray-800 text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold disabled:opacity-50">
						<i class="bi bi-stopwatch"></i> {{ $unfinishedSelfAttendance->check_in_time }}
					</button>
				@endif

				{{-- Already checked in but haven't checked out --}}
				@if($unfinishedSelfAttendance && !$unfinishedSelfAttendance->check_out_time)
					<form action="{{ route('teacher.attendance.check-out.store', $course->id) }}" method="post">
						@csrf
						<x-button onclick="return confirm('Are you sure want to check out now?');">
							<i class="bi bi-stopwatch"></i> Check Out
						</x-button>
					</form>

				{{-- Already checked in and checked out --}}
				@elseif($unfinishedSelfAttendance && $unfinishedSelfAttendance->check_out_time)
					<button disabled class="bg-gray-800 text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold disabled:opacity-50">
						<i class="bi bi-stopwatch"></i> {{ $unfinishedSelfAttendance->check_out_time }}
					</button>

				{{-- Haven't checked in and haven't checked out --}}
				@else
					<button disabled class="bg-gray-800 text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold disabled:opacity-50">
						<i class="bi bi-stopwatch"></i> Check Out
					</button>
				@endif
			</div>
		</div>

		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		{{-- For larger screen --}}
		<div class="flex mt-4 w-full flex-wrap">
			<a href="{{ route('teacher.attendance.show', ['course_id' => $course->id, 'content' => 'attendance reports']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'attendance reports' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				Attendance Reports
			</a>

			<a href="{{ route('teacher.attendance.show', ['course_id' => $course->id ,'content' => 'student attendances']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'student attendances') border-bottom: 4px solid #1db9cf; @endif">
				Student Attendances
			</a>

			<a href="{{ route('teacher.attendance.show', ['course_id' => $course->id ,'content' => 'my attendances']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'my attendances') border-bottom: 4px solid #1db9cf; @endif">
				My Attendances
			</a>
		</div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		@if(request('content') == 'attendance reports' || !request('content'))
			<div class="mb-3 mt-5 flex flex-col-reverse gap-8 xl:gap-0 xl:flex-row w-full justify-between items-center">
				<x-anchor-button href="{{ route('teacher.attendance.select-students', $course->id) }}" class="self-start xl:self-center">
					<i class="bi bi-plus-lg"></i> New Attendance Report
				</x-anchor-button>
				<div class="relative flex flex-col items-start w-full md:w-96 first-letter:0 dropdown-container">
					<button type="button" class="border-slate-400 py-2 px-4 rounded-2xl font-bold text-dark-blue w-full bg-white flex justify-between items-center dropdown-toggler" style="border-width: 3px;">{{ ucwords(request('show', 'My Attendances')) }} <img src="{{ asset('img/dropdown-arrow.svg') }}" class="w-5 h-5" alt="icon"></button>
					<div class="absolute bg-white top-12 w-full rounded-xl flex flex-col hidden overflow-hidden dropdown-menu" style="">
						<a href="{{ route('teacher.attendance.show', ['course_id' => $course->id ,'content' => 'my attendances', 'content' => 'attendance reports', 'show' => 'my students only']) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">My Students Only</div></a>
						<a href="{{ route('teacher.attendance.show', ['course_id' => $course->id ,'content' => 'my attendances', 'content' => 'attendance reports', 'show' => 'all']) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">All</div></a>
					</div>
				</div>
				{{-- <form action="{{ route('teacher.attendance.show', ['course_id' => $course->id ,'content' => 'my attendances']) }}" method="get" class="flex items-center gap-2">
					<x-select class="w-48 md:w-80" name="show">
						<option value="my students only" @if(!request('show') || request('show') == 'my students only') selected @endif>My Students Only</option>
						<option value="all" @if(request('show') == 'all') selected @endif>All Students</option>
					</x-select>
					
					<x-button type="submit">Filter</x-button>
				</form> --}}
			</div>

			<div class="w-full overflow-auto hidden xl:block" style="height: 60vh;">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Students</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Attended</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Absent</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Uploader</th>
						<th class="text-center py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@php
							$sessionCounter = [];  // Stores total session count per student
							$sessionNumberPerAttendance = []; // Stores session number per attendance

							foreach($attendanceData2 as $atddat) {
								$currentSessionNumbers = []; // Session count for this specific attendance

								foreach($atddat->student_attendances as $sa) {
									$studentId = $sa->student->id;

									// Initialize session count if first time seeing this student
									if (!isset($sessionCounter[$studentId])) {
										$sessionCounter[$studentId] = 0;
									}

									// Increase session counter and store the current session number for this attendance
									$sessionCounter[$studentId]++;
									$currentSessionNumbers[$sa->id] = $sessionCounter[$studentId];
								}

								// Store the session numbers for this attendance
								$sessionNumberPerAttendance[$atddat->id] = $currentSessionNumbers;
							}
						@endphp

						@forelse ($attendanceData as $atd)
							<tr class="@if($loop->index % 2 == 0) bg-white @endif">
								<td class="py-3 px-4">{{ Carbon\Carbon::parse($atd->attendance_date)->format('l, d F Y') }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->student_attendances->count() }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->student_attendances->where("is_attend", 1)->count() }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->student_attendances->where("is_attend", 0)->count() }}</td>
								<td class="py-3 px-4 text-center">{{ $atd->posted_by->id == Auth::user()->id? 'Me' : $atd->posted_by->full_name }}</td>
								<td class="py-3 px-4">
									<div class="flex gap-2 justify-center">
										{{-- <x-anchor-button  href="{{ route('teacher.attendance.edit', $atd->id) }}">
											<i class="bi bi-pencil-square"></i>
										</x-anchor-button> --}}
										<button type="button" class=" show-attendance-details-button" data-attendance="{{ $atd }}" data-student_attendances="{{ $atd->student_attendances }}" data-session_number="{{ json_encode($sessionNumberPerAttendance[$atd->id]) }}" title="Check this attendance report details"><img src="{{ asset('img/history.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></button>
									</div>
								</td>
							</tr>
						@empty
							<tr><td class="p-5 bg-white font-semibold text-center" colspan="6">- No attendance data yet -</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>

			{{-- For smaller screen --}}
			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
				@forelse ($attendanceData as $atd)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
						<button class="flex flex-col items-start dropdown-toggler w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<strong class="text-base text-start">{{ Carbon\Carbon::parse($atd->attendance_date)->format('l, d F Y') }}</strong>
								<i class="bi bi-chevron-down"></i>
							</div>
						</button>
						<div class="flex flex-col w-full dropdown-menu" style="display: none;">
							{{ $atd->student_attendances->count() }} Students Reported:
							<ol class="list-disc list-inside">
								<li>{{ $atd->student_attendances->where("is_attend", 1)->count() }} Attended</li>
								<li>{{ $atd->student_attendances->where("is_attend", 0)->count() }} Absent</li>
							</ol>

							<div class="mt-5">
								<i>Posted by</i><br>{{ $atd->posted_by->id == Auth::user()->id? 'Me' : $atd->posted_by->full_name }}
							</div>
	
							<strong class="mt-5">Actions</strong>
							<div class="flex gap-3 items-start my-3">
								<button type="button" class=" show-attendance-details-button" data-attendance="{{ $atd }}" data-student_attendances="{{ $atd->student_attendances }}" data-session_number="{{ json_encode($sessionNumberPerAttendance[$atd->id]) }}"><img src="{{ asset('img/history.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></button>
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		@if(request('content') == 'student attendances')
			<div class="w-full overflow-auto hidden xl:block" style="height: 60vh;">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student Name</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Last Date Attended</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Total Attendance</th>
					</thead>
					<tbody>
						@foreach ($total_student_attendances as $tsa)
							<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
								<td class="py-3 px-4">{{ $tsa['name'] }} <span class="ml-2 @if($tsa['status'] == 'learning') bg-light-blue @elseif($tsa['status'] == 'complete') bg-green-600 @else bg-red @endif text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ ucwords($tsa['status']) }}</span></td>
								<td class="py-3 px-4">{{ $tsa['latest'] ? Carbon\Carbon::parse($tsa['latest']->attendance->attendance_date)->format('l, d M Y') : 'N/A' }}</td>
								<td class="py-3 px-4">{{ $tsa['curr'] }} of {{ $tsa['total'] }} sessions</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
				@forelse ($total_student_attendances as $tsa)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
						<button class="flex flex-col items-start dropdown-toggler w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<strong class="text-base text-start">{{ $tsa['name'] }} <span class="ml-2 @if($tsa['status'] == 'learning') bg-light-blue @elseif($tsa['status'] == 'complete') bg-green-600 @else bg-red @endif text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ ucwords($tsa['status']) }}</span></strong>
								<i class="bi bi-chevron-down"></i>
							</div>
						</button>
						<div class="flex flex-col w-full dropdown-menu" style="display: none;">
							<div class="flex flex-col">
								<span>Last Date Attended:</span>
								<span class="font-semibold">{{ $tsa['latest'] ? Carbon\Carbon::parse($tsa['latest']->attendance->attendance_date)->format('l, d M Y') : 'N/A' }}</span>
								<span class="mt-3">Total Attendance:</span>
								<span class="font-semibold">{{ $tsa['curr'] }} of {{ $tsa['total'] }} sessions</span>
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		@if(request('content') == 'my attendances')
			<div class="w-full overflow-auto hidden xl:block" style="height: 60vh;">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Check In</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Check Out</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Photo</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Description</th>
					</thead>
					<tbody>
						@forelse (App\Models\SelfAttendance::where('user_id', Auth::user()->id)->where('course_id', $course->id)->orderBy('self_attendance_date', 'desc')->get() as $self_atd)
							<tr class="@if($loop->index % 2 == 0) bg-white @endif">
								<td class="py-3 px-4">{{ Carbon\Carbon::parse($self_atd->self_attendance_date)->format('D, d M Y') }}</td>
								<td class="py-3 px-4">{{ $self_atd->check_in_time }}<br>GMT+7</td>
								<td class="py-3 px-4">
									@if($self_atd->check_out_time)
										{{ $self_atd->check_out_time }}<br>GMT+7
									@else
										N/A
									@endif
								</td>
								<td class="py-3 px-4">
									<img src="{{ Storage::url("app/public/" . $self_atd->attendance_evidence) }}" width="200px" alt="photo">
								</td>
								<td class="py-3 px-4">{{ $self_atd->description ?? 'N/A' }}</td>
							</tr>
						@empty
							
						@endforelse
					</tbody>
				</table>
			</div>

			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
				@forelse (App\Models\SelfAttendance::where('user_id', Auth::user()->id)->where('course_id', $course->id)->orderBy('self_attendance_date', 'desc')->get() as $self_atd)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
						<button class="flex flex-col items-start dropdown-toggler w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<strong class="text-base text-start">{{ Carbon\Carbon::parse($self_atd->self_attendance_date)->format('D, d M Y') }}</strong>
								<i class="bi bi-chevron-down"></i>
							</div>
						</button>
						<div class="flex flex-col w-full dropdown-menu" style="display: none;">
							<div class="flex flex-col">
								<div>Check in: {{ $self_atd->check_in_time }} GMT+7</div>
								<div>Check out: {{ $self_atd->check_out_time }} GMT+7</div>
							</div>

							<div class="mt-5">
								<img src="{{ Storage::url("app/public/" . $self_atd->attendance_evidence) }}" width="200px" alt="photo">
								<div class="mt-4">{{ $self_atd->description ?? 'N/A' }}</div>
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		<script>
			$(document).ready(() => {
				$(".show-attendance-details-button").click(function(){
					$("#attendance-details-tbody").empty();

					const attendance = $(this).data('attendance');
					const sessionNumberPerStudent = $(this).data('session_number');
					const student_attendances = $(this).data('student_attendances');

					let i = 0;
					for(let sa of student_attendances){
						const col1 = $('<td>').addClass('py-3 px-4 text-center').text(i + 1);
						const col2 = $('<td>').addClass('py-3 px-4').text(sa.student.full_name);
						const col3 = $('<td>').addClass('py-3 px-4').text(sessionNumberPerStudent[sa.id]);
						const col4 = $('<td>').addClass('py-3 px-4').text(sa.is_attend == 1 ? `${sa.start_time.slice(0, 5)}-${sa.end_time.slice(0, 5)}` : 'Absent');
						const col5 = $('<td>').addClass('py-3 px-4').html(sa.is_attend == 1 ? `<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">` : `<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">`);
						const col6 = $('<td>').addClass('py-3 px-4').text(sa.is_attend == 1 ? `${sa.activity_progress} [${sa.learning_status}]` : 'Absent');
						const col7 = $('<td>').addClass('py-3 px-4').text(sa.attendance_detail);

						let rowBG;
						if(i % 2 == 0){
							rowBG = "rgb(237, 241, 247)";
						}
						else {
							rowBG = "white";
						}

						$("#attendance-details-tbody").append(
							$("<tr>").css("background-color", rowBG).append(col1).append(col2).append(col3).append(col4).append(col5).append(col6).append(col7)
						);
						i++;
					}

					$("#attendance-details").parent().show();
				});
			});
		</script>
	</x-section-container>

@endsection
