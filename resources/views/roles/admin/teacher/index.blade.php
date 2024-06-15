@extends("layouts.main-admin")

@section("title")
	<h1>Manage Teachers</h1>
@endsection

@section("content")
	<x-page-title>{{ __("List of Active Teachers") }}</x-page-title>

	<form class="flex w-full justify-center" action="{{ route("admin.teacher.index") }}">
		<x-input type="text" class="border-slate-500 rounded-l-lg rounded-r-none" name="search" placeholder="Search..." />
		<x-button class="bg-white rounded-l-none rounded-r-lg border border-slate-500 text-slate-500 hover:text-white"><i class="bi bi-search"></i></x-button>
	</form>

	<div class="overflow-x-auto rounded-3xl mt-10 px-10 py-5 bg-indigo-200">
		<table class="min-w-full border-collapse sm:table text-sm" style="border-collapse: separate;
		border-spacing: 0 20px;">
			<thead>
				<tr class="bg-blue-900 text-white">
					<th class="border-blue-400 px-4 py-5 sm:w-1/4 rounded-l-xl">Full Name</th>
					<th class="border-blue-400 px-4 py-5 sm:w-1/4">Email</th>
					<th class="border-blue-400 px-4 py-5 sm:w-1/4">Assigned Courses</th>
					<th class="border-blue-400 px-4 py-5 sm:w-1/4 rounded-r-xl">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($accounts as $account)
					@if($account->status == "disabled")
						@continue
					@endif
					<tr class="bg-blue-800 text-white">
						<td class="border-blue-300 px-4 py-5 sm:w-1/4 rounded-l-xl text-center">
							<a href="{{ route('admin.teacher.show', ['teacher_id' => $account->id]) }}" class="text-blue-200 hover:text-blue-400 font-semibold hover:underline">
								{{ ($account->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ $account->full_name }}
							</a>
						</td>
						<td class="border-blue-300 px-4 py-5 sm:w-1/4 text-center">{{ $account->email }}</td>
						<td class="border-blue-300 px-4 py-5 sm:w-1/4 text-center">
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
						<td class="border-blue-300 px-4 py-5 sm:w-1/4 rounded-r-xl">
							<div class="flex w-full justify-center gap-1">
								<x-anchor-button class="bg-orange-500"
									href="{{ route('admin.teacher.show', $account->id) }}">
									<i class="bi bi-eye"></i>
								</x-anchor-button>
								<x-anchor-button class="bg-orange-500"
									href="{{ route('admin.teacher.edit', $account->id) }}">
									<i class="bi bi-pencil-square"></i>
								</x-anchor-button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td class="bg-white text-center px-4 py-5 sm:w-1/4 rounded-xl" colspan="4">- No teachers yet -</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
@endsection
