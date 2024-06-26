@extends("layouts.main-teacher")

@section("title")
	<h1>Student Progress</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">Manage Students</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 text-center mb-8">Pick a Course</h1>
		<div class="flex flex-col">
			<div class="border-t border-l border-r border-blue-950 bg-slate-300 rounded-t-xl w-full">
				<h2 class="text-xl text-center font-semibold my-2 text-blue-950">Course Name</h2>
			</div>
			<div class="border border-blue-950 bg-slate-300 rounded-b-xl w-full flex justify-center">
				<ul class="list-disc my-5">
					@if ($courses->isNotEmpty())
						@foreach ($courses as $course)
							<li class="text-blue-950 font-semibold">
								<a href="{{ route('teacher.student.select-student', $course->id) }}" class="hover:underline">
									{{ $course->course_name }}
								</a>
							</li>
						@endforeach
					@else
						<tr class="text-blue-900"><td>N/A</td></tr>
					@endif
				</ul>
			</div>
		</div>
	</x-section-container>
@endsection
