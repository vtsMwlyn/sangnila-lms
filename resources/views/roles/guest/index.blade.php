@extends("layouts.main-guest")

@section("title")
	<h1>Our Courses</h1>
@endsection

@section("content")
	<div class="flex flex-wrap gap-5 w-full">
		@forelse ($courses as $i => $course)
			<a href="{{ route('guest.show', ['course_id' => $course->id]) }}" class="w-full max-h-[250px] xl:w-[48%] overflow-hidden rounded-3xl course-card transition duration-300 hover:scale-[102%] flex items-stretch" data-course-name="{{ $course->course_name }}" style="background-color: #FEFEFEB2;">
				<img class="w-1/3 course-bg" style="object-fit: cover; object-position: center;"/>
				<div class="w-2/3 p-5">
					<div class="w-full flex items-center justify-between">
						<p class="font-bold text-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
						@if($top_courses[0] == $course->id)
							<span class="bg-red text-white text-base rounded-lg px-2 py-0.5 font-normal">HOT!</span>
						@elseif(in_array($course->id, $top_courses))
							<span class="bg-light-blue text-white text-base rounded-lg px-2 py-0.5 font-normal">Popular</span>
						@endif
					</div>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>
					<div class="overflow-y-auto" style="height: 100px;">{{ $course->course_description }}</div>
					<div class="w-full flex flex-col mt-4">
						<div class="flex gap-2">
							<i class="bi bi-person-fill"></i>
							<span>{{ App\Models\CourseStudent::where("course_id", $course->id)->count() }} Students Enrolled</span>
						</div>
						<div class="flex gap-2">
							<i class="bi bi-person-fill"></i>
							<span>{{ App\Models\CourseTeacher::where("course_id", $course->id)->count() }} Teachers Teaching</span>
						</div>
					</div>
				</div>
			</a>
		@empty
			<div class="text-blue-900">N/A</div>
		@endforelse
	</div>

	<script src="{{ asset('js/custom-script-guest.js') }}"></script>

@endsection
