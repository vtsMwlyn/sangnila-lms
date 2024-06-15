@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Edit Student's Data") }}</x-page-title>

	<form action="{{ route('admin.student.update', $student->id) }}" method="post" class="bg-indigo-200 p-10 rounded-3xl mt-10">
		@csrf
		@method('PATCH')
		<!-- Student Name -->
		<div>
			<x-label for="full_name" :value="__('Student Name')" />
			<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" placeholder="Student's Name" :value="$student->full_name"
				autofocus />
		</div>

		<!-- Student Phone Number -->
		<div class="mt-3">
			<x-label for="phone_number" :value="__('Student Phone Number')"/>
			<x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="$student->details->phone_number" placeholder="Add phone number"  />
		</div>

		<!-- Student City of Birth -->
		<div class="mt-3">
			<x-label for="city_of_birth" :value="__('Student City of Birth')"/>
			<x-input id="city_of_birth" class="block mt-1 w-full" type="text" name="city_of_birth" :value="$student->details->city_of_birth" placeholder="Add city of birth"  />
		</div>

		<!-- Student Date of Birth -->
		<div class="mt-3">
			<x-label for="date_of_birth" :value="__('Student Date of Birth')"/>
			@if($student->details->date_of_birth)
				<x-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="$student->details->date_of_birth" placeholder="Add date of birth"  />
			@else
				<x-input id="date_of_birth" class="block mt-1 w-full" type="text" onfocus="(this.type='date')" name="date_of_birth" :value="$student->details->date_of_birth" placeholder="Add date of birth"  />
			@endif
		</div>


		<!-- Student School Name -->
		<div class="mt-10">
			<x-label for="school_name" :value="__('Student School Name')"/>
			<x-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="$student->details->school_name" placeholder="Add school name"  />
		</div>

		<!-- Student Education Level -->
		<div class="mt-4">
			<x-label for="student_level" class="text-white" :value="__('Select Student Education Level')" />
			<x-select name="student_level" id="student_level"
			class="mt-1 w-1/3">
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

		<!-- Student Parent's Name -->
		<div class="mt-3">
			<x-label for="name_parent" :value="__('Student Parent\'s Name')"/>
			<x-input id="name_parent" class="block mt-1 w-full" type="text" name="name_parent" :value="$student->details->name_parent" placeholder="Add parent's name"  />
		</div>

		<!-- Student Parent's Phone Number -->
		<div class="mt-3">
			<x-label for="phone_parent" :value="__('Student Parent\'s Phone Number')"/>
			<x-input id="phone_parent" class="block mt-1 w-full" type="text" name="phone_parent" :value="$student->details->phone_parent" placeholder="Add parent's phone number"  />
		</div>

		<div class="flex items-stretch justify-end mt-8 gap-1">
			<x-button class="bg-orange-500">
				{{ __('Save') }}
			</x-button>
			<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500">
				Cancel
			</x-button>
		</div>
	</form>
@endsection
