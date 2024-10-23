@extends("layouts.main-student")

@section("title")
	<h1>Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('student.assignment.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>{{ $assignment->title }}</span>
	> <span>Submit</span>
@endsection

@section("content")
	<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
		<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8" alt="back"></button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $assignment->title }}</h1>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<p class="mt-3 font-semibold">Assignment Description:</p>
		<p class="mt-1">{{ $assignment->desc }}</p>

		<p class="mt-3 text-blue">Please submit before <span class="font-bold">{{ $assignment->deadline_date }} {{ $assignment->deadline_time }}</span></p>

		<form action="{{ route("student.assignment.submit", [$course->id, $assignment->id]) }}" method="post" class="mt-8">
			@csrf
			<div class="flex flex-col">
				<label for="title">Submission Title</label>
				<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Submission Title" autofocus />
			</div>

			<div class="flex flex-col mt-4">
				<label for="link">You Work Link</label>
				<x-input id="link" class="w-full mt-1" type="text" name="link" style="border-width: 3px;" value="{{ old('link') }}" placeholder="Your Work Link" autofocus />
			</div>

			<div class="flex items-center justify-center w-full mt-16 mb-3 gap-3">
				<x-button class="w-full md:w-1/6">Submit</x-button>
				<x-cancel-button msg="The filled data will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
			</div>
		</form>

	</div>
@endsection
