@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title>{{ __("Edit Student's Data") }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('admin.student.update', $student->id) }}" method="post" class="mt-3">
			@csrf
			@method('PATCH')

			<div class="flex w-full gap-5">
				{{-- Student Name --}}
				<div class="flex flex-col w-1/2">
					<x-label for="full_name">Full Name<span class="text-red">*</span></x-label>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="full_name" class="block w-full" type="text" name="full_name" placeholder="Student's Name" :value="$student->full_name"
						autofocus />
					</div>
				</div>

				{{-- Student Phone Number --}}
				<div class="flex flex-col w-1/2">
					<x-label for="phone_number" :value="__('Phone Number')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="phone_number" class="block w-full" type="text" name="phone_number" :value="$student->details->phone_number" placeholder="Add phone number"  />
					</div>
				</div>
			</div>

			<div class="flex w-full gap-5 mt-3">
				{{-- Student City of Birth --}}
				<div class="flex flex-col w-1/2">
					<x-label for="city_of_birth" :value="__('City of Birth')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="city_of_birth" class="block w-full" type="text" name="city_of_birth" :value="$student->details->city_of_birth" placeholder="Add city of birth"  />
					</div>
				</div>

				{{-- Student Date of Birth --}}
				<div class="flex flex-col w-1/2">
					<x-label for="date_of_birth" :value="__('Date of Birth')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="date_of_birth" class="block w-full date-input" type="date" name="date_of_birth" :value="$student->details->date_of_birth" placeholder="Add date of birth"  />
					</div>
				</div>
			</div>

			<div class="flex w-full gap-5 mt-3">
				{{-- Student School Name --}}
				<div class="flex flex-col w-1/2">
					<x-label for="school_name" :value="__('School Name')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="school_name" class="block w-full" type="text" name="school_name" :value="$student->details->school_name" placeholder="Add school name"  />
					</div>
				</div>

				{{-- Student Education Level --}}
				<div class="flex flex-col w-1/2">
					<x-label for="student_level" :value="__('Education Level')" />
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
			</div>

			<div class="flex w-full gap-5 mt-3">
				{{-- Student Parent's Name --}}
				<div class="flex flex-col w-1/2">
					<x-label for="name_parent" :value="__('Parent\'s Name')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="name_parent" class="block w-full" type="text" name="name_parent" :value="$student->details->name_parent" placeholder="Add parent's name"  />
					</div>
				</div>

				{{-- Student Parent's Phone Number --}}
				<div class="flex flex-col w-1/2">
					<x-label for="phone_parent" :value="__('Parent\'s Phone Number')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="phone_parent" class="block w-full" type="text" name="phone_parent" :value="$student->details->phone_parent" placeholder="Add parent's phone number"  />
					</div>
				</div>
			</div>

			<div class="flex items-stretch justify-end mt-10 mb-3 gap-3">
				<x-cancel-button class="w-full md:w-1/4">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-1/4">
					{{ __('Save') }}
				</x-button>
			</div>
		</form>
	</x-section-container>
@endsection
