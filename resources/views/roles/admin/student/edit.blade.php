@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.student.show', $student->id) }}" class="font-bold text-yellow-500">{{ $student->full_name }}</a>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Edit Student's Data") }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route('admin.student.update', $student->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Student Name -->
			<div class="flex gap-3 @error('full_name') items-start @else items-stretch @enderror">
				<x-boxed-label for="full_name" :value="__('Full Name')" />
				<div class="flex flex-col w-full items-stretch">
					<x-input id="full_name" class="block w-full" type="text" name="full_name" placeholder="Student's Name" :value="$student->full_name"
					autofocus />
				</div>
			</div>

			<!-- Student Phone Number -->
			<div class="mt-3 flex gap-3 @error('phone_number') items-start @else items-stretch @enderror">
				<x-boxed-label for="phone_number" :value="__('Phone Number')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="phone_number" class="block w-full" type="text" name="phone_number" :value="$student->details->phone_number" placeholder="Add phone number"  />
				</div>
			</div>

			<!-- Student City of Birth -->
			<div class="mt-3 flex gap-3 @error('city_of_birth') items-start @else items-stretch @enderror">
				<x-boxed-label for="city_of_birth" :value="__('City of Birth')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="city_of_birth" class="block w-full" type="text" name="city_of_birth" :value="$student->details->city_of_birth" placeholder="Add city of birth"  />
				</div>
			</div>

			<!-- Student Date of Birth -->
			<div class="mt-3 flex gap-3 @error('date_of_birth') items-start @else items-stretch @enderror">
				<x-boxed-label for="date_of_birth" :value="__('Date of Birth')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="date_of_birth" class="block w-full date-input" type="date" name="date_of_birth" :value="$student->details->date_of_birth" placeholder="Add date of birth"  />
				</div>
			</div>


			<!-- Student School Name -->
			<div class="mt-8 flex gap-3 @error('school_name') items-start @else items-stretch @enderror">
				<x-boxed-label for="school_name" :value="__('School Name')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="school_name" class="block w-full" type="text" name="school_name" :value="$student->details->school_name" placeholder="Add school name"  />
				</div>
			</div>

			<!-- Student Education Level -->
			<div class="mt-4 flex gap-3 @error('student_level') items-start @else items-stretch @enderror">
				<x-boxed-label for="student_level" class="text-white" :value="__('Education Level')" />
				<div class="flex flex-col w-full items-stretch">
					<x-select name="student_level" id="student_level"
					class="w-full">
						@if($student->details->student_level)
							@forelse ($education_levels as $level)
								<option value="{{ $student->details->student_level }}" @if($student->details->student_level == $level) selected @endif>{{ $level }}</option>
							@empty
							@endforelse
						@else
							<option value="notselectedyet" selected disabled>Add Student Education Level</option>
							@forelse ($education_levels as $level)
								<option value="{{ $level }}">{{ $level }}</option>
							@empty
							@endforelse
						@endif
					</x-select>
				</div>
			</div>

			<!-- Student Parent's Name -->
			<div class="mt-3 flex gap-3 @error('name_parent') items-start @else items-stretch @enderror">
				<x-boxed-label for="name_parent" :value="__('Parent\'s Name')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="name_parent" class="block w-full" type="text" name="name_parent" :value="$student->details->name_parent" placeholder="Add parent's name"  />
				</div>
			</div>

			<!-- Student Parent's Phone Number -->
			<div class="mt-3 flex gap-3 @error('phone_parent') items-start @else items-stretch @enderror">
				<x-boxed-label for="phone_parent" :value="__('Parent\'s Phone Number')"/>
				<div class="flex flex-col w-full items-stretch">
					<x-input id="phone_parent" class="block w-full" type="text" name="phone_parent" :value="$student->details->phone_parent" placeholder="Add parent's phone number"  />
				</div>
			</div>

			<div class="flex items-stretch justify-center mt-20 mb-3 gap-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
