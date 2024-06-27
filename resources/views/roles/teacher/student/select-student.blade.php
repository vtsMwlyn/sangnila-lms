@extends("layouts.main-teacher")

@section("title")
	<h1>Student Progress</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">Manage Students</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 text-center mb-8">Pick a Student</h1>
		<div class="flex flex-col">
			<div class="border-t border-l border-r border-blue-950 bg-slate-300 rounded-t-xl w-full">
				<h2 class="text-xl text-center font-semibold my-2 text-blue-950">Student Name</h2>
			</div>
			<div class="border border-blue-950 bg-slate-300 rounded-b-xl w-full flex justify-center">
				@if($course_students->count())
					<div class="flex justify-evenly w-full">
						@php
							$number_of_data_separation = 3;
							$n = ceil($course_students->count() / $number_of_data_separation);
						@endphp
						@for($i = 0; $i < $number_of_data_separation; $i++)
							<div class="my-5">
								<ul class="list-disc pl-6 font-semibold">
									@if($i < $number_of_data_separation - 1)
										@for($j = $n * $i; $j < $n * ($i + 1); $j++)
											<li class="text-blue-950">
												<a href="{{ route('teacher.student.show.progress', ['student_id' => $course_students[$j]->student->id, 'course_id' => $course->id]) }}" class="hover:underline">{{ $course_students[$j]->student->full_name }} @if($course_students[$j]->student->status == "disabled")<span class="text-red-500">(Disabled)</span>@endif
												</a>
											</li>
										@endfor
									@else
										@for($j = $n * $i; $j < $course_students->count(); $j++)
											<li class="text-blue-950">
												<a href="{{ route('teacher.student.show.progress', ['student_id' => $course_students[$j]->student->id, 'course_id' => $course->id]) }}" class="hover:underline">{{ $course_students[$j]->student->full_name }} @if($course_students[$j]->student->status == "disabled")<span class="text-red-500">(Disabled)</span>@endif
												</a>
											</li>
										@endfor
									@endif
								</ul>
							</div>
						@endfor
					</div>
				@else
					<p class="text-blue-950 font-semibold py-3">- No students assigned yet to the course -</p>
				@endif
			</div>
		</div>
	</x-section-container>
@endsection
