@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of Active Students") }}</x-page-title>

		<!-- Filter -->
		<form action="{{ route("admin.student.index") }}" id="filter-form">
		</form>

		<div class="flex items-center justify-between gap-5 mt-5 w-full">
			<x-anchor-button class="w-56" href="{{ route('admin.student.import-excel') }}"><i class="bi bi-file-earmark-arrow-up"></i> Import From Excel</x-anchor-button>

			<div class="flex w-1/2 items-stretch">
				<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none w-full" placeholder="Search..." :value="request('search')" id="search-name"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;" id="search-btn"><i class="bi bi-search"></i></button>
			</div>

			<x-select id="filter-course" class="w-56" value="{{ request('course_name') }}">
				<option value="">All</option>
				@forelse(App\Models\Course::where("status", "active")->get() as $c)
					<option value="{{ $c->id }}" @if(request('course') == $c->id) selected @endif>{{ $c->course_name }}</option>
				@empty
				@endforelse
			</x-select>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		@if(session()->has("successImportExcelStudent"))
			<x-badge-success badge_text="{{ session('successImportExcelStudent') }}" class="mb-4"></x-badge-success>
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
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4 w-1/4">
								{{ $student->full_name }}
							</td>
							<td class="py-2 px-4">
								@if($student->enrolled_courses->count())
									<ul>
										@foreach ($student->enrolled_courses as $index2 => $course)
											<li class="flex justify-between gap-3 items-center my-2">
												<div class="w-2/3">
													<span>{{ $course->course_name }}</span>
												</div>
												<div class="w-1/3">
													<div class="w-full bg-gray-200 rounded-lg h-4 overflow-hidden relative">
														<div class="absolute w-full h-full @if((($current_attendances[$index1][$index2] + 1) % $max_attendances[$index1][$index2] == 0 || $current_attendances[$index1][$index2] >= $max_attendances[$index1][$index2]) && $current_attendances[$index1][$index2] != 0) text-red-100 @else text-green-950 @endif  flex justify-center items-center font-semibold">
															{{ __($current_attendances[$index1][$index2] . "/" . $max_attendances[$index1][$index2]) }}
														</div>
														<div class="@if(($current_attendances[$index1][$index2] + 1) % $max_attendances[$index1][$index2] == 0 || $current_attendances[$index1][$index2] >= $max_attendances[$index1][$index2]) bg-red @else bg-green-700 @endif h-full" style="width: {{ $percentages[$index1][$index2] }}%;"></div>
													</div>
												</div>
											</li>
										@endforeach
									</ul>
								@else
									<p class="text-center">- No courses assigned yet -</p>
								@endif
							</td>
							<td class="py-2 px-4 text-center">
								@if($student->enrolled_courses->count())
									@php
										$paid_periods = [];

										foreach($student->course_students as $cs){
											$receipts = App\Models\Receipt::where('course_student_id', $cs->id)->get();

											array_push($paid_periods, $receipts->count());
										}
									@endphp
									<ul>
										@foreach ($paid_periods as $paidp)
											<li>{{ $paidp }}</li>
										@endforeach
									</ul>
								@else
									N/A
								@endif
							</td>
							<td class="py-2 px-4 text-center">
								@if ($student->status == "enabled")
									<span class="font-bold text-light-blue">Active</span>
								@else
									<span class="font-bold text-red">Inactive</span>
								@endif
							</td>
							<td class="py-2 px-4">
								<div class="flex w-full justify-start gap-1">
									<x-anchor-button
									href="{{ route('admin.student.show', $student->id) }}">
										<i class="bi bi-eye"></i>
									</x-anchor-button>
									<x-anchor-button
										href="{{ route('admin.student.edit', $student->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
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
