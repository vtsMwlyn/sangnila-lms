@extends("layouts.main-admin")

@section("title")
	<h1>Student's Details</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $student->full_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('admin.student.index') }}"></x-back-button>
		<x-page-title style="margin-bottom: 0;">{{ $student->full_name }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(App\Models\ImportedStudent::where("student_id", $student->id)->first())
			<div class="w-full bg-yellow-300 px-5 py-3 my-8 rounded-lg">
				<p class="text-yellow-700 font-semibold" ><i class="bi bi-info-circle"></i> <span class="font-bold">This student is imported.</span> You can unset the imported status when the attendance data of the students are fully inserted by <a href="{{ route("admin.student.normalize.confirmation", [$student->id]) }}" class="hover:underline font-bold hover:font-extrabold">clicking here</a>.</p>
			</div>
		@endif

		@if(session()->has("successAssignToCourse"))
			<x-badge-success badge_text="{{ session('successAssignToCourse') }}"></x-badge-success>
		@elseif(session()->has("successNormalize"))
			<x-badge-success badge_text="{{ session('successNormalize') }}"></x-badge-success>
		@elseif(session()->has("successUnassignFromCourse"))
			<x-badge-warning badge_text="{{ session('successUnassignFromCourse') }}"></x-badge-warning>
		@elseif(session()->has("successUpdateStudentData"))
			<x-badge-success badge_text="{{ session('successUpdateStudentData') }}"></x-badge-success>
		@elseif(session()->has("successUpdateMaxSession"))
			<x-badge-success badge_text="{{ session('successUpdateMaxSession') }}"></x-badge-success>
		@endif

		<!-- Students Information -->
		<div class="w-full flex flex-col gap-y-4">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Student Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ ($student->details->gender == 1)? "Mr." : "Ms." }} {{ $student->full_name }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Phone Number</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $student->details->phone_number ?? 'N/A' }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>City of Birth</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $student->details->city_of_birth ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Date of Birth</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $student->details->date_of_birth ? Carbon\Carbon::parse($teacher->details->date_of_birth)->format('d F Y') : 'N/A' }}</div>
				</div>
			</div>
		</div>

		<div class="w-full flex flex-col gap-y-4 mt-4" id="more_details" style="display: none;">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Parent's Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $student->details->name_parent ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Parent's Phone Number</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $student->details->phone_parent ?? 'N/A' }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>School Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $student->details->school_name ?? 'N/A' }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Education Level</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $student->details->student_level ?? 'N/A' }}</div>
				</div>
			</div>
		</div>

		<div class="w-full flex justify-end items-start mt-5 gap-3">
			<x-button type="button" class="bg-orange-500" id="show_more_less_button">Show More</x-button>
			<x-anchor-button type="button" class="bg-orange-500" href="{{ route('admin.student.edit', $student->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<div class="w-full bg-slate-400 mt-16" style="height: 2px;"></div>
			<div class="w-full flex items-center justify-between">
				<h2 class="my-4 font-extrabold text-xl text-dark-blue">List of Enrolled Courses</h2>
				<x-anchor-button class="bg-orange-500"
						href="{{ route('admin.student.assign.create', $student->id) }}">
					<i class="bi bi-plus-lg"></i> Assign to course
				</x-anchor-button>
			</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-4">
			<div class="flex gap-4">
				@forelse ($student->enrolled_courses as $course)
					<!-- Card -->
					<div class="w-1/3 flex flex-col bg-white rounded-xl p-5">
						@php
							$cs = App\Models\CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->first();
							$teacher = $cs->teacher;
						@endphp

						<h1 class="text-xl font-bold"><a href="{{ route('admin.course.show', $course->id) }}" class="text-blue-950 hover:text-cyan-500">{{ $course->course_name }}</a></h1>
						<div class="w-full bg-slate-400 my-2" style="height: 2px;"></div>

						<div class="flex gap-2 items-center">
							<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
							{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
						</div>
						<div class="flex gap-2 items-center">
							<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
							{{ $cs->max_course_session }} Sessions (Max)
						</div>

						<div class="flex w-full justify-end gap-2 text-sm mt-4">
							<x-button class="text-white" onclick="return confirm('Are you sure want to change the maximum session of this student in this course?');"><i class="bi bi-pencil-square"></i> Edit</x-button>
							<button type="button" data-route="{{ route('admin.student.unassign.delete', ['student_id' => $student->id, 'course_id' => $course->id]) }}" data-unassign_course_name="{{ $course->course_name }}" class="unassign-teacher-btn text-white bg-red flex items-center justify-center px-4 py-2 rounded-xl hover:bg-slate-800 hover:scale-105 active:bg-slate-900 focus:scale-95 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 text-xs" style="box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
								Unassign
							</button>
						</div>
					</div>
				@empty
					<div class="bg-white p-5 w-full rounded-xl font-semibold text-center">- No courses enrolled yet -</div>
				@endforelse
			</div>
		</div>

		<div class="w-full bg-slate-400 mt-16" style="height: 2px;"></div>
		<h2 class="my-4 font-extrabold text-xl text-dark-blue">Attendance and Assignment Progress</h2>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Attendance & Progress</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Assignments</th>
				</thead>
				<tbody>
					@forelse ($student->enrolled_courses as $i => $ec)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4 w-1/4">
								{{ $ec->course_name }}
							</td>
							<td class="py-2 px-4">
								{{ $current_progress[$i] }}/{{ $full_progress[$i] }} done
								<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.atd-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
									<i class="bi bi-eye"></i>
								</x-anchor-button>
							</td>
							<td class="py-2 px-4">
								{{ $done_assignment[$i] }}/{{ $assignment_if_full[$i] }} done
								<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.asg-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
									<i class="bi bi-eye"></i>
								</x-anchor-button>
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
		{{-- <div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Course</th>
					<th class="template-heads">Attendance & Progress</th>
					<th class="template-heads rounded-r-xl">Assignments</th>
				</x-slot>
				@if($student->enrolled_courses->count())
					@for($i = 0; $i < $student->enrolled_courses->count(); $i++)
						<tr>
							<td class="template-bodies rounded-l-xl"><a href="{{ route('admin.course.show', $student->enrolled_courses[$i]->id) }}" class="font-bold text-blue-200 hover:underline hover:text-blue-400">{{ $student->enrolled_courses[$i]->course_name }}</a></td>
							<td class="template-bodies">
								<div class="flex w-full items-center justify-center gap-3">
									<span>{{ $current_progress[$i] }}/{{ $full_progress[$i] }} done</span>
									<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.atd-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
										Details
									</x-anchor-button>
								</div>
							</td>
							<td class="template-bodies rounded-r-xl">
								<div class="flex w-full items-center justify-center gap-3">
									<span>{{ $done_assignment[$i] }}/{{ $assignment_if_full[$i] }} done</span>
									<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.asg-details', [$student->id, $student->enrolled_courses[$i]->id]) }}">
										Details
									</x-anchor-button>
								</div>
							</td>
						</tr>
					@endfor
				@else
					<tr><td colspan="3" class="text-center font-semibold p-5 bg-white rounded-xl">- Student isn't assigned to any courses yet -</td></tr>
				@endif
			</x-table>
		</div> --}}

	</x-section-container>

	<script>
		$(document).ready(() => {
			$('#show_more_less_button').click(() => {
				$('#more_details').slideToggle();
			});
		});
	</script>
@endsection
