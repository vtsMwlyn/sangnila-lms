@extends("layouts.main-admin")

@section("title")
	<h1>Manage Teachers</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Teachers</h1>

	<div class="overflow-x-auto rounded-md">
		<table class="min-w-full bg-white border-collapse">
			<thead>
				<tr>
					<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Full Name</th>
					<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Email</th>
					<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Assigned Courses</th>
					<th class="bg-blue-300 border-b border-blue-400 px-4 py-2 sm:w-1/4">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($accounts as $account)
					@if($account->status == "disabled")
						@continue
					@endif
					<tr>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
							<a href="{{ route('admin.teacher.show', ['teacher_id' => $account->id]) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
								{{ $account->full_name }}
							</a>
						</td>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">{{ $account->email }}</td>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4 text-center">
							{{-- {{ $account->teached_courses }} --}}
							@if($account->teached_courses->count())
								<ul class="flex flex-col items-center">
									@foreach ($account->teached_courses as $course)
										<li>{{ $course->course_name }}</li>
									@endforeach
								</ul>
							@else
								- No courses assigned yet -
							@endif
						</td>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
							<div class="flex w-full justify-center gap-1">
								<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
									href="{{ route("admin.teacher.show", $account->id) }}">
									View
								</a>
								<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
									href="{{ route("admin.teacher.edit", $account->id) }}">
									Edit
								</a>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4" colspan="3">N/A</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
@endsection
