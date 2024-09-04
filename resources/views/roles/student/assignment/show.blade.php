@extends("layouts.main-student")

@section("title")
	<h1>My Assignments</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>My Assignments</x-page-title>
		<h1 class="text-xl font-semibold text-center text-blue-900 mb-8">Course: {{ $course->course_name }}</h1>

		@if(session()->has("successSubmitAssignment"))
			<x-badge-success badge_text="{{ session('successSubmitAssignment') }}"></x-badge-success>
		@elseif(session()->has("successEditSubmission"))
			<x-badge-success badge_text="{{ session('successEditSubmission') }}"></x-badge-success>
		@elseif(session()->has("maximumSubmission"))
			<x-badge-danger badge_text="{{ session('maximumSubmission') }}"></x-badge-danger>
		@endif

		@forelse ($assignments as $index => $asg)
			<div class="rounded-md w-full mt-5 my-5 p-5 border" style="background: rgba(256, 256, 256, 0.4)">
				<h3 class="text-xl font-semibold text-blue-950">{{ $asg->title }}</h3>

				@if($submissions_per_assignment[$index] > 0)
					<div class="flex items-center gap-2">
						<p class="mt-2 mb-2">Submitted</p>
						<i class="bi bi-patch-check-fill text-2xl text-green-800"></i>
					</div>
					<a class="text-blue-800 hover:underline font-bold" href="{{ route("student.assignment.detail", [$course->id, Auth::user()->id ,$asg->id]) }}">Submission history and feedback</a>
				@endif

				<p class="mt-3 italic ">Assignment Description:</p>
				<p class="mt-1 ">{{ $asg->desc }}</p>

				<p class="mt-3 text-blue-950 ">Please submit before <span class="font-bold">{{ $asg->deadline_date }} {{ $asg->deadline_time }}</span></p>

				@if($submissions_per_assignment[$index] < 10)
					<p class="font-bold  text-blue-950">New submissions allowed: {{ 10 - $submissions_per_assignment[$index] }} time(s)</p>
				@else
					<p class="font-bold  text-red-500">Number of new submissions reached its limit!</p>
				@endif

				<div class="flex items-center gap-3 mt-5 mb-3">
					<x-anchor-button class="bg-orange-500"
						href="{{ $asg->link }}">
						Download
					</x-anchor-button>
					@if($asg->submissions && $asg->submissions->where("student_id", Auth::user()->id)->count())
						<x-anchor-button class="bg-orange-500"
							href="{{ route('student.assignment.submit', [$course->id, $asg->id]) }}">
							New submission
						</x-anchor-button>
					@else
						<x-anchor-button class="bg-orange-500"
							href="{{ route('student.assignment.submit', [$course->id, $asg->id]) }}">
							Upload
						</x-anchor-button>
					@endif
				</div>
			</div>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No assignments given yet -</div>
		@endforelse
	</x-section-container>
@endsection
