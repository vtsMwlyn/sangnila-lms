@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("List of All Courses") }}</x-page-title>

		<form class="flex w-full justify-center" action="{{ route("admin.course.index") }}">
			<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none w-full lg:w-1/3" name="search" placeholder="Search..." :value="request('search')"/>
			<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
		</form>

		@if(session()->has("successCreateNewCourse"))
			<x-badge-success badge_text="{{ session('successCreateNewCourse') }}">
			</x-badge-success>
		@elseif(session()->has("successDeleteCourse"))
			<x-badge-warning badge_text="{{ session('successDeleteCourse') }}">
			</x-badge-warning>
		@endif

		<div>
			<x-anchor-button class="bg-orange-500 mt-8" href="{{ route('admin.course.create') }}"><i class="bi bi-plus-lg"></i> Add New Course</x-anchor-button>
		</div>

		<div class="overflow-x-auto mt-5">
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
						<td colspan="4" class="bg-white rounded-xl p-5 sm:w-1/4 text-center font-semibold">
							{{ request("search")? "- No data found -" : "- No courses yet -" }}
						</td>
					</tr>
				@endif
			</x-table>
		</div>
	</x-section-container>

@endsection
