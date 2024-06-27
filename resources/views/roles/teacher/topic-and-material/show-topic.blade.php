@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">
			<a href="{{ route("teacher.mycourse.show", $topic->course->id) }}">{{ $topic->course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">{{ $topic->title }}</h1>

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

		<div class="flex gap-2 mb-6">
			<x-anchor-button class="bg-orange-500" href="{{ route('teacher.topic.edit', [$topic->course->id, $topic->id]) }}"><i class="bi bi-pencil-square"></i> Edit Topic</x-anchor-button>
			<x-anchor-button class="bg-orange-500" href="{{ route('teacher.topic.delete', [$topic->course->id, $topic->id]) }}"><i class="bi bi-trash3"></i> Delete Topic</x-anchor-button>
		</div>

		<h2 class="text-xl font-semibold mb-2 text-white">Material List:</h2>
		<div class="flex mt-4">
			<x-anchor-button class="bg-orange-500" href="{{ route('teacher.material.upload', $topic->id) }}"><i class="bi bi-plus-lg"></i> Add New Material</x-anchor-button>
		</div>

		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Material Title</th>
					<th class="template-heads" style="min-width: 30vw;">Material Description</th>
					<th class="template-heads" style="max-width: 20vw;">Link</th>
					<th class="template-heads rounded-r-xl">Actions</th>
				</x-slot>

				@forelse ($topic->materials as $material)
					<tr>
						<td class="template-bodies rounded-l-xl">{{ $material->title }}</td>
						<td class="template-bodies" style="min-width: 30vw;">{{ $material->desc }}</td>
						<td class="template-bodies" style="max-width: 20vw; word-wrap: break-word;"><a href="{{ $material->link }}" target="blank" class="text-blue-200 font-bold hover:text-blue-400 hover:underline">{{ $material->link }}</a></td>
						<td class="template-bodies rounded-r-xl">
							<div class="flex gap-1">
								<x-anchor-button class="bg-orange-500"
									href="{{ route('teacher.material.edit', $material->id) }}">
									<i class="bi bi-pencil-square"></i>
								</x-anchor-button>
								<x-anchor-button class="bg-orange-500"
									href="{{ route('teacher.material.remove', $material->id) }}">
									<i class="bi bi-trash3"></i>
								</x-anchor-button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="bg-white rounded-xl p-5 text-center">- No materials added yet to this topic -</td>
					</tr>
				@endforelse
			</x-table>
		</div>
	</x-section-container>
@endsection
