@extends("layouts.main-student")

@section("title")
	<h1>My Assignments</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Assignment Submission</h1>

	<div class="rounded-md w-full mt-5 my-5 p-5 border">
		<h3 class="text-xl font-semibold">{{ $assignment->title }}</h3>
		<p class="mt-3 italic">Assignment Description:</p>
		<p>{{ $assignment->desc }}</p>
		<p class="mt-3">Please submit before <span class="font-semibold">{{ $assignment->deadline_date }} {{ $assignment->deadline_time }}</span></p>

		<form action="{{ route("student.assignment.submit", [$course->id, $assignment->id]) }}" method="post" class="mt-4">
			@csrf
			<!-- Submission Title -->
			<div>
				<x-label for="title" :value="__('Submission Title')" />
				<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')"
					autofocus />
			</div>

			<!-- Link -->
			<div class="mt-3">
				<x-label for="link" :value="__('Link of Your Answer/Work')" />
				<x-input id="link" class="block mt-1 w-full" type="text" name="link" :value="old('link')"/>
			</div>

			<div class="flex items-center justify-end mt-6 gap-1">
				<x-button type="submit" class="bg-indigo-400">
					Submit
				</x-button>
				<x-button type="button" onclick="if(confirm('The filled data will be discarded, are you sure want to cancel?')) history.back();" class="bg-indigo-400">
					Cancel
				</x-button>
			</div>
		</form>

	</div>
@endsection
