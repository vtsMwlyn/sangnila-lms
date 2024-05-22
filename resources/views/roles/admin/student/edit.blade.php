@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Student Data</h1>
	<form action="{{ route('admin.student.update', $student->id) }}" method="post">
		@csrf
		@method('PATCH')
		<!-- Student Name -->
		<div>
			<x-label for="full_name" :value="__('New Student Name')" />
			<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="$student->full_name"
				autofocus />
		</div>

		<!-- Student Email -->
		<div class="mt-3">
			<x-label for="email" :value="__('New Student Email')"/>
			<x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="$student->email"  />
		</div>

		<div class="flex items-stretch justify-end mt-4 gap-1">
			<x-button type="button" onclick="history.back()" class="bg-indigo-400">
					Cancel
			</x-button>
			<x-button class="bg-indigo-400">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
