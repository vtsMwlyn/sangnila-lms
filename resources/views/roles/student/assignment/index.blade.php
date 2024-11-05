@extends("layouts.main-student")

@section("title")
	<h1>Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Select Course</span>
@endsection

@section("content")
	@forelse ($assignments_data as $ad)
		<a href="{{ route('student.assignment.show', $ad["course_student"]->course->id) }}" class="w-full transition duration-300 selectable-cards">
			<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
				<p class="font-bold text-dark-blue">{{ $ad["course_student"]->course->course_name }}</p>
				<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
				<div class="flex">
					<div class="w-1/4">Total Assignments</div>
					<div class="w-1/4">Completed Assignments</div>
					<div class="w-1/4">Pending Assignments</div>
					<div class="w-1/4 text-red">Due Soon Assignment</div>
				</div>
				<div class="flex">
					<div class="w-1/4 font-semibold">{{ $ad["status"]["total"] }}</div>
					<div class="w-1/4 font-semibold">{{ $ad["status"]["done"] }}</div>
					<div class="w-1/4 font-semibold">{{ $ad["status"]["pending"] }}</div>
					<div class="w-1/4 font-semibold text-red">{{ $ad["status"]["nearest_deadline"] }}</div>
				</div>
			</div>
		</a>
	@empty
	@endforelse

	<script>
		$(".selectable-cards").on({
			"mouseover": function(){
				$(this).css("transform", "scale(1.02)");
			},
			"mouseout": function(){
				$(this).css("transform", "scale(1)");
			}
		});
	</script>
@endsection
