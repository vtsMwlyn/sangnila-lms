@extends("layouts.main-admin")

@section("title")
	<h1>{{ $teacher->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.teacher.show', $teacher->id) }}" class="font-bold text-yellow-500">{{ (($teacher->details->gender == 1)? "Mr. " : "Ms. ") . $teacher->full_name }}</a>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ __("Edit Teacher's Data") }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route('admin.teacher.update', $teacher->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Teacher Name -->
			<div class="flex @error('full_name') items-start @else items-stretch @enderror gap-3">
				<x-boxed-label for="full_name" :value="__('Teacher Name')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="full_name" class="block w-full" type="text" name="full_name" :value="$teacher->full_name" placeholder="Teacher's name"
					autofocus />
				</div>
			</div>

			<!-- Phone Number -->
			<div class="mt-3 flex @error('phone_number') items-start @else items-stretch @enderror gap-3">
				<x-boxed-label for="phone_number" :value="__('Phone Number')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="phone_number" class="block w-full" type="text" name="phone_number" :value="$teacher->details->phone_number" placeholder="Add phone number"  />
				</div>
			</div>

			<!-- Teacher City of Birth -->
			<div class="mt-3 flex @error('city_of_birth') items-start @else items-stretch @enderror gap-3">
				<x-boxed-label for="city_of_birth" :value="__('Teacher City of Birth')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="city_of_birth" class="block w-full" type="text" name="city_of_birth" :value="$teacher->details->city_of_birth" placeholder="Add city of birth"  />
				</div>
			</div>

			<!-- Teacher Date of Birth -->
			<div class="mt-3 flex @error('date_of_birth') items-start @else items-stretch @enderror gap-3">
				<x-boxed-label for="date_of_birth" :value="__('Teacher Date of Birth')"/>
				<div class="flex flex-col w-full items-stretch">
					@if($teacher->details->date_of_birth)
						<x-input id="date_of_birth" class="block w-full" type="date" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
					@else
						<x-input id="date_of_birth" class="block w-full" type="text" onfocus="(this.type='date')" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
					@endif
				</div>
			</div>

			<div class="flex items-stretch justify-center mt-20 mb-3 gap-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button msg="The changes will be discarded, are you sure want to cancel?" class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
