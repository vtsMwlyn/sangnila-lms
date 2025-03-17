@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->enrolled_courses->where('status', 'active') as $course)
			<a href="{{ route('student.learning-documentation.show', $course->id) }}" class="oneperthree transition duration-300 hover:scale-[102%]">
				<div class="rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
					{{-- Course information --}}
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
					@php
						$n_portolios = App\Models\Portfolio::where('course_id', $course->id)->where('student_id', Auth::user()->id)->count();
						$c_status = App\Models\Assessment::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first()->certificate_accessible;
					@endphp
					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>
						{{ $n_portolios }} Portfolio Uploaded
					</div>
					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>
                        Certificate @if($c_status == 1) Available @else Not Available Yet @endif
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>

@endsection
