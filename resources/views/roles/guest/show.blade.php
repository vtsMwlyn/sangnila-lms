@extends("layouts.main-guest")

@section("title")
	<h1>Our Courses</h1>
@endsection

@section("content")
	<x-section-container>
		<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8 hover:scale-110" alt="back"></button>
		<x-page-title>{{ $course->course_name }} - {{ ucwords($course->level) }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		<div class="flex w-full flex-wrap mt-2">
			<a href="{{ route('guest.show', ['course_id' => $course->id, 'content' => 'general information']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'general information' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				General Information
			</a>

			<a href="{{ route('guest.show', ['course_id' => $course->id ,'content' => 'curriculum']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-48 text-center hover:bg-slate-200"
				style="@if(request('content') == 'curriculum') border-bottom: 4px solid #1db9cf; @endif">
				Curriculum
			</a>
		</div>

		@if(!request('content') || request('content') == 'general information')
			<h2 class="text-lg text-blue font-bold mb-2 mt-8">Course Description</h2>
			<p class="font-semibold">{{ $course->course_description }}</p>

			<h2 class="text-lg text-blue font-bold mt-8">Meet Our Certified Lecturers</h2>
			<div class="mt-4 flex flex-wrap">
				@forelse ($course->teachers as $index => $teacher)
					<a href="{{ route('guest.lecturer-biography', $teacher->id) }}" class="w-1/6 mb-6 hover:text-cyan-500">
						<div class="flex flex-col items-center">
							@if($teacher->details->profpic)
								<img src="{{ Storage::url("app/public/" . $teacher->details->profpic) }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy" oncontextmenu="return false;">
							@else
								@if($teacher->details->gender == 1)
									<img src="{{ asset('img/tempblankprofpicmale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
								@else
									<img src="{{ asset('img/tempblankprofpicfemale.png') }}" class="rounded-full w-28 h-28 mt-2 mb-4" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
								@endif
							@endif
							<h1 class="text-lg font-bold text-center">{{ $teacher->details->gender == 1 ? 'Mr.' : 'Ms.' }} {{ $teacher->full_name }}</h1>
						</div>
					</a>
				@empty
					- No teachers assigned to this course yet -
				@endforelse
			</div>

			<h2 class="text-lg text-blue font-bold mt-6">Students Enrolled</h2>
			<div class="flex w-full gap-5 mt-4">
				@php
					$n_total = $course->course_students->whereIn('learning_status', ['learning', 'complete'])->count();
					$n_learning = $course->course_students->where('learning_status', 'learning')->count();
					$n_complete = $course->course_students->where('learning_status', 'complete')->count();

					$p_learning = $n_total == 0 ? 0 : (int)(($n_learning / $n_total) * 100);
					$p_complete = 100 - $p_learning;
				@endphp
				<div class="bg-white rounded-xl w-1/3 border border-light-blue p-5">
					<div class="flex w-full justify-between items-center">
						<span class="text-slate-600">Learning</span>
						<span class="bg-light-blue text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ $p_learning }}%</span>
					</div>
					<div class="mt-4"><strong>{{ $n_learning }}</strong> Students</div>
				</div>
				<div class="bg-white rounded-xl w-1/3 border border-green-600 p-5">
					<div class="flex w-full justify-between items-center">
						<span class="text-slate-600">Complete</span>
						<span class="bg-green-600 text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ $p_complete }}%</span>
					</div>
					<div class="mt-4"><strong>{{ $n_complete }}</strong> Students</div>
				</div>
				<div class="bg-white rounded-xl w-1/3 border border-indigo-600 p-5">
					<div class="flex w-full justify-between items-center">
						<span class="text-slate-600">Total</span>
						<span class="bg-indigo-600 text-white text-base rounded-lg px-2 py-0.5 font-normal">100%</span>
					</div>
					<div class="mt-4"><strong>{{ $n_total }}</strong> Students</div>
				</div>
			</div>

			<h2 class="text-lg text-blue font-bold mt-10">Students Portfolio</h2>
			<div class="flex gap-3 flex-wrap w-full my-4 overflow-y-auto items-start media-scroll" style="max-height: 60vh;">
				@forelse($course->portfolios->where('is_highlighted', 1) as $portfolio)
					<div class="relative oneperthree rounded-lg overflow-hidden" style="height: 300px;">
						<a href="{{ $portfolio->type != 'link' ? Storage::url("app/public/" . $portfolio->path) : $portfolio->path }}" target="_blank" class="relative imeeji">
							<div class="absolute flex w-full h-full items-center justify-center text-white hint-text" style="display: none; background: rgba(0, 0, 0, 0.7);">Click to view the full file</div>

							@if($portfolio->type == 'image')
								<img src="{{ Storage::url("app/public/" . $portfolio->path) }}" alt="img" style="object-fit: cover;" class="w-full h-full rounded-lg" loading="lazy">
							@elseif($portfolio->type == "video")
								<video class="w-full h-full rounded-lg lazy-video" style="object-fit: cover;" controls preload="none">
									<source src="{{ Storage::url("app/public/" . $portfolio->path) }}" type="{{ Storage::mimeType('app/public/' . $portfolio->path) }}">
								</video>
							@elseif($portfolio->type == "link")
								<div class="w-full h-full flex items-center justify-center bg-slate-400 rounded-lg">
									<i class="bi bi-paperclip text-white text-6xl"></i>
								</div>
							@else
								<div class="w-full h-full flex items-center justify-center bg-slate-400 rounded-lg">
									<i class="bi bi-filetype-pdf text-white text-6xl"></i>
								</div>
							@endif
						</a>
					</div>
				@empty
					<div class="text-center w-full bg-white rounded-xl py-2 px-4">- Currently there are no publicly available portfolio to show -</div>
				@endforelse
			</div>
		@endif

		@if(request('content') == 'curriculum')
			<h2 class="text-lg text-blue font-bold mb-2 mt-8">Learning Outcomes</h2>
			@foreach($course->learning_outcomes()->orderBy('number')->get() as $lo)
				<div class="flex gap-2 items-center">
					<img src="{{ asset('img/bullet.svg') }}" alt="icon" class="w-3 h-3">
					<div>LO{{ $lo->number }}: {{ $lo->title }}</div>
				</div>
			@endforeach

			<h2 class="text-lg text-blue font-bold mt-6">Learning Topic and Activities</h2>
			<div class="overflow-x-auto mb-5">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Title</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Description</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>

					<tbody>
						@if ($course->trial_class_resources->count())
							@foreach ($course->trial_class_resources as $index => $tc_resource)
								<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
									<td class="py-3 px-4">{{ $loop->iteration }}</td>
									<td class="py-3 px-4">{{ $tc_resource->topic_title }} - {{ $tc_resource->activity_title }}</td>
									<td class="py-3 px-4">{!! nl2br($tc_resource->description) !!}</td>
									<td class="py-3 px-4">
										<a href="{{ route('guest.preview', [$course->id, $tc_resource->id]) }}">
											<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="4" class="p-5 bg-white font-semibold text-center">This course doesn't have any public resource available yet.</td>
							</tr>
						@endif
					</tbody>
				</table>

				<div class="mt-10 flex justify-center">
					<div class="border-4 border-cyan-400 p-5 text-cyan-400 font-extrabold">~ Want to find out more? Come join us now! ~</div>
				</div>
			</div>
		@endif
	</x-section-container>
@endsection
