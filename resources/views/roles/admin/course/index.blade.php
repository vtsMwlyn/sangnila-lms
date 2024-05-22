@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<div class="flex w-full items-center justify-between mb-4">
		<h1 class="text-3xl font-semibold text-blue-900 mb-4">List of Available Courses</h1>

		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('sysadmin.course.create') }}">Add New Course</a>
	</div>

	@if(session()->has("successCreateNewCourse"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successCreateNewCourse") }}</p>
		</div>
	@elseif(session()->has("successDeleteCourse"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDeleteCourse") }}</p>
		</div>
	@endif

	@if ($courses->isNotEmpty())
	<div class="overflow-x-auto rounded-md">
		<table class="min-w-full bg-white border-collapse sm:table">
			<thead>
				<tr>
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Course Name</th>
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Description</th>
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Visibility</th>
					<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($courses as $course)
				<tr>
					<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
						<a href="{{ route('admin.course.show', ['course_id' => $course->id]) }}"
							class="text-blue-600 hover:text-blue-800 font-semibold hover:underline block">
							{{ $course->course_name }}
						</a>
					</td>

					<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
						{{ $course->course_description }}
					</td>

					<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4 text-center">
						{{ $course->visibility }}
					</td>
					<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4 text-center">
						<div class="flex gap-1 w-full">
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route('admin.course.show', ['course_id' => $course->id]) }}">
								View
							</a>
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route('admin.course.edit', $course->id) }}">
								Edit
							</a>
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route('admin.course.delete', $course->id) }}">
								Delete
							</a>
						</div>
					</td>

				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@else
		<div class="text-blue-900">N/A</div>
	@endif
@endsection
