@extends("layouts.main-teacher")

@section("title")
	<h1>Student Progress</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">Manage Students' Material Access</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 text-center mb-8">Pick a Student</h1>

		<div class="w-full">
			@if($course_students->count())
				<div class="w-full py-6 text-white rounded-xl font-bold text-center" style="background: #000C48;">
					Student List
				</div>
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
				<p class="text-center py-3 text-blue-950">- No students assigned yet -</p>
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
	</x-section-container>
@endsection
