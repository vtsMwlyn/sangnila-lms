@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ $course->course_name }}</x-page-title>

		@if(session()->has("successAddTopic"))
			<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
				<p class="text-green-900">{{ session("successAddTopic") }}</p>
			</div>
		@elseif(session()->has("successDeleteTopic"))
			<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
				<p class="text-yellow-600" >{{ session("successDeleteTopic") }}</p>
			</div>
		@endif

		<p class="text-blue-950 font-semibold mb-8">{{ $course->course_description }}</p>

		<div class="flex flex-col">
			<div class="border-t border-l border-r border-blue-950 bg-slate-300 rounded-t-xl w-full">
				<h2 class="text-xl text-center font-semibold my-2 text-blue-950">Student List</h2>
			</div>
			<div class="border border-blue-950 bg-slate-300 rounded-b-xl w-full">
				<div class="flex justify-evenly w-full">
					@php
						$number_of_data_separation = 3;
						$n = ceil($course_students->count() / $number_of_data_separation);
					@endphp
					@for($i = 0; $i < $number_of_data_separation; $i++)
						<div class="my-5">
							<ul class="list-disc pl-6">
								@if($i < $number_of_data_separation - 1)
									@for($j = $n * $i; $j < $n * ($i + 1); $j++)
										<li class="text-blue-950">{{ $course_students[$j]->student->full_name }} @if($course_students[$j]->student->status == "disabled")<span class="text-red-500">(Disabled)</span>@endif</li>
									@endfor
								@else
									@for($j = $n * $i; $j < $course_students->count(); $j++)
										<li class="text-blue-950">{{ $course_students[$j]->student->full_name }} @if($course_students[$j]->student->status == "disabled")<span class="text-red-500">(Disabled)</span>@endif</li>
									@endfor
								@endif
							</ul>
						</div>
					@endfor
				</div>
			</div>
		</div>

		<h2 class="text-xl font-semibold mb-2 text-white mt-5">Course Topic and Materials:</h2>

		<div class="mt-5 mb-5 bg-blue-800 rounded-xl">
			<form action="{{ route("teacher.topic.update", [$topic->course->id, $topic->id]) }}" method="post" class="p-5">
				@csrf
				@method("patch")
				<!-- New Topic Title -->
				<div>
					<x-label for="title" :value="__('New Topic Title')" style="color: white;"/>
					<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $topic->title)"
						autofocus />
				</div>
				<div class="flex w-full justify-center gap-2 mt-8">
					<x-button class="bg-orange-500 w-full md:w-1/6">
						{{ __('Save') }}
					</x-button>
					<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/6">
						Cancel
					</x-button>
				</div>
			</form>
		</div>

		<div class="mt-8 mb-5 overflow-x-auto">
			<table class="w-full bg-white">
				<thead class="bg-blue-800 text-white">
					<th class="border px-4 py-2">Topic Title</th>
					<th class="border px-4 py-2">List of Materials</th>
					{{-- <th class="border px-4 py-2">Material Link</th> --}}
					<th class="border px-4 py-2">Action</th>
				</thead>
				<tbody>
					@if ($course->topics->count())
						@foreach ($course->topics as $topic)
							@if($topic->materials->count())
								@foreach ($topic->materials as $material)
									<tr>
										@if($loop->iteration == 1)
											<td class="border px-4 py-2" rowspan={{ $topic->materials->count() }}>
												<a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-700">{{ $topic->title }}</a>
											</td>
										@endif
										<td class="border px-4 py-2">{{ $material->title }}</td>
										{{-- <td class="border px-4 py-2"><a href="{{ $material->link }}" class="text-blue-600">{{ $material->link }}</a></td> --}}
										@if($loop->iteration == 1)
											<td class="border px-4 py-2" rowspan={{ $topic->materials->count() }}>
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
										@endif
									</tr>
								@endforeach
							@else
								<tr>
									<td class="border px-4 py-2"><a href="{{ route("teacher.topic.show", [$course->id, $topic->id]) }}" class="font-bold text-blue-700">{{ $topic->title }}</a></td>
									<td class="border px-4 py-2 text-center">- No materials added yet to this topic -</td>
									<td class="border px-4 py-2">
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
						<tr><td colspan="4" class="border px-4 py-2 text-center">- No topics added yet to this course -</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</x-section-container>
@endsection
