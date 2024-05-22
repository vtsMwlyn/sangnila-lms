@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">List of Assignments in {{ $course->course_name }}</h1>

	<div class="mt-5 mb-5">
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
			href="{{ route('teacher.assignment.upload', $course->id) }}">
			Upload New Assignment
		</a>
	</div>

	@if(session()->has("successUploadAssignment"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUploadAssignment") }}</p>
		</div>
	@elseif(session()->has("successEditAssignment"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successEditAssignment") }}</p>
		</div>
	@elseif(session()->has("successDeleteAssignment"))
		<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDeleteAssignment") }}</p>
		</div>
	@endif

	<div class="overflow-x-auto rounded-md">
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="border px-3">Title</th>
					<th class="border px-3">Description</th>
					<th class="border px-3">Deadline</th>
					<th class="border px-3">Download link</th>
					{{-- <th class="border px-3">Assigned to</th> --}}
					<th class="border px-3">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if (count($assignments))
					@foreach($assignments as $asg)
						<tr>
							<td class="border px-3"><a class="text-blue-700 font-bold" href="{{ route("teacher.assignment.check", $asg->id) }}">{{ $asg->title }}</a></td>
							<td class="border px-3">{{ $asg->desc }}</td>
							<td class="border px-3">{{ $asg->deadline_date }}<br>{{ $asg->deadline_time }}</td>
							<td class="border px-3 text-blue-600"><a href="{{ $asg->link }}">{{ $asg->link }}</a></td>
							{{-- <td class="border px-3">{{ $asg->assigned_to->full_name }}</td> --}}
							{{-- <td class="border px-3">
								<ul>
									@for ($j = $i; $assignments[$j]->title == $assignments[$j + 1]->title; $j++)
										<li>{{ $assignments[$j]->assigned_to->full_name }}</li>
									@endfor
								</ul>
							</td> --}}
							<td class="border px-3">
								<div class="flex gap-1">
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
										href="{{ route("teacher.assignment.edit", $asg->id) }}">
										Edit
									</a>
									<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
										href="{{ route("teacher.assignment.delete", $asg->id) }}">
										Delete
									</a>
								</div>
							</td>
						</tr>
					@endforeach
				@else
					<tr ><td colspan="5" class="border px-3 text-center">- No assignments yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
