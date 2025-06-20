@extends('layouts.main-head-of-lecturer')

@section('title')
    <h1>Courses</h1>
@endsection

@section('content')
    <x-section-container>
		<div class="flex w-full justify-between items-center">
			<div class="flex flex-col">
				<x-page-title>All Courses List</x-page-title>
			</div>
			<form class="flex justify-center" action="{{ route("head-of-lecturer.course.index") }}">
				<x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search course..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

        <div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

        @if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<div class="w-full overflow-x-auto" style="height: 60vh;">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Course Name</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Level</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Format</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Delivery Mode</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Participants</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($courses as $course)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-3 px-4">{{ $course->course_name }}</td>
							<td class="py-3 px-4">{{ ucwords($course->level) }}</td>
							<td class="py-3 px-4">{{ $course->format }} Sessions</td>
							<td class="py-3 px-4">{{ ucwords($course->delivery_mode) }}</td>
							<td class="py-3 px-4">
								<div class="w-full flex flex-col">
									<div class="">{{ $course->teachers->count() }} Teachers</div>
									<div class="">{{ $course->students->count() }} Students</div>
								</div>
							</td>
							<td class="py-3 px-4 font-semibold @if($course->status == "active") text-light-blue @else text-red @endif">{{ ucwords($course->status) }}</td>
							<td class="py-3 px-4">
								<div class="flex gap-1 w-full">
									<div class="relative">
										<a href="{{ route('head-of-lecturer.course.show', $course->id) }}" title="Check course details">
											<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
										@if($course->teachers->count() == 0 || $course->students->count() == 0 || $course->learning_outcomes->count() == 0 || $course->curriculum_topics->count() == 0)
											<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
										@endif
									</div>
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-3 px-4 text-center" colspan="7">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

    </x-section-container>
@endsection