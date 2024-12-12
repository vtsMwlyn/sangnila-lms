@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of Active Students") }}</x-page-title>

		<div class="flex items-center mt-6">
			<div class="w-1/4">
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.student.import-excel') }}"><i class="bi bi-file-earmark-arrow-up"></i> Import From Excel</x-anchor-button>
			</div>

			<form class="flex w-1/2 justify-center" action="{{ route("admin.student.index") }}">
				<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		@if(session()->has("successImportExcelStudent"))
			<x-badge-success badge_text="{{ session('successImportExcelStudent') }}" class="mb-4"></x-badge-success>
		@endif

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Student Name</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Enrolled Courses & Progress</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
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
							<td class="py-2 px-4">
								@if ($student	->status == "enabled")
									<span class="font-bold text-light-blue">Active</span>
								@else
									<span class="font-bold text-red">Inactive</span>
								@endif
							</td>
							<td class="py-2 px-4">
								<div class="flex w-full justify-start gap-1">
									<x-anchor-button class="bg-orange-500"
									href="{{ route('admin.student.show', $student->id) }}">
										<i class="bi bi-eye"></i>
									</x-anchor-button>
									<x-anchor-button class="bg-orange-500"
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
@endsection
