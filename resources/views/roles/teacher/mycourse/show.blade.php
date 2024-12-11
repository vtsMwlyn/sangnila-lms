@extends("layouts.main-teacher")

@section("title")
	<h1>Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.mycourse.index') }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("successSynchronizeCurriculum"))
			<x-badge-success badge_text="{{ session('successSynchronizeCurriculum') }}"></x-badge-success>
		@elseif(session()->has("successPickFromCurriculum"))
			<x-badge-success badge_text="{{ session('successPickFromCurriculum') }}"></x-badge-success>
		@elseif(session()->has("successImportExcelTopicsAndActivities"))
			<x-badge-success badge_text="{{ session('successImportExcelTopicsAndActivities') }}"></x-badge-success>
		@elseif(session()->has("successDeleteTopic"))
			<x-badge-warning badge_text="{{ session('successDeleteTopic') }}"></x-badge-warning>
		@endif

		<p class="font-bold my-4">Description:</p>
		<p class="text-blue-950 font-semibold">{{ $course->course_description }}</p>

		<p class="font-bold mt-6">Learning Outcomes:</p>
		<div class="flex flex-col gap-1 mt-2">
			@forelse($learning_outcomes as $lo)
				<div class="flex gap-2 items-center">
					<img src="{{ asset('img/bullet.svg') }}" alt="icon" class="w-3 h-3">
					<div>LO{{ $lo->number }}: {{ $lo->title }}</div>
				</div>
			@empty
				N/A
			@endforelse
		</div>

		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<h2 class="my-4 font-extrabold text-xl text-dark-blue">Student List</h2>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-8 flex flex-wrap">
			@forelse ($course_students as $index => $cs)
				<div class="flex flex-col items-center w-1/6 mb-6">
					@if($cs->student->details->profpic)
						<img src="{{ Storage::url("app/public/" . $cs->student->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;">
					@else
						<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
					@endif
					<h1 class="text-lg font-bold text-center">{{-- explode(" ", $cs->student->full_name)[0] --}}{{ $cs->student->full_name }}</h1>
				</div>
			@empty
			@endforelse
		</div>

		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<h2 class="my-4 font-extrabold text-xl text-dark-blue">Course Topics and Activities</h2>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="flex justify-between items-stretch w-full mt-5">
			<x-anchor-button class="bg-orange-500"
				href="{{ route('teacher.mycourse.topic.create', $course->id) }}">
				<i class="bi bi-plus-lg"></i> Add new topic
			</x-anchor-button>

			<div class="flex gap-5">
				<x-anchor-button class="bg-orange-500"
					href="{{ route('teacher.mycourse.import-excel-topicandactivities', $course->id) }}">
					<i class="bi bi-file-earmark-arrow-up"></i> Import from Excel
				</x-anchor-button>

				@if($has_curriculum > 0)
					<div class="relative flex flex-col items-end dropdown-container">
						<x-button class="bg-orange-500" type="button" class="dropdown-toggler">
							<i class="bi bi-arrow-repeat"></i> Generate from Syllabus
						</x-button>
						<div class="absolute z-10 overflow-hidden bg-white top-12 w-80 rounded-3xl text-sm font-semibold flex flex-col py-2 dropdown-menu" style="display: none; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
							<a href="{{ route('teacher.mycourse.pick-course', $course->id) }}" class="hover:bg-slate-300">
								<div class="w-full px-5 py-1 text-black flex items-center gap-1"><i class="bi bi-check2-square text-slate-400"></i> Pick from Syllabus</div>
							</a>
							<form method="POST" action="{{ route('teacher.mycourse.synchronize', $course->id) }}" class="hover:bg-slate-300 grow flex items-center gap-2">
								@csrf
								<button class="w-full px-5 py-1 text-black flex items-center gap-1" onclick="return confirm('Synchronizing with topics and activity in syllabus will erase all of your posted topics and activities. Are your sure want to proceed?');">
									<i class="bi bi-arrow-repeat text-slate-400"></i> Sync with Syllabus
								</button>
							</form>
						</div>
					</div>
				@endif
			</div>
		</div>

		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic Title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activities</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody >
					@php
						$iterasus = 1;
					@endphp

					@forelse ($topics as $topic)
						@if($topic->activities->count())
							<tr class="@if($iterasus % 2 == 1) bg-white @endif">
								<td class="py-2 px-4">{{ $topic->title }}</td>

								<td class="py-2 px-4">
									<ul class="h-full w-full flex flex-col">
										@foreach ($topic->activities as $activity)
											<li>{{ $activity->title }}</li>
										@endforeach
									</ul>
								</td>

								<td class="py-2 px-4">
									@php
										$lolist = [];
										foreach ($topic->activities as $activity) {
											foreach ($activity->learning_outcomes as $leaout) {
												if (!in_array($leaout->number, $lolist)) {
													$lolist[] = $leaout->number;
												}
											}
										}

										sort($lolist);
									@endphp

									@foreach($lolist as $los)
										LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif
									@endforeach
								</td>

								<td class="py-2 px-4">
									<div class="flex w-full items-center gap-2">
										<x-anchor-button class="bg-orange-500"
											href="{{ route('teacher.mycourse.topic.show', [$course->id, $topic->id]) }}">
											<i class="bi bi-eye"></i> Details
										</x-anchor-button>
										<x-anchor-button class="bg-orange-500"
											href="{{ route('teacher.mycourse.topic.delete', [$topic->course->id, $topic->id]) }}">
											<i class="bi bi-trash3"></i> Delete
										</x-anchor-button>
									</div>
								</td>
							</tr>

							@php
								$iterasus++;
							@endphp
						@else
							<tr class="@if($iterasus % 2 == 1) bg-white @endif">
								<td class="py-2 px-4">{{ $topic->title }}</td>
								<td class="py-2 px-4">- No activities added yet to this topic -</td>
								<td class="py-2 px-4">
									<div class="flex w-full items-center gap-2">
										<x-anchor-button class="bg-orange-500"
											href="{{ route('teacher.mycourse.topic.show', [$course->id, $topic->id]) }}">
											<i class="bi bi-eye"></i> Details
										</x-anchor-button>
										<x-anchor-button class="bg-orange-500"
											href="{{ route('teacher.mycourse.topic.delete', [$topic->course->id, $topic->id]) }}">
											<i class="bi bi-trash3"></i> Delete
										</x-anchor-button>
									</div>
								</td>
							</tr>

							@php
								$iterasus++;
							@endphp
						@endif
					@empty
						<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No topics and activities added yet to this course -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>
@endsection
