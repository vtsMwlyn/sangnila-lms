@extends("layouts.main-admin")

@section("title")
	<h1>{{ $teacher->full_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Teacher Data</h1>
	<form action="{{ route('admin.teacher.update', $teacher->id) }}" method="post">
		@csrf
		@method('PATCH')
		<!-- Teacher Name -->
		<div>
			<x-label for="full_name" :value="__('New Teacher Name')" />
			<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="$teacher->full_name"
				autofocus />
		</div>

		<!-- Teacher Email -->
		<div class="mt-3">
			<x-label for="email" :value="__('New Teacher Email')"/>
			<x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="$teacher->email"  />
		</div>

		<div class="flex items-stretch justify-end mt-4 gap-1">
			<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Cancel
			</button>
			<x-button class="bg-indigo-400">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
