@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">List of Assignments in {{ $course->course_name }}</x-page-title>

		@if(session()->has("successUploadAssignment"))
			<x-badge-success badge_text="{{ session('successUploadAssignment') }}"></x-badge-success>
		@elseif(session()->has("successEditAssignment"))
			<x-badge-success badge_text="{{ session('successEditAssignment') }}"></x-badge-success>
		@elseif(session()->has("successDeleteAssignment"))
			<x-badge-warning badge_text="{{ session('successDeleteAssignment') }}"></x-badge-warning>
		@endif

		<div class="mt-5 mb-5">
			<x-anchor-button class="bg-orange-500"
				href="{{ route('teacher.assignment.upload', $course->id) }}">
				<i class="bi bi-plus-lg"></i> Upload New Assignment
			</x-anchor-button>
		</div>

		<div class="overflow-x-auto rounded-md">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Title</th>
					<th class="template-heads">Description</th>
					<th class="template-heads">Deadline</th>
					<th class="template-heads">Download link</th>
					{{-- <th class="template-heads">Assigned to</th> --}}
					<th class="template-heads rounded-r-xl">Actions</th>
				</x-slot>

				@if ($assignments->count())
					@foreach($assignments as $asg)
						<tr>
							<td class="template-bodies rounded-l-xl">
								<a class="text-blue-200 hover:text-blue-400 hover:underline font-bold" href="{{ route("teacher.assignment.check", $asg->id) }}">{{ $asg->title }}</a>
							</td>
							<td class="template-bodies">{{ $asg->desc }}</td>
							<td class="template-bodies">{{ $asg->deadline_date }}<br>{{ $asg->deadline_time }}</td>
							<td class="template-bodies text-blue-600">
								<a href="{{ $asg->link }}" class="text-blue-200 hover:text-blue-400 hover:underline font-bold">{{ $asg->link }}</a>
							</td>
							{{-- <td class="template-bodies">{{ $asg->assigned_to->full_name }}</td> --}}
							{{-- <td class="template-bodies">
								<ul>
									@for ($j = $i; $assignments[$j]->title == $assignments[$j + 1]->title; $j++)
										<li>{{ $assignments[$j]->assigned_to->full_name }}</li>
									@endfor
								</ul>
							</td> --}}
							<td class="template-bodies rounded-r-xl">
								<div class="flex gap-1">
									<x-anchor-button class="bg-orange-500" href="{{ route('teacher.assignment.edit', $asg->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
									<x-anchor-button class="bg-orange-500" href="{{ route('teacher.assignment.delete', $asg->id) }}">
										<i class="bi bi-trash3"></i>
									</x-anchor-button>
								</div>
							</td>
						</tr>
					@endforeach
				@else
					<tr ><td colspan="5" class="bg-white rounded-xl p-5 font-semibold text-center">- No assignments yet -</td></tr>
				@endif

			</x-table>
		</div>
	</x-section-container>
@endsection
