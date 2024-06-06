@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>

	@if(session()->has("successAddTopic"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successAddTopic") }}</p>
		</div>
	@elseif(session()->has("successDeleteTopic"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDeleteTopic") }}</p>
		</div>
	@endif

	<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>
	<h2 class="text-xl font-semibold mb-2">Student List:</h2>
	<ul class="list-disc pl-6 mb-6">
		@forelse ($course_students as $cs)
			@if($cs->student->status == "enabled")
				<li class="text-black">{{ $cs->student->full_name }}</li>
			@endif
		@empty
			<li class="text-gray-500">No student enrolled in this course</li>
		@endforelse
	</ul>
	<h2 class="text-xl font-semibold mb-2">Course Topic and Materials:</h2>
	<div class="relative flex items-center py-2 grou">
		<span class="mr-2">
			<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
				{{-- href=" route('teacher.material.upload', $course->id) " }}" --}}
				href="{{ route("teacher.topic.create", $course->id) }}">
				Add new topic
			</a>
		</span>
	</div>
	<div class="mt-5 mb-5 overflow-x-auto">
		<table class="w-full">
			<thead>
				<th class="border px-3">Topic Title</th>
				<th class="border px-3">List of Materials</th>
				{{-- <th class="border px-3">Material Link</th> --}}
				<th class="border px-3">Action</th>
			</thead>
			<tbody>
				@if ($course->course_topics->count())
					@foreach ($course->course_topics as $topic)
						@if($topic->course_materials->count())
							@foreach ($topic->course_materials as $material)
								<tr>
									@if($loop->iteration == 1)
										<td class="border px-3" rowspan={{ $topic->course_materials->count() }}>
											<a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-700">{{ $topic->title }}</a>
										</td>
									@endif
									<td class="border px-3">{{ $material->title }}</td>
									{{-- <td class="border px-3"><a href="{{ $material->link }}" class="text-blue-600">{{ $material->link }}</a></td> --}}
									@if($loop->iteration == 1)
										<td class="border px-3" rowspan={{ $topic->course_materials->count() }}>
											<div class="flex gap-1">
												<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
													href="{{ route("teacher.topic.edit", [$topic->course->id, $topic->id]) }}">
													Edit Topic
												</a>
												<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
													href="{{ route("teacher.topic.delete", [$topic->course->id, $topic->id]) }}">
													Delete Topic
												</a>
											</div>
										</td>
									@endif
								</tr>
							@endforeach
						@else
							<tr>
								<td class="border px-3"><a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-700">{{ $topic->title }}</a></td>
								<td class="border px-3 text-center">- No materials added yet to this topic -</td>
								<td class="border px-3">
									<div class="flex gap-1">
										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route('teacher.topic.edit', [$topic->course->id, $topic->id]) }}">
											Edit Topic
										</a>
										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("teacher.topic.delete", [$topic->course->id, $topic->id]) }}">
											Delete Topic
										</a>
									</div>
								</td>
							</tr>
						@endif
					@endforeach
				@else
					<tr><td colspan="4" class="border px-3 text-center">- No topics added yet to this course -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
