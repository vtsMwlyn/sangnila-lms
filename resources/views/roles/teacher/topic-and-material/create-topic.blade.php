@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Add Topic</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ $course->course_name }}</x-page-title>

		@if(session()->has("successAddTopic"))
			<x-badge-success badge_text="{{ session('successAddTopic') }}"></x-badge-success>
		@elseif(session()->has("successDeleteTopic"))
			<x-badge-warning badge_text="{{ session('successDeleteTopic') }}"></x-badge-warning>
		@endif

		<p class="text-blue-950 font-semibold mb-8 text-center">{{ $course->course_description }}</p>

		<div class="w-full">
			@if($course_students->count())
				<div class="w-full py-6 rounded-xl text-white font-bold text-center" style="background: #000C48;">
					Student List
				</div>
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

		<h2 class="text-xl font-semibold mb-2 text-white mt-8">Course Topic and Materials:</h2>

		<div class="mt-5 mb-5 bg-blue-900 rounded-xl">
			<form action="{{ route("teacher.topic.store", $course->id) }}" method="post" class="p-5">
				@csrf
				<!-- Topic Title -->
				<div>
					<x-label for="title" style="color: white" :value="__('Topic Title')" />
					<x-input id="title" class="block mt-1 w-full" type="text" name="title" placeholder="Enter new topic title" autofocus />
				</div>
				<div class="flex w-full justify-center gap-2 mt-8">
					<x-button class="bg-orange-500 w-full md:w-1/6">
						{{ __('Save') }}
					</x-button>
					<x-cancel-button msg="The filled data will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
						Cancel
					</x-cancel-button>
				</div>
			</form>
		</div>

		<div class="mt-8 mb-5 overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Topic Title</th>
					<th class="template-heads">List of Materials</th>
					{{-- <th class="template-heads">Material Link</th> --}}
					<th class="template-heads rounded-r-xl">Action</th>
				</x-slot>

				@if ($course->topics->count())
					@foreach ($course->topics as $topic)
						@if($topic->materials->count())
							@foreach ($topic->materials as $material)
								<tr>
									<td class="template-bodies rounded-l-xl">
										<a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $topic->title }}</a>
									</td>

									<td class="template-bodies">{{ $material->title }}</td>
									{{-- <td class="template-bodies"><a href="{{ $material->link }}" class="text-blue-600">{{ $material->link }}</a></td> --}}

									<td class="template-bodies rounded-r-xl">
										<div class="flex w-full justify-center gap-2">
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
							@endforeach
						@else
							<tr>
								<td class="template-bodies"><a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-700">{{ $topic->title }}</a></td>
								<td class="template-bodies rounded-r-xl">- No materials added yet to this topic -</td>
								<td class="template-bodies">
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
