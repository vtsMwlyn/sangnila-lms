@extends("layouts.main-admin")

@section("title")
	<h1>{{ $student->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.student.show', $student->id) }}" class="font-bold text-yellow-500">{{ $student->full_name }}</a>
	> <span>Assign</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Assign Student to Course") }}</x-page-title>

		<div class="rounded-xl py-5 px-10 mt-10 text-white bg-blue-950">Select a Course to Assign</div>
		<div class="mt-5">
			@if(count($courses))
				<form action="{{ route('admin.student.assign.store', $student->id) }}" method="post" id="student_assign_form">
					@csrf
					<!-- Select course -->
					<div class="mt-3">
						<x-label for="course_name" :value="__('Select a course to assign')" style="color: white;"/>
						<x-select name="course_name" id="course_name" class="mt-1 w-full">
							<option disabled selected>Pick a course</option>
							@foreach ($courses as $course)
								<option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
							@endforeach
						</x-select>
					</div>

					<!-- Max Course Sessions -->
					<div class="mt-3 flex gap-3">
						<div class="w-1/2">
							<x-label for="teacher_name" :value="__('Select a teacher that will teach the student')" style="color: white;"/>
							<x-select name="teacher_name" id="teacher_name" class="mt-1 w-full">
								<option disabled selected>Pick a teacher</option>
							</x-select>
						</div>
						<div class="w-1/2">
							<x-label for="max_course_session" :value="__('Maximum sessions in this course')" style="color: white;"/>
							<x-input id="max_course_session" class="block mt-1 w-full" type="number" name="max_course_session" placeholder="Maximum sessions"  />
						</div>
					</div>

					<div class="flex w-full justify-center mt-8 items-center gap-2">
						<x-button class="bg-orange-500 w-full md:w-1/6">
							{{ __('Assign') }}
						</x-button>
						<x-cancel-button msg="The filled in data will be discarded, are you sure want to cancel?" class="w-full md:w-1/6">
							Cancel
						</x-cancel-button>
					</div>
				</form>

				<div class="rounded-lg py-5 px-10 bg-blue-900" id="when_empty" style="display: none;">
					<p class="text-white italic">- No more courses to assign -</p>
					<x-button type="button" onclick="history.back()" class="bg-orange-500 mt-4">
						Return
					</x-button>
				</div>

				<script>
					const course_and_teachers = @json($course_and_teachers);
					const selectedCourse = document.querySelector("#course_name");

					course_and_teachers.forEach(element => {
						if(element.teachers.length == 0){
							$("#course_name").find(`option[value="${element.course_name}"]`).remove();
						}
					});

					if($("#course_name").children().length == 1){
						$("#student_assign_form").css({"display": "none"});
						$("#when_empty").css({"display": "block"});
					}

					selectedCourse.addEventListener("change", () => {
						const selectedCourseName = selectedCourse.value;
						console.log(selectedCourseName);
						const teacherList = document.querySelector("#teacher_name");

						teacherList.innerHTML = '';

						const course = course_and_teachers.find(c => c.course_name === selectedCourseName);

						if(course) {
							course.teachers.forEach(teacher => {
								const newOption = document.createElement("option");
								newOption.setAttribute("value", teacher);
								newOption.innerText = teacher;
								teacherList.appendChild(newOption);
							});
						}
					});

				</script>
			@else
				<div class="rounded-lg py-5 px-10 bg-blue-800">
					<p class="text-white italic">- No more courses to assign -</p>
					<x-button type="button" onclick="history.back()" class="bg-slate-600 mt-4">
						Return
					</x-button>
				</div>
			@endif
		</div>

		<div class="rounded-xl py-5 px-10 mt-16 text-white bg-blue-950">Courses Already Assigned to This Student</div>
		<div class="mt-3">
			<div class="flex gap-x-10 flex-wrap">
				@forelse ($student->enrolled_courses as $course)
					<div class="text-white border-2 border-white bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 200px; min-height: 100px; max-height: 100px;">
						{{ $course->course_name }}
					</div>
				@empty
					<div class="font-semibold mt-3 text-center p-5 rounded-xl w-full bg-white">- No courses assigned yet -</div>
				@endforelse
			</div>
		</div>
	</x-section-container>
@endsection
