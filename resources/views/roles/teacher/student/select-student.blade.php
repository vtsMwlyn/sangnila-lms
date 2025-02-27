@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.student.select-course') }}" class="text-yellow-500 font-bold">Select Course</a>
	> <span>{{ $course->course_name }}</span>
	> <span>Select Student</span>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.student.select-course') }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Select a student to continue</h1>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<div class="w-full flex flex-wrap gap-5 mt-4">
			@forelse ($course_students as $index => $cs)
				<!-- Counting how many students has no access at all to any activities -->
				@php
					$n_unlocked = 0;
					$n_opened = 0;

					$progress_per_student = App\Models\Progress::where("course_id", $course->id)->where("student_id", $cs->student->id)->get();

					if($progress_per_student->count() > 0){
						foreach($progress_per_student as $p){
							if($p->status == "unlocked"){
								$n_unlocked++;
							}

							if($p->already_opened == "yes"){
								$n_opened++;
							}
						}
					}
				@endphp

				<a href="{{ route('teacher.student.show', ['student_id' => $cs->student->id, 'course_id' => $course->id]) }}"   class="oneperthree transition duration-300 hover:scale-105 relative">
					<div class="bg-white rounded-3xl p-5 shadow-lg flex gap-4 items-start">
						<div class="">
							@if($cs->student->details->profpic)
								<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-20 h-20 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;">
							@else
								<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-20 h-20 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
							@endif
						</div>
						<div class="grow">
							<!-- Course information -->
							<p class="font-bold text-dark-blue">{{ $cs->student->full_name }}</p>
							<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

							<div class="flex gap-2 items-center @if($n_unlocked == 0) text-red @endif">
								<i class="bi bi-book-half text-slate-400"></i>{{ __($n_unlocked . "/" . $progress_per_student->count()) }} Activities Unlocked
							</div>

							<div class="flex gap-2 items-center">
								<i class="bi bi-book-half text-slate-400"></i>{{ __($n_opened . "/" . $progress_per_student->count()) }} Activities Read
							</div>
						</div>
					</div>

					@if($n_unlocked == 0)
						<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
					@endif
				</a>
			@empty
				<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
			@endforelse
		</div>
	</x-section-container>
@endsection
