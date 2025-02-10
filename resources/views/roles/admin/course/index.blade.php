@extends("layouts.main-admin")

@section("title")
	<h1>Manage Courses</h1>
@endsection

@section('popup')
	<!-- Delete course -->
	<x-confirmation method="delete" popup_title="Delete Course" id="delete-course-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> the Course <span class="font-bold text-light-blue" id="del-course-name"></span> from Sangnila LMS? <strong>This action will erase all data related to the course and can't be undone!</strong>
	</x-confirmation>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of All Courses") }}</x-page-title>

		<div class="flex items-center mt-6">
			<div class="w-1/4">
				<x-anchor-button  href="{{ route('admin.course.create') }}"><i class="bi bi-plus-lg"></i> Add New Course</x-anchor-button>
			</div>

			<form class="flex w-1/2 justify-center" action="{{ route("admin.course.index") }}">
				<x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		@if(session()->has("successCreateNewCourse"))
			<x-badge-success badge_text="{{ session('successCreateNewCourse') }}">
			</x-badge-success>
		@elseif(session()->has("successDeleteCourse"))
			<x-badge-warning badge_text="{{ session('successDeleteCourse') }}">
			</x-badge-warning>
		@endif

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course Name</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Level</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Format</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Participants</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($courses as $course)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4">{{ $course->course_name }}</td>
							<td class="py-2 px-4">{{ ucwords($course->level) }}</td>
							<td class="py-2 px-4">{{ $course->format }}</td>
							<td class="py-2 px-4">
								<div class="w-full flex flex-col">
									<div class="">{{ $course->teachers->count() }} Teachers</div>
									<div class="">{{ $course->students->count() }} Students</div>
								</div>
							</td>
							<td class="py-2 px-4 font-semibold @if($course->status == "active") text-light-blue @else text-red @endif">{{ ucwords($course->status) }}</td>
							<td class="py-2 px-4">
								<div class="flex gap-1 w-full">
									<x-anchor-button
										href="{{ route('admin.course.show', ['course_id' => $course->id]) }}">
										<i class="bi bi-eye"></i>
									</x-anchor-button>
									<x-anchor-button
										href="{{ route('admin.course.edit', $course->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
									<x-button type="button" class="delete-course-btn" data-route="{{ route('admin.course.destroy', $course->id) }}" data-del_course_name="{{ $course->course_name }}"><i class="bi bi-trash3"></i></x-button>
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-2 px-4 text-center" colspan="5">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-6 w-full flex justify-center">
			{{ $courses->links() }}
		</div>
	</x-section-container>

	<script>
		$(document).ready(() => {
			// Delete course
			$('.delete-course-btn').on('click', function() {
				// Retrieve data and set the data to the popup
				$("#delete-course-popup").find('form').attr("action", $(this).data('route'));
				$("#del-course-name").text($(this).data('del_course_name'));

				// Show the popup
				$("#delete-course-popup").parent().show();
			});
		});
	</script>

@endsection
