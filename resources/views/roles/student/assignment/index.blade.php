@extends("layouts.main-student")

@section("title")
	<h1>Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Select Course</span>
@endsection

@section("content")
	@forelse ($assignments_data as $ad)
		<a href="{{ route('student.assignment.show', $ad["course_student"]->course->id) }}" class="w-full transition duration-300 hover:scale-[101%] relative">
			<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
				<p class="font-bold text-dark-blue">{{ $ad["course_student"]->course->course_name }} - {{ ucwords($ad["course_student"]->course->level) }}</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
				<div class="hidden md:flex">
					<div class="w-1/4">Total Assignments</div>
					<div class="w-1/4">Completed Assignments</div>
					<div class="w-1/4">Pending Assignments</div>
					<div class="w-1/4 text-red">Due Soon Assignment</div>
				</div>
				<div class="hidden md:flex">
					<div class="w-1/4 font-semibold">{{ $ad["status"]["total"] }}</div>
					<div class="w-1/4 font-semibold">{{ $ad["status"]["done"] }}</div>
					<div class="w-1/4 font-semibold">{{ $ad["status"]["pending"] }}</div>
					<div class="w-1/4 font-semibold text-red">{{ $ad["status"]["nearest_deadline"] }}</div>
				</div>
				<div class="flex flex-col md:hidden">
					<div class="flex w-full justify-between">
						<div>Total Assignments</div>
						<div>{{ $ad["status"]["total"] }}</div>
					</div>
					<div class="flex w-full justify-between">
						<div>Completed Assignments</div>
						<div>{{ $ad["status"]["done"] }}</div>
					</div>
					<div class="flex w-full justify-between">
						<div>Pending Assignments</div>
						<div>{{ $ad["status"]["pending"] }}</div>
					</div>
					<div class="flex w-full flex-col text-red mt-4">
						<div>Due Soon Assignment</div>
						<div>{{ $ad["status"]["nearest_deadline"] }}</div>
					</div>
				</div>
			</div>

			@if($ad["status"]["pending"] > 0)
				<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
			@endif
		</a>
	@empty
	@endforelse
@endsection
