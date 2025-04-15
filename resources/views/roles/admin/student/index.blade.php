@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of Active Students") }}</x-page-title>

		{{-- Filter --}}
		<form action="{{ route("admin.student.index") }}" id="filter-form">
		</form>

		<div class="flex items-center justify-between gap-5 mt-5 w-full">
			<x-anchor-button class="w-56" href="{{ route('admin.student.import-excel') }}"><i class="bi bi-file-earmark-arrow-up"></i> Import From Excel</x-anchor-button>

			<form class="flex w-1/2 justify-center" action="{{ route("admin.student.index") }}">
				<x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>

			<x-select id="filter-course" class="w-56" value="{{ request('course_name') }}">
				<option value="">All</option>
				@forelse(App\Models\Course::where("status", "active")->get() as $c)
					<option value="{{ $c->id }}" @if(request('course') == $c->id) selected @endif>{{ $c->course_name }}</option>
				@empty
				@endforelse
			</x-select>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<div class="w-full overflow-x-auto" style="max-height: 500px;">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student Name</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Enrolled Courses & Progress</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Periods Paid</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($students as $index1 => $student)
						@php
							$courseStudents = $student->course_students;
						@endphp

						@forelse($courseStudents as $index2 => $cs)
							<tr class="@if($index1 % 2 == 0) bg-white @endif">
								@if($index2 == 0)
									<td class="py-3 px-4 w-1/4" rowspan="{{ $courseStudents->count() }}">
										<div class="flex w-full items-center gap-3">
											@if($student->details->profpic)
												<img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
											@else
												<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
											@endif
											{{ $student->full_name }}
										</div>
									</td>
								@endif
								<td class="py-3 px-4">
									<div class="flex justify-between gap-3 items-center">
										<div class="w-2/3">
											<span>{{ $cs->course->course_name }} - {{ ucwords($cs->course->level) }}</span>
										</div>
										<div class="w-1/3">
											<div class="w-full bg-gray-200 rounded-lg h-4 overflow-hidden relative">
												<div class="absolute w-full h-full
													@if((($current_attendances[$index1][$index2] + 1) % $max_attendances[$index1][$index2] == 0 || $current_attendances[$index1][$index2] >= $max_attendances[$index1][$index2]) && $current_attendances[$index1][$index2] != 0 && $cs->learning_status != 'complete') 
														text-red-100
													@else 
														text-green-950
													@endif flex justify-center items-center font-semibold">
													{{ __($current_attendances[$index1][$index2] . "/" . $max_attendances[$index1][$index2]) }}
												</div>
												<div class="h-full 
													@if((($current_attendances[$index1][$index2] + 1) % $max_attendances[$index1][$index2] == 0 || $current_attendances[$index1][$index2] >= $max_attendances[$index1][$index2]) && $cs->learning_status != 'complete')
														bg-red
													@else 
														bg-green-600
													@endif" style="width: {{ $percentages[$index1][$index2] }}%;">
												</div>
											</div>
										</div>
									</div>
								</td>
								<td class="py-3 px-4 text-center">
									{{ ($cs->temp_periods_paid ?? 0) + App\Models\Receipt::where('course_student_id', $cs->id)->get()->count() }}
								</td>
								<td class="py-3 px-4 text-center">
									@if($cs->learning_status == 'learning')
										<span class="font-bold text-light-blue">Learning</span>
									@elseif($cs->learning_status == 'complete')
										<span class="font-bold text-green-600">Complete</span>
									@else
										<span class="font-bold text-red">Undone</span>
									@endif
								</td>
								@if($index2 == 0)
									<td class="py-3 px-4" rowspan="{{ $courseStudents->count() }}">
										<div class="flex w-full justify-start gap-1">
											<a href="{{ route('admin.student.show', $student->id) }}" title="View this student details">
												<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</a>
											<a href="{{ route('admin.student.edit', $student->id) }}" title="Edit this student data">
												<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</a>
										</div>
									</td>
								@endif
							</tr>
						@empty
							<tr class="@if($index1 % 2 == 0) bg-white @endif">
								<td class="py-3 px-4 w-1/4">
									<div class="flex w-full items-center gap-3">
										@if($student->details->profpic)
											<img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
										@else
											<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
										@endif
										{{ $student->full_name }}
									</div>
								</td>
								<td class="py-3 px-4 text-center" colspan="3">- No courses enrolled -</td>
								<td class="py-3 px-4">
									<div class="flex w-full justify-start gap-1">
										<div class="relative">
											<x-anchor-button
											href="{{ route('admin.student.show', $student->id) }}">
												<i class="bi bi-eye"></i>
											</x-anchor-button>
											<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
										</div>
										<x-anchor-button
											href="{{ route('admin.student.edit', $student->id) }}">
											<i class="bi bi-pencil-square"></i>
										</x-anchor-button>
									</div>
								</td>
							</tr>
						@endforelse
					@empty
						<tr class="bg-white">
							<td class="py-3 px-4 text-center" colspan="5">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>

	<script>
		$(document).ready(() => {
			$('#search-btn').click(() => {
				const form = $('#filter-form');
				form.html('');

				form.append($("<input>").attr({"type": "hidden", "name": "course", "value": $('#filter-course').val()}));
				form.append($("<input>").attr({"type": "hidden", "name": "search", "value": $('#search-name').val()}));

				form.submit();
			});
		});
	</script>
@endsection
