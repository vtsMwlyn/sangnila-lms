@extends("layouts.main-student")

@section("title")
	<h1>My Assignments</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">Assignment Submission</x-page-title>

		<div class="rounded-md w-full mt-5 my-5 p-5 border" style="background: rgba(256, 256, 256, 0.4)">
			<h3 class="text-xl font-semibold text-blue-950">{{ $assignment->title }}</h3>

			<p class="mt-3 italic">Assignment Description:</p>
			<p class="mt-1">{{ $assignment->desc }}</p>

			<p class="mt-3 text-blue-950">Please submit before <span class="font-bold">{{ $assignment->deadline_date }} {{ $assignment->deadline_time }}</span></p>

			<form action="{{ route("student.assignment.submit", [$course->id, $assignment->id]) }}" method="post" class="mt-8">
				@csrf
				<!-- Submission Title -->
				<div class="flex gap-3 items-stretch w-full">
					<x-boxed-label for="title" :value="__('Submission Title')" />
					<x-input id="title" class="block w-full" type="text" name="title" :value="old('title')"
						autofocus />
				</div>

				<!-- Link -->
				<div class="mt-3 flex gap-3 items-stretch w-full">
					<x-boxed-label for="link" :value="__('Your Work Link')" />
					<x-input id="link" class="block w-full" type="text" name="link" :value="old('link')"/>
				</div>

				<div class="flex items-center justify-center w-full mt-16 mb-3 gap-3">
					<x-button type="submit" class="bg-orange-500 w-full md:w-1/6">
						Submit
					</x-button>
					<x-button type="button" onclick="if(confirm('The filled data will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/6">
						Cancel
					</x-button>
				</div>
			</form>

		</div>
	</x-section-container>
@endsection
