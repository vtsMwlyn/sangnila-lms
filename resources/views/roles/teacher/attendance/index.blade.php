@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Select Course</span>
@endsection

@section("content")
	<div class="w-full flex flex-wrap gap-5">
		@forelse (Auth::user()->teached_courses as $course)
			<a href="{{ route('teacher.attendance.show', $course->id) }}"  style="width: 32%;" class="transition duration-300 hover:scale-105">
				<div class="bg-white rounded-3xl p-5 shadow-lg">
					<!-- Counting assignments posted and nearest deadline -->
					@php
						$attendances = App\Models\Attendance::where("teacher_id", Auth::user()->id)->where("course_id", $course->id)->latest()->get();
					@endphp

					<!-- Course information -->
					<p class="font-bold text-dark-blue">{{ $course->course_name }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ $attendances->count() }} Attendance Reports Uploaded
					</div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/clock.svg') }}" class="w-4 h-4" alt="icon">Latest Report: {{ ($attendances->count() > 0) ? Carbon\Carbon::parse($attendances->first()->attendance_date)->format("D, d M Y") : 'N/A' }}
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses assigned yet -</div>
		@endforelse
	</div>
	{{-- <x-section-container>
		<x-page-title>Manage Attendance</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">Pick a Course</h1>
		<div class="overflow-x-auto rounded-md">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-xl">Course Name</th>
				</x-slot>
				@if (Auth::user()->teached_courses->isNotEmpty())
					@foreach (Auth::user()->teached_courses as $course)
						<tr>
							<td class="template-bodies rounded-xl selections transition ease-in-out duration-500" style="padding: 0;">
								<div class="flex w-full h-full items-stretch p-5">
									<a href="{{ route('teacher.attendance.show', $course->id) }}" class="font-bold h-full w-full">
										{{ $course->course_name }}
									</a>
								</div>
							</td>
						</tr>
					@endforeach
				@else
					<tr><td class="bg-white rounded-xl p-5 text-center font-semibold">- No courses assigned yet -</td></tr>
				@endif
			</x-table>
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
