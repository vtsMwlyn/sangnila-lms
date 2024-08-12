@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ (($user->details->gender == 1)? "Mr. " : "Ms. ") . $user->full_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ __("Teacher's Details") }}</x-page-title>

		@if(session()->has("successAssignToCourse"))
			<x-badge-success badge_text="{{ session('successAssignToCourse') }}"></x-badge-success>
		@elseif(session()->has("successUnassignFromCourse"))
			<x-badge-warning badge_text="{{ session('successUnassignFromCourse') }}"></x-badge-warning>
		@elseif(session()->has("successUpdateTeacherData"))
			<x-badge-success badge_text="{{ session('successUpdateTeacherData') }}"></x-badge-success>
		@endif

		<div class="my-8">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.edit', $user->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<x-horizontal-table>
			<tr>
				<td class="template-hheads w-1/3">Full name</td>
				<td class="template-hbodies">{{ ($user->details->gender == 1)? "Mr." : "Ms." }} {{ $user->full_name }}</td>
			</tr>
			<tr>
				<td class="template-hheads w-1/3">Phone number</td>
				<td class="template-hbodies">@if($user->details->phone_number){{ $user->details->phone_number }}@else{{ __("N/A") }}@endif</td>
			</tr>
			<tr>
				<td class="template-hheads w-1/3">City of Birth</td>
				<td class="template-hbodies">@if($user->details->city_of_birth){{ $user->details->city_of_birth }}@else{{ __("N/A") }}@endif</td>
			</tr>
			<tr>
				<td class="template-hheads w-1/3">Date of Birth</td>
				<td class="template-hbodies">@if($user->details->date_of_birth){{ $user->details->date_of_birth }}@else{{ __("N/A") }}@endif</td>
			</tr>
		</x-horizontal-table>
	</x-section-container>

	<x-section-container class="mt-5">
		<div class="rounded-xl py-5 px-10 mt-5 text-white bg-blue-950">List of Assigned Course</div>
		<div class="mt-10">
			<div>
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.assign', $user->id) }}">
					<i class="bi bi-plus-lg"></i> Assign to Course
				</x-anchor-button>
			</div>
			<div class="flex gap-x-10 flex-wrap mt-5">
				@forelse ($user->teached_courses as $course)
					<div class="text-white border-2 border-white bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-between font-semibold" style="min-width: 200px; min-height: 100px; max-height: 100px;">
						{{ $course->course_name }}
						<a
							href="{{ route('admin.teacher.unassign.delete', ['teacher_id' => $user->id, 'course_id' => $course->id]) }}"
							class="">
							<i class="bi bi-x-circle-fill"></i>
						</a>
					</div>
				@empty
					<div class="flex w-full justify-center rounded-xl p-5 bg-white text-center font-semibold">- No courses assigned yet -</div>
				@endforelse
			</div>
		</div>
	</x-section-container>
@endsection
