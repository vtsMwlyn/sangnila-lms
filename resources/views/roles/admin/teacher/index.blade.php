@extends("layouts.main-admin")

@section("title")
	<h1>Manage Teachers</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of Active Teachers") }}</x-page-title>

		<div class="flex items-center justify-center mt-6">
			<form class="flex w-1/2 justify-center" action="{{ route("admin.teacher.index") }}">
				<x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search..." :value="request('search')"/>
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Full Name</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Assigned Courses</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Price</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($teachers as $i => $teacher)
						@forelse ($teacher->teached_courses as $j => $course)
							<tr class="@if($i % 2 == 0) bg-white @endif">
								@if($j == 0)
									<td class="py-3 px-4" rowspan="{{ $teacher->teached_courses->count() }}">
										<div class="flex w-full items-center gap-3">
											@if($teacher->details->profpic)
												<img src="{{ Storage::url("app/public/" .$teacher->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
											@else
												@if($teacher->details->gender == 1)
													<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
												@else
													<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
												@endif
											@endif
											{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
										</div>
									</td>
								@endif
								<td class="py-3 px-4">
									{{ $course->course_name }} - {{ ucwords($course->level) }}
								</td>
								<td class="py-3 px-4">
									Rp {{ number_format(App\Models\CourseTeacher::where('user_id', $teacher->id)->where('course_id', $course->id)->first()->price, 2, ',', '.') }}
								</td>
								@if($j == 0)
									<td class="py-3 px-4" rowspan="{{ $teacher->teached_courses->count() }}">
										@if ($teacher->status == "enabled")
											<span class="font-bold text-light-blue">Active</span>
										@else
											<span class="font-bold text-red">Inactive</span>
										@endif
									</td>
									<td class="py-3 px-4" rowspan="{{ $teacher->teached_courses->count() }}">
										<div class="flex gap-1 w-full">
											<div class="relative">
												<a href="{{ route('admin.teacher.show', $teacher->id) }}" title="View this teacher details">
													<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
												</a>
												@if ($teacher->teached_courses->count() == 0)
													<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
												@endif
											</div>
											<a href="{{ route('admin.teacher.edit', $teacher->id) }}" title="Edit this teacher data">
												<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</a>
										</div>
									</td>
								@endif
							</tr>
						@empty
							<tr class="@if($i % 2 == 0) bg-white @endif">
								<td class="py-3 px-4">
									<div class="flex w-full items-center gap-3">
										@if($teacher->details->profpic)
											<img src="{{ Storage::url("app/public/" . $teacher->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
										@else
											@if($teacher->details->gender == 1)
												<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
											@else
												<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
											@endif
										@endif
										{{ ($teacher->details->gender == 1)? "Mr." : "Ms." }} {{ $teacher->full_name }}
									</div>
								</td>
								<td class="py-3 px-4" colspan="2">- No courses assigned yet-</td>
								<td class="py-3 px-4">
									@if ($teacher->status == "enabled")
										<span class="font-bold text-light-blue">Active</span>
									@else
										<span class="font-bold text-red">Inactive</span>
									@endif
								</td>
								<td class="py-3 px-4">
									<div class="flex gap-1 w-full">
										<div class="relative">
											<a href="{{ route('admin.teacher.show', $teacher->id) }}" title="View this teacher details">
												<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
											</a>
											@if ($teacher->teached_courses->count() == 0)
												<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -4px; right: -4px;">!</div>
											@endif
										</div>
										<a href="{{ route('admin.teacher.edit', $teacher->id) }}" title="Edit this teacher data">
											<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
									</div>
								</td>
							</tr>
						@endforelse
					@empty
						<tr class="bg-white">
							<td class="py-3 px-4 text-center" colspan="4">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>
@endsection
