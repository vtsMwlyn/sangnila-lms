@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.student.select-course') }}" class="text-yellow-500 font-bold">Select Course</a>
	> <span>{{ $course->course_name }}</span>
	> <a href="{{ route('teacher.student.select-student', $course->id) }}" class="text-yellow-500 font-bold">Select Student</a>
	> <span>{{ $student->full_name }}</span>
@endsection

@section('popup')
	{{-- Upload portfolio  --}}
	<x-popup popup_title="Upload Portfolio" class="w-11/12 xl:w-2/3 flex flex-col items-stretch justify-center overflow-y-auto" id="upload-portfolio">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" action="{{ route('teacher.student.store.portfolio', [$student->id, $course->id]) }}" class="mt-4" enctype="multipart/form-data">
				@csrf
				{{-- File uploads --}}
				<div class="flex flex-col">
					<label for="files">Select Images to Upload</label>
					<x-input id="files" class="w-full mt-1" type="file" name="files[]" style="border-width: 3px;" multiple/>
				</div>

				{{-- Link upload --}}
				<div class="flex flex-col mt-4">
					<label for="link">Or Add Work Link</label>
					<x-input id="link" class="w-full mt-1" type="text" name="link" placeholder="Add work link here"/>
				</div>

				{{-- Preview --}}
				<div id="file-preview" class="mt-4 flex flex-wrap w-full gap-3"></div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/4">
						{{ __('Submit') }}
					</x-button>
				</div>
			</form>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		@php
			$status = App\Models\CourseStudent::where('student_id', $student->id)->where('course_id', $course->id)->first()->learning_status
		@endphp

		<x-back-button href="{{ route('teacher.student.select-student', $course->id) }}"></x-back-button>
		<x-page-title>{{ ucwords($student->full_name) }}</x-page-title>
		<div class="flex gap-2 items-center text-xl font-semibold text-blue-900 mt-2">
			<span>{{ $course->course_name }} - {{ ucwords($course->level) }}</span>
			<span class="@if($status == 'learning') bg-light-blue @elseif($status == 'undone') bg-red @else bg-green-600 @endif text-white text-base rounded-lg px-2 py-0.5 font-normal">{{ ucwords($status) }}</span>
		</div>

		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		{{-- For larger screen --}}
		<div class="mt-4 w-full flex flex-wrap">
			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id, 'content' => 'activity access']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'activity access' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				Activity Access
			</a>

			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id,'content' => 'meeting links']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'meeting links') border-bottom: 4px solid #1db9cf; @endif">
				Meeting Links
			</a>

			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id,'content' => 'portfolios']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'portfolios') border-bottom: 4px solid #1db9cf; @endif">
				Portfolios
			</a>

			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id,'content' => 'assessment']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'assessment') border-bottom: 4px solid #1db9cf; @endif">
				Assessment
			</a>
		</div>

		@if(request('content') == 'activity access' || !request('content'))
			<form method="post" action="{{ route("teacher.student.update.progress.activity-access", [$course->id, $student->id]) }}" id="activity_access">
				@csrf
				@method('patch')

				<div class="w-full overflow-x-auto hidden xl:block mt-3">
					<table class="w-full">
						<thead>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Name</th>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Material Access</th>
						</thead>
						<tbody>
							@forelse ($newestprogress as $progress)
								<tr class="@if($loop->index % 2 == 0) bg-white @endif">
									<td class="py-3 px-4 text-center">{{ $progress->activity->session }}</td>
									<td class="py-3 px-4">{{ $progress->activity->topic->title }}</td>
									<td class="py-3 px-4">{{ $progress->activity->title }}</td>
									<td class="py-3 px-4">
										<div class="w-full flex justify-center">
											<input type="checkbox" id="activity_progress_{{ $progress->id }}"
											class="mr-2 h-5 w-5"
											@if ($progress->status === 'unlocked') checked @endif>
										</div>
									</td>
								</tr>
							@empty
								<tr><td colspan="4" class="text-center font-semibold bg-white rounded-xl p-5">- No activities yet added to this course -</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>

				@if($newestprogress->isNotEmpty())
					<div class="flex items-stretch gap-3 justify-end mt-10 mb-3">
						<x-cancel-button class="w-full md:w-40 xl:w-1/6">
							Cancel
						</x-cancel-button>
						<x-button class=" w-full md:w-40 xl:w-1/6">
							{{ __('Save') }}
						</x-button>
					</div>
				@endif
			</form>
			

			{{-- For smaller screen --}}
			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-3">
				@forelse ($newestprogress as $progress)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3">
						<div class="flex flex-col items-start w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<span class="text-base text-start"><strong>[{{ $progress->activity->topic->title }}]</strong> {{ $progress->activity->title }}</span>
							</div>
							<i>Session {{ $progress->activity->session }}</i>
						</div>
						<div class="flex flex-col w-full">
							<strong>Actions</strong>

							<div class="w-full flex justify-start mt-2">
								<input type="checkbox" id="activity_progress_{{ $progress->id }}"
								class="mr-2 h-5 w-5"
								@if ($progress->status === 'unlocked') checked @endif> Student is able to access this activity
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		@if(request('content') == 'meeting links')
			<form method="post" action="{{ route("teacher.student.update.progress.meeting-link", [$course->id, $student->id]) }}" id="meeting_link">
				@csrf
				@method('patch')

				<input type="hidden" name="content" value="meeting links">

				<div class="w-full overflow-x-auto hidden xl:block mt-3">
					<table class="w-full">
						<thead>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400">Session</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Topic</th>
							<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Name</th>
							<th class="text-center py-3 px-4 border-b-2 border-slate-400 w-1/3">Meeting Link</th>
						</thead>
						<tbody>
							@forelse ($newestprogress as $i => $progress)
								<tr class="@if($loop->index % 2 == 0) bg-white @endif">
									<td class="py-3 px-4 text-center">{{ $progress->activity->session }}</td>
									<td class="py-3 px-4">{{ $progress->activity->topic->title }}</td>
									<td class="py-3 px-4">{{ $progress->activity->title }}</td>
									<td class="py-3 px-4 w-1/3">
										<x-input type="text" class="w-full" name="meeting_links[]" placeholder="Add meeting link" value="{{ old('meeting_links.' . $i, $progress->meeting_link) }}"/>
										@error('meeting_links.' . $i)
											<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> Please insert a valid URL.</p>
										@enderror
									</td>
								</tr>
							@empty
								<tr><td colspan="4" class="text-center font-semibold bg-white rounded-xl p-5">- No activities yet added to this course -</td></tr>
							@endforelse
						</tbody>
					</table>

					@if($newestprogress->isNotEmpty())
						<div class="flex items-stretch gap-3 justify-end mt-10 mb-3">
							<x-cancel-button class="w-full md:w-40 xl:w-1/6">
								Cancel
							</x-cancel-button>
							<x-button class=" w-full md:w-40 xl:w-1/6">
								{{ __('Save') }}
							</x-button>
						</div>
					@endif
				</div>
			</form>

			{{-- For smaller screen --}}
			<div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-3">
				@forelse ($newestprogress as $progress)
					<div class="bg-white rounded-xl p-4 flex flex-col gap-3">
						<div class="flex flex-col items-start w-full">
							<div class="flex w-full justify-between items-center mb-2">
								<span class="text-base text-start"><strong>[{{ $progress->activity->topic->title }}]</strong> {{ $progress->activity->title }}</span>
							</div>
							<i>Session {{ $progress->activity->session }}</i>
						</div>
						<div class="flex flex-col w-full">
							<strong>Actions</strong>

							<div class="w-full flex justify-start mt-2 flex-col items-start">
								<label>Add Meeting Link</label>
								<x-input type="text" class="w-full mt-1" name="meeting_links[]" placeholder="Add meeting link" value="{{ old('meeting_links.' . $i, $progress->meeting_link) }}"/>
								@error('meeting_links.' . $i)
									<p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> Please insert a valid URL.</p>
								@enderror
							</div>
						</div>
					</div>
				@empty
					- N/A -
				@endforelse
			</div>
		@endif

		@if(request('content') == 'portfolios')
			<div class="flex flex-col w-full">
				<x-button type="button" id="upload-portfolio-btn" class="w-fit mt-4"><i class="bi bi-plus-lg"></i> Upload Portfolios</x-button>

				<div class="flex gap-3 flex-wrap mt-6 w-full overflow-y-auto items-start" style="height: 60vh;">
					@forelse($student->portfolios()->orderBy('created_at', 'desc')->get() as $portfolio)
						<div class="relative oneperthree" style="height: 300px;">
							<form action="{{ route('teacher.student.destroy.portfolio', $portfolio->id) }}" method="post" class="absolute" style="top: 10px; right: 10px; z-index: 5;">
								@method('delete')
								@csrf
								<button type="submit" class="bg-red rounded-lg py-2 px-4 text-white hover:bg-slate-700" onclick="return confirm('Apakah anda yakin ingin menghapus foto ini dari pengembalian ini?')" title="Remove this file from student's portfolio"><i class="bi bi-trash3"></i></button>
							</form>

							<a href="{{ $portfolio->type != 'link' ? Storage::url("app/public/" . $portfolio->path) : $portfolio->path }}" target="_blank" class="relative imeeji">
								<div class="absolute flex w-full h-full items-center justify-center text-white hint-text" style="display: none; background: rgba(0, 0, 0, 0.7);">Click to view the full file</div>

								@if($portfolio->type == 'image')
									<img src="{{ Storage::url("app/public/" . $portfolio->path) }}" alt="img" style="object-fit: cover;" class="w-full h-full rounded-lg">
								@elseif($portfolio->type == "video")
									<video class="w-full h-full rounded-lg" style="object-fit: cover;" controls>
										<source src="{{ Storage::url("app/public/" . $portfolio->path) }}" type="{{ Storage::mimeType('app/public/' . $portfolio->path) }}">
									</video>
								@else
									<div class="w-full h-full flex items-center justify-center bg-slate-400 rounded-lg">
										<i class="bi bi-paperclip text-white text-6xl"></i>
									</div>
								@endif
							</a>
						</div>
					@empty
						<div class="text-center w-full bg-white rounded-xl py-2 px-4">- No data found -</div>
					@endforelse
				</div>
			</div>
		@endif

		@if(request('content') == 'assessment')
			@if($assessment)
				<div class="w-full flex flex-col items-stretch mt-4 p-5 rounded-2xl" style="background: white;">
					<h3 class="text-xl font-bold text-blue">Performance: {{ ucwords($assessment->performance_score) }}</h3>
					<div class="mt-2">{!! $assessment->performance_description !!}</div>
				</div>
				<div class="w-full flex flex-col items-stretch mt-4 p-5 rounded-2xl" style="background: linear-gradient(to right, rgba(190, 226, 219, 0.49) 0%, rgba(104, 124, 120, 0) 100%);">
					<h3 class="text-xl font-bold text-blue">Technical Skill: {{ ucwords($assessment->technical_skill_score) }}</h3>
					<div class="mt-2">{!! $assessment->technical_skill_description !!}</div>
				</div>
				<div class="w-full flex flex-col items-stretch mt-4 p-5 rounded-2xl" style="background: white;">
					<h3 class="text-xl font-bold text-blue">Aesthetical Skill: {{ ucwords($assessment->aesthetical_skill_score) }}</h3>
					<div class="mt-2">{!! $assessment->aesthetical_skill_description !!}</div>
				</div>
				<div class="w-full flex flex-col items-stretch my-4 p-5 rounded-2xl" style="background: linear-gradient(to right, rgba(190, 226, 219, 0.49) 0%, rgba(104, 124, 120, 0) 100%);">
					<h3 class="text-xl font-bold text-blue">Overall: {{ ucwords($assessment->overall_score) }}</h3>
					<div class="mt-2">{!! $assessment->overall_description !!}</div>
				</div>

				{{-- <div class="flex w-full justify-end mt-4">
					<x-anchor-button href="{{ route('teacher.student.edit.assessment', $assessment->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
				</div> --}}
			@else
				<div class="mt-4 flex flex-col items-center py-10">
					<img src="{{ asset('img/no-assessment.png') }}" class="w-1/6" alt="coming-soon">
					<p class="font-semibold text-dark-blue mt-10">You haven't uploaded the assessment for this student</p>
					<x-anchor-button href="{{ route('teacher.student.create.assessment', [$student->id, $course->id]) }}" class="mt-4"><i class="bi bi-file-earmark-text"></i> Upload Assessment</x-anchor-button>
				</div>
			@endif
		@endif
	</x-section-container>

	<script>
		const collectCheckboxValues = () => {
			const checkboxes = document.querySelectorAll('input[type="checkbox"]');
			const checkboxValues = [];
			checkboxes.forEach((checkbox) => {
				checkboxValues.push(checkbox.checked ? 'on' : 'off');
			});
			return checkboxValues;
		}

		$(document).ready(() => {
			$('#upload-portfolio-btn').on('click', function(){
				$('#upload-portfolio').parent().show();
			});

			$("#files").on("change", function(){
				let files = this.files;
				let previewContainer = $("#file-preview");

				// Clear previous previews
				previewContainer.empty();

				if (files) {
					$.each(files, function(index, file) {
						let reader = new FileReader();

						reader.onload = function(e) {
							let mediaElement;

							if (file.type.startsWith("image/")) {
								// Create image element
								mediaElement = $("<img>")
									.attr("src", e.target.result).addClass('oneperthree')
									.css({"height": "200px", "object-fit": "cover", "border-radius": "8px"});
							} 
							else if (file.type.startsWith("video/")) {
								// Create video element
								mediaElement = $("<video>")
									.attr("src", e.target.result).addClass('oneperthree').attr("controls", true)
									.css({"height": "200px", "border-radius": "8px"});
							}

							if (mediaElement) {
								previewContainer.append(mediaElement);
							}
						};

						reader.readAsDataURL(file);
					});
				}
			});

			$('.imeeji').on({
				'mouseover': function(){
					$(this).find('.hint-text').show();
				},
				'mouseout': function(){
					$(this).find('.hint-text').hide();
				}
			});

			$('#activity_access').on('submit', function(event) {
				event.preventDefault();
				const checkboxValues = collectCheckboxValues();

				checkboxValues.forEach((value, index) => {
					$('#activity_access').append($('<input>').attr({'type': 'hidden', 'name': 'checkbox_value[]', 'value': value}));
				});

				this.submit();
			});
		});
	</script>
@endsection
