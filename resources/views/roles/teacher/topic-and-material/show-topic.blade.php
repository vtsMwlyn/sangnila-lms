@extends("layouts.main-teacher")

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">
		<a href="{{ route("teacher.mycourse.show", $topic->course->id) }}">{{ $topic->course->course_name }}</a>
	</h1>
	<h1 class="text-2xl font-semibold text-blue-900 mb-5">{{ $topic->title }}</h1>

	@if(session()->has("successUpdateTopic"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateTopic") }}</p>
		</div>
	@elseif(session()->has("successEditTopic"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successEditTopic") }}</p>
		</div>
	@elseif(session()->has("successUploadMaterial"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUploadMaterial") }}</p>
		</div>
	@elseif(session()->has("successEditMaterial"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successEditMaterial") }}</p>
		</div>
	@elseif(session()->has("successDeleteMaterial"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDeleteMaterial") }}</p>
		</div>
	@endif

	<div class="flex space-x-4 mb-6">
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('teacher.topic.edit', [$topic->course->id, $topic->id]) }}">Edit Topic</a>
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route("teacher.topic.delete", [$topic->course->id, $topic->id]) }}">Delete Topic</a>
		{{-- <a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
			href="{{ route('admin.course.assign', $course->id) }}">Assign Teacher</a> --}}
		{{-- <form action="{{ route("teacher.mycourse.delete", [$topic->id, $topic->course->id]) }}" method="post">
			@csrf
			@method("delete")
			<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
				Delete Topic
			</button>
		</form> --}}
	</div>

	<h2 class="text-xl font-semibold mb-2">Material List:</h2>
	<div class="flex space-x-4 mt-2 mb-4">
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('teacher.material.upload', $topic->id) }}">Add Material</a>
	</div>
	<table class="w-full mt-4">
		<thead>
			<th class="border px-3">Material Title</th>
			<th class="border px-3">Link</th>
			<th class="border px-3">Actions</th>
		</thead>
		<tbody>
			@forelse ($topic->course_materials as $material)
				<tr>
					<td class="border px-3">{{ $material->title }}</td>
					<td class="border px-3"><a href="{{ $material->link }}" class="text-blue-700">{{ $material->link }}</a></td>
					<td class="border px-3">
						<div class="flex gap-1">
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route("teacher.material.edit", $material->id) }}">
								Edit Material
							</a>
							<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
								href="{{ route("teacher.material.remove", $material->id) }}">
								Delete Material
							</a>
						</div>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="3" class="border px-3 text-center">- No materials added yet to this topic -</td>
				</tr>
			@endforelse
		</tbody>
	</table>
@endsection
