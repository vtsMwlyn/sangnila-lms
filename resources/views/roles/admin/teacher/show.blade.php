@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Teacher's Details") }}</x-page-title>

	@if(session()->has("successAssignToCourse"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successAssignToCourse") }}</p>
		</div>
	@elseif(session()->has("successUnassignFromCourse"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successUnassignFromCourse") }}</p>
		</div>
	@elseif(session()->has("successUpdateTeacherData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateTeacherData") }}</p>
		</div>
	@endif

	<div class="p-10 bg-indigo-200 rounded-3xl">
		<div class="mb-5">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.edit', $user->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		</div>

		<table class="w-full" style="border-collapse: separate; border-spacing: 15px 10px;">
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Full name</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($user->full_name){{ $user->full_name }}@else{{ __("N/A") }}@endif</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Phone number</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($user->details->phone_number){{ $user->details->phone_number }}@else{{ __("N/A") }}@endif</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">City of Birth</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($user->details->city_of_birth){{ $user->details->city_of_birth }}@else{{ __("N/A") }}@endif</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold w-1/3">Date of Birth</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">@if($user->details->date_of_birth){{ $user->details->date_of_birth }}@else{{ __("N/A") }}@endif</td>
			</tr>
		</table>

		<x-page-title class="mt-5" style="text-align: left;">Courses Teached</x-page-title>
		<div class="px-10 py-5 border rounded-xl bg-blue-900 mt-3">
			<div class="py-5">
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.assign', $user->id) }}">
					<i class="bi bi-plus-lg"></i> Assign to Course
				</x-anchor-button>
			</div>
			<hr>
			<div class="flex gap-x-10 overflow-x-auto bg-blue-900 rounded-b-xl mt-5">
				@forelse ($user->teached_courses as $course)
					<div class="text-white border bg-orange-500 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center gap-3 font-semibold" style="min-width: 200px; min-height: 50px; max-height: 50px;">
						{{ $course->course_name }}
						<a
							href="{{ route('admin.teacher.unassign.delete', ['teacher_id' => $user->id, 'course_id' => $course->id]) }}"
							class="">
							<i class="bi bi-x-circle-fill"></i>
						</a>
					</div>
				@empty
					<span class="text-white">No courses teached</span>
				@endforelse
			</div>
		</div>
	</div>
@endsection
