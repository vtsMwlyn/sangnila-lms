@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ $course->course_name }}</x-page-title>

		@if(session()->has("successAddTopic"))
			<x-badge-success badge_text="{{ session('successAddTopic') }}"></x-badge-success>
		@elseif(session()->has("successSynchronizeCurriculum"))
			<x-badge-success badge_text="{{ session('successSynchronizeCurriculum') }}"></x-badge-success>
		@elseif(session()->has("successDeleteTopic"))
			<x-badge-warning badge_text="{{ session('successDeleteTopic') }}"></x-badge-warning>
		@endif

		<p class="text-blue-950 font-semibold my-8 text-center">{{ $course->course_description }}</p>

		<div class="w-full">
			<div class="w-full py-6 rounded-xl text-white font-bold text-center" style="background: #000C48;">
				Student List
			</div>

			@if($course_students->count())
				<div class="flex flex-col gap-5 mt-5">
					@foreach ($course_students as $index => $cs)
						@if ($index % 3 == 0)
							@if ($index != 0)
								</div> <!-- Close previous row -->
							@endif
							<div class="flex w-full rounded-xl text-white py-6 items-center" style="background-color: #283785;">
						@endif
							<div class="w-1/3 text-center">{{ $cs->student->full_name }}</div>
					@endforeach
					</div> <!-- Close last row -->
				</div>
			@else
				<div class="text-center p-5 bg-white rounded-xl mt-5 w-full font-semibold">- No students assigned yet -</div>
			@endif
		</div>

		<h2 class="text-xl font-semibold mb-2 text-white mt-10">Course Topic and Materials:</h2>
		<div class="flex justify-between items-stretch w-full mt-5">
			<x-anchor-button class="bg-orange-500"
				{{-- href=" route('teacher.material.upload', $course->id) " }}" --}}
				href="{{ route('teacher.topic.create', $course->id) }}">
				<i class="bi bi-plus-lg"></i> Add new topic
			</x-anchor-button>
			@if($has_curriculum > 0)
				<form action="{{ route('teacher.mycourse.synchronize', $course->id) }}" method="post">
					@csrf
					<x-button class="bg-orange-500" onclick="return confirm('Synchronizing with topics and material in curriculum will erase all of your posted topics and materials. Are your sure want to proceed?')"><i class="bi bi-arrow-repeat"></i> Synchronize with curriculum</x-button>
				</form>
			@endif
		</div>

		<div class="mt-5 mb-5 overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl w-1/3">Topic Title</th>
					<th class="template-heads w-1/3">List of Materials</th>
					{{-- <th class="template-heads">Material Link</th> --}}
					<th class="template-heads rounded-r-xl w-1/3">Action</th>
				</x-slot>

				@if ($topics->count())
					@foreach ($topics as $topic)
						@if($topic->materials->count())
							<tr>
								<td class="template-bodies rounded-l-xl w-1/3">
									<a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $topic->title }}</a>
								</td>

								<td class="template-bodies w-1/3">
									<ul class="h-full w-full overflow-y-auto flex flex-col" style="max-height: 100px;">
										@foreach ($topic->materials as $material)
											<li>{{ $material->title }}</li>
										@endforeach
									</ul>
								</td>
								{{-- <td class="template-bodies"><a href="{{ $material->link }}" class="text-blue-600">{{ $material->link }}</a></td> --}}

								<td class="template-bodies rounded-r-xl w-1/3">
									<div class="flex flex-col w-full justify-center items-center gap-2">
										<x-anchor-button class="bg-orange-500 w-1/2"
											href="{{ route('teacher.topic.edit', [$topic->course->id, $topic->id]) }}">
											<i class="bi bi-pencil-square"></i> Edit Topic
										</x-anchor-button>
										<x-anchor-button class="bg-orange-500 w-1/2"
											href="{{ route('teacher.topic.delete', [$topic->course->id, $topic->id]) }}">
											<i class="bi bi-trash3"></i> Delete Topic
										</x-anchor-button>
									</div>
								</td>

							</tr>
						@else
							<tr>
								<td class="template-bodies rounded-l-xl"><a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $topic->title }}</a></td>
								<td class="template-bodies">- No materials added yet to this topic -</td>
								<td class="template-bodies rounded-r-xl">
									<div class="flex w-full justify-center gap-1">
										<x-anchor-button class="bg-orange-500"
											href="{{ route('teacher.topic.edit', [$topic->course->id, $topic->id]) }}">
											Edit Topic
										</x-anchor-button>
										<x-anchor-button class="bg-orange-500"
											href="{{ route('teacher.topic.delete', [$topic->course->id, $topic->id]) }}">
											Delete Topic
										</x-anchor-button>
									</div>
								</td>
							</tr>
						@endif
					@endforeach
				@else
					<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No topics and materials added yet to this course -</td></tr>
				@endif

			</x-table>
		</div>
	</x-section-container>
@endsection
