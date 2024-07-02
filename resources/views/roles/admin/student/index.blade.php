@extends("layouts.main-admin")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("List of Active Students") }}</x-page-title>

		<form class="flex w-full justify-center" action="{{ route("admin.student.index") }}">
			<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none w-full lg:w-1/3" name="search" placeholder="Search..." :value="request('search')"/>
			<x-button class="bg-white rounded-l-none rounded-r-lg border-blue-900 border-t-2 border-r-2 border-b-2 text-blue-900 hover:text-white"><i class="bi bi-search"></i></x-button>
		</form>

		<div class="overflow-x-auto mt-8">
			<x-table>
				<x-slot name="head">
					<th class="template-heads sm:w-1/4 rounded-l-xl">Student Name</th>
					<th class="template-heads sm:w-1/4">Email</th>
					<th class="template-heads sm:w-1/4" >Enrolled Courses & Progress</th>
					<th class="template-heads sm:w-1/4 rounded-r-xl">Actions</th>
				</x-slot>

				@if ($students->isNotEmpty())
					@foreach ($students as $index1 => $student)
						<tr>
							<td class="template-bodies sm:w-1/4 rounded-l-xl text-center">
								<a href="{{ route('admin.student.show', ['student_id' => $student->id]) }}"
									class="text-blue-200 hover:text-blue-400 font-semibold hover:underline">
									{{ $student->full_name }}
								</a>
								@if($student->status == "disabled")
									<span class="text-red-500">(Disabled)</span>
								@endif
							</td>
							<td class="template-bodies sm:w-1/4 text-center">
								{{ $student->email }}
							</td>
							<td class="template-bodies sm:w-1/4">
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
														<div class="@if(($current_attendances[$index1][$index2] + 1) % $max_attendances[$index1][$index2] == 0 || $current_attendances[$index1][$index2] >= $max_attendances[$index1][$index2]) bg-red-600 @else bg-green-700 @endif h-full" style="width: {{ $percentages[$index1][$index2] }}%;"></div>
													</div>
												</div>
											</li>
										@endforeach
									</ul>
								@else
									<p class="text-center">- No courses assigned yet -</p>
								@endif
							</td>
							<td class="template-bodies sm:w-1/4 rounded-r-xl">
								<div class="flex w-full justify-center gap-1">
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
					@endforeach
				@else
					<tr class="bg-white">
						<td colspan="4" class="rounded-xl text-center p-5 sm:w-1/4">
							- No students yet -
						</td>
					</tr>
				@endif
			</x-table>
		</div>
	</x-section-container>
@endsection
