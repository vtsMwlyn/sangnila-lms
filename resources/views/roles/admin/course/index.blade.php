@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("List of All Courses") }}</x-page-title>

		<form class="flex w-full justify-center" action="{{ route("admin.course.index") }}">
			<x-input type="text" class="border-slate-500 rounded-l-lg rounded-r-none" name="search" placeholder="Search..." />
			<x-button class="bg-white rounded-l-none rounded-r-lg border border-slate-500 text-slate-500 hover:text-white"><i class="bi bi-search"></i></x-button>
		</form>

		@if(session()->has("successCreateNewCourse"))
			<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
				<p class="text-green-900">{{ session("successCreateNewCourse") }}</p>
			</div>
		@elseif(session()->has("successDeleteCourse"))
			<div class="w-full bg-yellow-300 px-5 py-3 rounded-lg">
				<p class="text-yellow-600" >{{ session("successDeleteCourse") }}</p>
			</div>
		@endif

		<x-anchor-button class="bg-orange-500 mt-8" href="{{ route('admin.course.create') }}"><i class="bi bi-plus-lg"></i> Add New Course</x-anchor-button>

		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl sm:w-1/4">Course Name</th>
					<th class="template-heads sm:w-1/4">Description</th>
					<th class="template-heads sm:w-1/4">Visibility</th>
					<th class="template-heads rounded-r-xl sm:w-1/4">Actions</th>
				</x-slot>

				@if ($courses->isNotEmpty())
					@foreach ($courses as $course)
						<tr>
							<td class="template-bodies sm:w-1/4 rounded-l-xl">
								<a href="{{ route('admin.course.show', ['course_id' => $course->id]) }}"
									class="text-blue-200 hover:text-blue-400 font-semibold hover:underline block">
									{{ $course->course_name }}
								</a>
							</td>

							<td class="template-bodies sm:w-1/4">
								{{ substr($course->course_description, 0, 100) }}...
							</td>

							<td class="template-bodies sm:w-1/4">
								{{ $course->visibility }}
							</td>
							<td class="template-bodies sm:w-1/4 rounded-r-xl">
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
			</x-table>
		</div>
	</x-section-container>

@endsection
