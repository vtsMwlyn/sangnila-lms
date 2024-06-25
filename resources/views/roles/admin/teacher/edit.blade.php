@extends("layouts.main-admin")

@section("title")
	<h1>{{ $teacher->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ __("Edit Teacher's Data") }}</x-page-title>
		<form action="{{ route('admin.teacher.update', $teacher->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Teacher Name -->
			<div class="flex items-stretch gap-3">
				<x-boxed-label for="full_name" :value="__('Teacher Name')" />
				<x-input id="full_name" class="block w-full" type="text" name="full_name" :value="$teacher->full_name" placeholder="Teacher's name"
					autofocus />
			</div>

			<!-- Teacher Phone Number -->
			<div class="mt-3 flex items-stretch gap-3">
				<x-boxed-label for="phone_number" :value="__('Teacher Phone Number')"/>
				<x-input id="phone_number" class="block w-full" type="text" name="phone_number" :value="$teacher->details->phone_number" placeholder="Add phone number"  />
			</div>

			<!-- Teacher City of Birth -->
			<div class="mt-3 flex items-stretch gap-3">
				<x-boxed-label for="city_of_birth" :value="__('Teacher City of Birth')"/>
				<x-input id="city_of_birth" class="block w-full" type="text" name="city_of_birth" :value="$teacher->details->city_of_birth" placeholder="Add city of birth"  />
			</div>

			<!-- Teacher Date of Birth -->
			<div class="mt-3 flex items-stretch gap-3">
				<x-boxed-label for="date_of_birth" :value="__('Teacher Date of Birth')"/>
				@if($teacher->details->date_of_birth)
					<x-input id="date_of_birth" class="block w-full" type="date" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
				@else
					<x-input id="date_of_birth" class="block w-full" type="text" onfocus="(this.type='date')" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
				@endif
			</div>

			<div class="flex items-stretch justify-center mt-20 mb-3 gap-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Save') }}
				</x-button>
				<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/5">
					Cancel
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
