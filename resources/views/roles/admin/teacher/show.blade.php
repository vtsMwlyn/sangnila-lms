@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<h2 class="text-2xl font-semibold text-blue-900 mb-4">Teacher's Detail</h2>

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
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
			href="{{ route("admin.teacher.edit", $user->id) }}">Edit</a>
	</div>
	<table class="mb-8 border">
		<tr>
			<td class="border px-5 font-bold">Full name</td>
			<td class="border px-5">{{ $user->full_name }}</td>
		</tr>
		<tr>
			<td class="border px-5 font-bold">Email</td>
			<td class="border px-5">{{ $user->email }}</td>
		</tr>
	</table>
	{{-- <p class="text-gray-700"><span class="font-bold">Full name:</span> {{ $user->full_name }}</p>
	<p class="text-gray-700 mb-8"><span class="font-bold">Email:</span> {{ $user->email }}</p> --}}
	<h2 class="text-xl font-semibold mb-5">Course(s) teached by this user:</h2>
	<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
		href="{{ route("admin.teacher.assign", $user->id) }}">
		Assign to course
	</a>

	<ul class="mb-6 mt-6 flex flex-wrap gap-5">
		@forelse ($user->teached_courses as $course)
			<li class="text-black border rounded-lg bg-gray-300 px-3 py-1">
				{{ $course->course_name }}
				<a
					href="{{ route('admin.teacher.unassign.delete', ['teacher_id' => $user->id, 'course_id' => $course->id]) }}"
					class="">
					<i class="bi bi-x-circle-fill"></i>
				</a>
			</li>
		@empty
			<li class="text-gray-500">No course</li>
		@endforelse
	</ul>
@endsection
