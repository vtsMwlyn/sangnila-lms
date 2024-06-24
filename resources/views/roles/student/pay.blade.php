@extends("layouts.main-student")

@section("title")
	<h1>Payment</h1>
@endsection

@section("content")
	<form action="{{ route("student.pay.proceed") }}" method="post">
		@csrf
		<div class="">
			<x-label>{{ __("Select Course") }}</x-label>
			<x-select class="w-full mt-1" name="course" id="course">
				@foreach(Auth::user()->enrolled_courses as $course)
					<option value="{{ $course->id }}">{{ $course->course_name }}</option>
				@endforeach
			</x-select>
		</div>

		<div class="mt-3">
			<x-label>{{ __("Number of Session Extend") }}</x-label>
			<x-input class="w-full mt-1" type="number" name="session_extend" id="session_extend" :value="8"/>
		</div>

		<div class="flex gap-1 mt-5 w-full justify-end">
			<x-button class="bg-orange-500" type="button" onclick="history.back();">{{ __("Cancel") }}</x-button>
			<x-button class="bg-orange-500">{{ __("Proceed") }}</x-button>
		</div>
	</form>
@endsection
