@extends("layouts.main-admin")

@section("title")
	<h1>Manage Teachers</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("List of Active Teachers") }}</x-page-title>

		<form class="flex w-full justify-center" action="{{ route("admin.teacher.index") }}">
			<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none w-full lg:w-1/3" name="search" placeholder="Search..." :value="request('search')"/>
			<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
		</form>

		<div class="overflow-x-auto mt-8">
			<x-table>
				<x-slot name="head">
					<th class="template-heads sm:w-1/4 rounded-l-xl">Full Name</th>
					<th class="template-heads sm:w-1/4">Email</th>
					<th class="template-heads sm:w-1/4">Assigned Courses</th>
					<th class="template-heads sm:w-1/4 rounded-r-xl">Actions</th>
				</x-slot>

				@forelse ($accounts as $account)
					@if($account->status == "disabled")
						@continue
					@endif
					<tr>
						<td class="template-bodies sm:w-1/4 rounded-l-xl">
							<a href="{{ route('admin.teacher.show', ['teacher_id' => $account->id]) }}" class="text-blue-200 hover:text-blue-400 font-semibold hover:underline">
								{{ ($account->details->gender == 1)? "Mr." : "Ms." }} {{ $account->full_name }}
							</a>
						</td>
						<td class="template-bodies sm:w-1/4">{{ $account->email }}</td>
						<td class="template-bodies sm:w-1/4">
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
						<td class="template-bodies sm:w-1/4 rounded-r-xl">
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
						<td class="bg-white text-center p-5 font-semibold sm:w-1/4 rounded-xl" colspan="4">{{ request("search")? "- No data found -" : "- No teachers yet -" }}</td>
					</tr>
				@endforelse
			</x-table>
		</div>
	</x-section-container>
@endsection
