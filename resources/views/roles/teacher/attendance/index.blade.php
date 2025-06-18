@extends("layouts.main-teacher")

@section("title")
	<h1>Attendance</h1>
@endsection


@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->teached_courses as $course)
			<a href="{{ route('teacher.attendance.show', $course->id) }}"  class="oneperthree transition duration-300 hover:scale-[102%]">
				<div class="rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
					{{-- Counting assignments posted and nearest deadline --}}
					@php
						$attendances = App\Models\Attendance::where("uploader_id", Auth::user()->id)->where("course_id", $course->id)->latest()->get();
					@endphp

					{{-- Course information --}}
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ $attendances->count() }} Attendance Reports Uploaded
					</div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">Latest Upload: {{ ($attendances->count() > 0) ? Carbon\Carbon::parse($attendances->first()->attendance_date)->format("D, d M Y") : 'N/A' }}
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse

		@php
			$substitution_attendances = App\Models\Attendance::where('is_substitution', 1)->where('uploader_id', Auth::id())->latest()->get();
		@endphp
		<a href="{{ route('teacher.attendance.substitution.index') }}"  class="oneperthree transition duration-300 hover:scale-[102%]">
			<div class="rounded-3xl p-5 shadow-lg" style="background-color: #FEFEFEB2;">
				<p class="font-bold text-light-blue">Upload as Substitute Teacher</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

				<div class="flex gap-2 items-center">
					<i class="bi bi-book-half text-slate-400"></i>{{ $substitution_attendances->count() }} Substitution Attendance Reports Uploaded
				</div>

				<div class="flex gap-2 items-center">
					<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">Latest Upload: {{ $substitution_attendances->count() > 0 ? Carbon\Carbon::parse($substitution_attendances->first()->attendance_date)->format("D, d M Y") : 'N/A' }}
				</div>
			</div>
		</a>
	</div>
@endsection
