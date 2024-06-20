@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<x-page-title>{{ __("List of All Courses") }}</x-page-title>

	@if(session()->has("successCreateNewCourse"))
		<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
			<p class="text-green-900">{{ session("successCreateNewCourse") }}</p>
		</div>
	@elseif(session()->has("successDeleteCourse"))
		<div class="w-full bg-yellow-300 px-5 py-3 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDeleteCourse") }}</p>
		</div>
	@endif

	<div class="flex flex-col-reverse md:flex-row gap-5 md:gap-0 justify-between items-center mt-5">
		<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.create') }}"><i class="bi bi-plus-lg"></i> Add New Course</x-anchor-button>
		<form class="flex" action="{{ route("admin.course.index") }}">
			<x-input type="text" class="border-slate-500 rounded-l-lg rounded-r-none" name="search" placeholder="Search..." />
			<x-button class="bg-white rounded-l-none rounded-r-lg border border-slate-500 text-slate-500 hover:text-white"><i class="bi bi-search"></i></x-button>
		</form>
	</div>

	<div class="overflow-x-auto rounded-3xl mt-7 px-10 py-5 bg-indigo-200">
		<table class="min-w-full border-collapse sm:table text-sm" style="border-collapse: separate;
		border-spacing: 0 20px;">
			<thead>
				<tr class="bg-blue-900">
					<th class="text-white py-5 rounded-l-xl border-blue-400 font-bold px-4 sm:w-1/4">Course Name</th>
					<th class="text-white py-5 border-blue-400 font-bold px-4 sm:w-1/4">Description</th>
					<th class="text-white py-5 border-blue-400 font-bold px-4 sm:w-1/4">Visibility</th>
					<th class="text-white py-5 rounded-r-xl border-blue-400 font-bold px-4 sm:w-1/4">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if ($courses->isNotEmpty())
					@foreach ($courses as $course)
						<tr class="bg-blue-800 text-white">
							<td class="border-blue-300 px-4 py-5 sm:w-1/4 rounded-l-xl text-center">
								<a href="{{ route('admin.course.show', ['course_id' => $course->id]) }}"
									class="text-blue-200 hover:text-blue-400 font-semibold hover:underline block">
									{{ $course->course_name }}
								</a>
							</td>

							<td class="border-blue-300 px-4 py-5 sm:w-1/4 text-center">
								{{ substr($course->course_description, 0, 100) }}...
							</td>

							<td class="border-blue-300 px-4 py-5 sm:w-1/4 text-center">
								{{ $course->visibility }}
							</td>
							<td class="border-blue-300 px-4 py-5 sm:w-1/4 text-center rounded-r-xl">
								<div class="flex gap-1 w-full justify-center">
									<x-anchor-button class="bg-orange-500"
										href="{{ route('admin.course.show', ['course_id' => $course->id]) }}">
										<i class="bi bi-eye"></i>
									</x-anchor-button>
									<x-anchor-button class="bg-orange-500"
										href="{{ route('admin.course.edit', $course->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
									<x-anchor-button class="bg-orange-500"
										href="{{ route('admin.course.delete', $course->id) }}">
										<i class="bi bi-trash3"></i>
									</x-anchor-button>
								</div>
							</td>

						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="4" class="bg-white rounded-xl px-4 py-5 sm:w-1/4 text-center">
							- No courses yet -
						</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>

@endsection
