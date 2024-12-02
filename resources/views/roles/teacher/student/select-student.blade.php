@extends("layouts.main-teacher")

@section("title")
	<h1>Student Progress</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.student.select-course') }}" class="text-yellow-500 font-bold">Select Course</a>
	> <span>{{ $course->course_name }}</span>
	> <span>Select Student</span>
@endsection

@section("content")
<div class="w-full flex flex-wrap gap-5">
	@forelse ($course_students as $index => $cs)
		<a href="{{ route('teacher.student.show.progress', ['student_id' => $cs->student->id, 'course_id' => $course->id]) }}"  style="width: 32%;" class="transition duration-300 hover:scale-105">
			<!-- Counting how many students has no access at all to any activities -->
			@php
				$course_students = App\Models\CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

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
						<i class="bi bi-book-half text-slate-400"></i>{{ __($n_unlocked . "/" . $progress_per_student->count()) }} Materials Unlocked
					</div>

					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ __($n_opened . "/" . $progress_per_student->count()) }} Activities Read
					</div>
				</div>
			</div>
		</a>
	@empty
		<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
	@endforelse
</div>
	{{-- <x-section-container>
		<x-page-title>Manage Students' Material Access</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 text-center mb-8">Pick a Student</h1>

		<div class="w-full">
			<div class="w-full py-6 text-white rounded-xl font-bold text-center" style="background: #000C48;">
				Student List
			</div>

			@if($course_students->count())
				<div class="flex flex-col gap-5 mt-5">
					@foreach ($course_students as $index => $cs)
						@if ($index % 3 == 0)
							@if ($index != 0)
								</div> <!-- Close previous row -->
							@endif
							<div class="flex w-full rounded-xl overflow-hidden text-white h-16" style="background-color: #283785;">
						@endif

						<a href="{{ route('teacher.student.show.progress', ['student_id' => $cs->student->id, 'course_id' => $course->id]) }}" class="text-center w-1/3 h-full selections transition ease-in-out duration-500 flex justify-center items-center">
							{{ $cs->student->full_name }}
						</a>

					@endforeach

					</div> <!-- Close last row -->
				</div>
			@else
				<div class="text-center bg-white rounded-xl w-full font-semibold p-5 mt-5">- No students assigned yet -</div>
			@endif
		</div>

		<script>
			$(".selections").on({
				"mouseover": function(){
					$(this).css({"background-color": "rgb(250 204 21)"});
				},
				"mouseout": function(){
					$(this).css({"background-color": "#283785"});
				}
			})
		</script>
	</x-section-container> --}}
@endsection
