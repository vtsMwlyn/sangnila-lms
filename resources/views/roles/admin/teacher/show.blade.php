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

	<div class="h-fit mb-5">
		<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.edit', $user->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
	</div>

	<div class="p-5 bg-indigo-200 rounded-3xl overflow-x-auto">
		<table class="w-full" style="border-collapse: separate; border-spacing: 15px 10px;">
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold">Full name</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">{{ $user->full_name }}</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold">Email</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">{{ $user->email }}</td>
			</tr>
		</table>
	</div>

	<div class="flex flex-col items-stretch mt-10">
		<div class="rounded-xl py-2 px-10 flex justify-between items-center text-white bg-blue-900">
			<span>Courses Assigned</span>
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.teacher.assign', $user->id) }}">
				Assign to Course
			</x-anchor-button>
		</div>

		<div class="flex gap-x-10 overflow-x-auto bg-indigo-200 px-10 py-5 rounded-xl mt-3">
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
				<li class="text-gray-500">No course</li>
			@endforelse
		</div>
	</div>
@endsection
