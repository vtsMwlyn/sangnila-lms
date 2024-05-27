@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Edit Student's Data") }}</x-page-title>

	<form action="{{ route('admin.student.update', $student->id) }}" method="post" class="bg-indigo-200 py-5 px-10 rounded-xl">
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
			<x-button type="button" onclick="history.back()" class="bg-orange-500">
					Cancel
			</x-button>
			<x-button class="bg-orange-500">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
