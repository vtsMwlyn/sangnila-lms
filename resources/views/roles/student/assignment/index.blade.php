@extends("layouts.main-student")

@section("title")
	<h1>My Assignments</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Select Course</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>My Assignments</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-4 text-center">Pick a Course</h1>
		<div class="overflow-x-auto rounded-md">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-xl">Course Name</th>
				</x-slot>
				@if ($courseStudents->count())
					@foreach ($courseStudents as $cs)
						<tr>
							<td class="template-bodies rounded-xl selections transition ease-in-out duration-500" style="cursor: url('{{ asset('img/cursor2.cur') }}'), pointer; padding: 0;">
								<div class="flex w-full h-full items-stretch p-5">
									<a href="{{ route('student.assignment.show', $cs->course->id) }}" class="font-bold h-full w-full">
										{{ $cs->course->course_name }}
									</a>
								</div>
							</td>
						</tr>
					@endforeach
				@else
					<tr><td class="bg-white rounded-xl p-5 font-semibold text-center">- No courses assigned yet -</td></tr>
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
	</x-section-container>
@endsection
