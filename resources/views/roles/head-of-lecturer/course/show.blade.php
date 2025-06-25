@extends("layouts.main-head-of-lecturer")

@section("title")
	<h1>Course Details</h1>
@endsection

@section("content")
	<x-section-container class="mb-10">
		{{-- Page title --}}
		<x-back-button href="{{ route('head-of-lecturer.course.index') }}"></x-back-button>
		<div class="flex items-center justify-between">
			<x-page-title style="margin-bottom: 0;">{{ $course->course_name }}</x-page-title>
			<x-anchor-button href="{{ route('head-of-lecturer.course.syllabus-download', $course->id) }}"><i class="bi bi-download"></i> Download Syllabus</x-anchor-button>
		</div>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		{{-- Flash messages --}}
		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		{{-- Course informations --}}
		<div class="w-full flex flex-col gap-y-4">
			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Course Name</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ $course->course_name }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Status</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400 font-bold" style="border-width: 3px">{{ ucwords($course->status) }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Level</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ ucwords($course->level) }}</div>
				</div>

				<div class="flex flex-col w-1/2">
					<p>Format</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ $course->format }}</div>
				</div>
			</div>

			<div class="w-full flex gap-x-4">
				<div class="flex flex-col w-1/2">
					<p>Delivery Mode</p>
					<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{{ ucwords($course->delivery_mode) }}</div>
				</div>

				<div class="flex flex-col w-1/2"></div>
			</div>

			<div class="flex flex-col w-full">
				<p>Course Description</p>
				<div class="w-full px-4 py-2 mt-1 rounded-2xl bg-white border-slate-400" style="border-width: 3px">{!! nl2br($course->course_description) !!}</div>
			</div>
		</div>

		{{-- Learning Outcomes --}}
		<div class="w-full bg-slate-400 mt-12" style="height: 2px;"></div>
		<div class="flex items-center justify-between">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Learning Outcomes</h2>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="w-full overflow-x-auto my-6">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">#</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Outcome Title</th>
				</thead>
				<tbody>
					@forelse ($learning_outcomes as $index => $lo)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-3 px-4">{{ $lo->number }}</td>
							<td class="py-3 px-4">{{ $lo->title }}</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="p-5 text-center" colspan="3">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- Assigned teachers and students --}}
		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="my-3 flex items-center justify-between">
			<h2 class="font-extrabold text-xl text-dark-blue">List of Assigned Teachers</h2>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-8 flex flex-wrap">
			@forelse ($course->teachers as $index => $teacher)
				<div class="w-1/6 mb-6">
					<div class="flex flex-col items-center">
						@if($teacher->details->profpic)
							<img src="{{ Storage::url("app/public/" . $teacher->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
						@else
							@if($teacher->details->gender == 1)
								<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@else
								<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@endif
						@endif
						<h1 class="text-lg font-bold text-center">{{-- explode(" ", $teacher->full_name)[0] --}}{{ ($teacher->details->gender == 1)? 'Mr.' : "Ms." }} {{ $teacher->full_name }}</h1>
					</div>
				</div>
			@empty
				- No teachers assigned to this course yet -
			@endforelse
		</div>

		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="flex w-full justify-between items-center">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Student List</h2>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="mt-8 flex flex-wrap">
			@forelse ($course->students as $index => $student)
				<div class="w-1/6 mb-6">
					<div class="flex flex-col items-center">
						@if($student->details->profpic)
							<img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
						@else
							@if($student->details->gender == 1)
								<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@else
								<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
							@endif
						@endif
						<h1 class="text-lg font-bold text-center">{{-- explode(" ", $student->full_name)[0] --}}{{ $student->full_name }}</h1>
					</div>
				</div>
			@empty
				- No students assigned yet to this course -
			@endforelse
		</div>

		{{-- Curriculum --}}
		<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
		<div class="flex w-full justify-between items-center">
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Syllabus/Curriculum</h2>
		</div>
		<div class="w-full bg-slate-400 " style="height: 2px;"></div>

		<div class="overflow-x-auto mt-3">
			<table class="w-full">
				<thead>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activities</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
				</thead>

				@forelse ($course->curriculum_topics as $topic)
					<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
						<td class="py-3 px-4 text-center">
							@php
								if($topic->curriculum_activities->count()){
									echo $topic->curriculum_activities->min('session') . '-' . $topic->curriculum_activities->max('session');
								} else {
									echo 'N/A';
								}
							@endphp
						</td>
						<td class="py-3 px-4 w-1/4">
							{{ $topic->title }}
						</td>
						<td class="py-3 px-4" style="text-align: start">
							@if($topic->curriculum_activities->count())
								<ul class="list-disc list-inside">
									@foreach ($topic->curriculum_activities()->orderBy('session')->get() as $activity)
										<li class="mb-2 relative">
                                            @if($activity->link)
                                                <a href="{{ $activity->link }}" target="_blank" class="text-blue-600 hover:underline font-bold">{{ $activity->title }}</a>
                                            @else
                                                <span class="text-slate-600 font-bold">{{ $activity->title }}</span>
                                                <div class="h-6 w-6 rounded-full bg-red absolute text-white flex items-center justify-center" style="top: 0; right: -4px;">!</div>
                                            @endif
                                        </li>
									@endforeach
								</ul>
							@else
								- No curriculum activities yet -
							@endif
						</td>
						<td class="py-3 px-4">
							@php
								$lolist = [];
								foreach ($topic->curriculum_activities as $activity) {
									foreach ($activity->learning_outcomes as $leaout) {
										if (!in_array($leaout->number, $lolist)) {
											$lolist[] = $leaout->number;
										}
									}
								}

								sort($lolist);
							@endphp

							@forelse($lolist as $los)
								<div class="w-full text-center">LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif</div>
							@empty
								<div class="w-full text-center">N/A</div>
							@endforelse
						</td>
					</tr>
				@empty
					<tr><td colspan="5" class="text-center p-5 bg-white">- No curriculum topics and activities yet -</td></tr>
				@endforelse
			</table>
		</div>
	</x-section-container>

@endsection
