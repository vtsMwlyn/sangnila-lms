@extends("layouts.main-teacher")

@section("title")
	<h1>{{ $topic->course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $topic->course->course_name }}</h1>
	<h1 class="text-2xl font-semibold text-blue-900 mb-4">{{ $topic->title }}</h1>

	<div class="mt-5 mb-5">
		<form action="{{ route("teacher.topic.update", [$topic->course->id, $topic->id]) }}" method="post" class="border rounded-lg p-5">
			@csrf
			@method("patch")
			<!-- New Topic Title -->
			<div>
				<x-label for="title" :value="__('New Topic Title')" />
				<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $topic->title)"
					autofocus />
			</div>
			<div class="flex gap-1 mt-3">
				<x-button class="bg-indigo-400">
					{{ __('Save') }}
				</x-button>
				<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-indigo-400">
					Cancel
				</x-button>
			</div>
		</form>
	</div>

	<h2 class="text-xl font-semibold mb-2">Material List:</h2>
	<div class="overflow-x-auto">
		<table class="min-w-full">
			<thead>
				<th class="border px-3">Material Title</th>
				<th class="border px-3">Link</th>
				<th class="border px-3">Actions</th>
			</thead>
			<tbody>
				@forelse ($topic->materials as $material)
					<tr>
						<td class="border px-3">{{ $material->title }}</td>
						<td class="border px-3"><a href="{{ $material->link }}" class="text-blue-700" target="blank">{{ $material->link }}</a></td>
						<td class="border px-3">
							<div class="flex gap-1">
								<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
									href="#">
									Edit Material
								</a>
								<form action="#" method="post">
									@csrf
									@method("delete")
									<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
										Delete Material
									</button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="3" class="border px-3">- No materials added yet to this topic -</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
@endsection
