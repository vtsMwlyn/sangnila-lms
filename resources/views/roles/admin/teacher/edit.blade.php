@extends("layouts.main-admin")

@section("title")
	<h1>{{ $teacher->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Edit Teacher's Data") }}</x-page-title>
	<form action="{{ route('admin.teacher.update', $teacher->id) }}" method="post" class="bg-indigo-200 p-10 rounded-3xl">
		@csrf
		@method('PATCH')
		<!-- Teacher Name -->
		<div>
			<x-label for="full_name" :value="__('Teacher Name')" />
			<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="$teacher->full_name" placeholder="Teacher's name"
				autofocus />
		</div>

		<!-- Teacher Phone Number -->
		<div class="mt-3">
			<x-label for="phone_number" :value="__('Teacher Phone Number')"/>
			<x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="$teacher->details->phone_number" placeholder="Add phone number"  />
		</div>

		<!-- Teacher City of Birth -->
		<div class="mt-3">
			<x-label for="city_of_birth" :value="__('Teacher City of Birth')"/>
			<x-input id="city_of_birth" class="block mt-1 w-full" type="text" name="city_of_birth" :value="$teacher->details->city_of_birth" placeholder="Add city of birth"  />
		</div>

		<!-- Teacher Date of Birth -->
		<div class="mt-3">
			<x-label for="date_of_birth" :value="__('Teacher Date of Birth')"/>
			@if($teacher->details->date_of_birth)
				<x-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
			@else
				<x-input id="date_of_birth" class="block mt-1 w-full" type="text" onfocus="(this.type='date')" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
			@endif
		</div>

		<div class="flex items-stretch justify-end mt-8 gap-1">
			<x-button type="button" onclick="history.back()" class="bg-orange-500">
					Cancel
			</x-button>
			<x-button class="bg-orange-500">
				{{ __('Save') }}
			</x-button>
		</div>

	</form>
@endsection
