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
		<x-page-title>{{ __("Edit Teacher's Data") }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route('admin.teacher.update', $teacher->id) }}" method="post" class="mt-3">
			@csrf
			@method('PATCH')

			<div class="flex w-full gap-5">
				<!-- Teacher Name -->
				<div class="flex flex-col w-1/2">
					<x-label for="full_name" :value="__('Teacher Name')" />
					<x-input id="full_name" class="block w-full" type="text" name="full_name" :value="$teacher->full_name" placeholder="Teacher's name"
						autofocus />
				</div>

				<!-- Phone Number -->
				<div class="flex flex-col w-1/2">
					<x-label for="phone_number" :value="__('Phone Number')"/>
					<x-input id="phone_number" class="block w-full" type="text" name="phone_number" :value="$teacher->details->phone_number" placeholder="Add phone number"  />
				</div>
			</div>

			<div class="flex w-full gap-5 mt-3">
				<!-- Teacher City of Birth -->
				<div class="flex w-1/2 flex-col">
					<x-label for="city_of_birth" :value="__('Teacher City of Birth')"/>
					<x-input id="city_of_birth" class="block w-full" type="text" name="city_of_birth" :value="$teacher->details->city_of_birth" placeholder="Add city of birth"  />
				</div>

				<!-- Teacher Date of Birth -->
				<div class="flex w-1/2 flex-col">
					<x-label for="date_of_birth" :value="__('Teacher Date of Birth')"/>
					@if($teacher->details->date_of_birth)
						<x-input id="date_of_birth" class="block w-full" type="date" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
					@else
						<x-input id="date_of_birth" class="block w-full" type="text" onfocus="(this.type='date')" name="date_of_birth" :value="$teacher->details->date_of_birth" placeholder="Add date of birth"  />
					@endif
				</div>
			</div>

			<div class="flex items-stretch justify-end mt-8 mb-3 gap-3">
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
