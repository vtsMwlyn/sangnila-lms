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
	<!-- Upload portfolio  -->
	<x-popup popup_title="Upload Portfolio" class="w-2/3 flex flex-col items-stretch justify-center overflow-y-auto" id="upload-portfolio">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" action="{{ route('teacher.student.store.portfolio', [$student->id, $course->id]) }}" class="mt-4" enctype="multipart/form-data">
				@csrf
				<!-- File uploads -->
				<div class="flex flex-col">
					<label for="files">Select Images to Upload</label>
					<x-input id="files" class="w-full mt-1" type="file" name="files[]" style="border-width: 3px;" multiple/>
				</div>

				<!-- Link upload -->
				<div class="flex flex-col mt-4">
					<label for="link">Or Add Work Link</label>
					<x-input id="link" class="w-full mt-1" type="text" name="link" placeholder="Add work link here"/>
				</div>

				<!-- Preview -->
				<div id="file-preview" class="mt-4 flex flex-wrap w-full gap-3"></div>

				<div class="flex items-stretch gap-3 justify-center mt-10 mb-3">
					<x-button class=" w-full md:w-1/5">
						{{ __('Submit') }}
					</x-button>
				</div>
			</form>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.student.select-student', $course->id) }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $student->full_name }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>

		@if(session()->has("successUpdateProgress"))
			<x-badge-success badge_text="{{ session('successUpdateProgress') }}" class="mb-4"></x-badge-success>
		@elseif(session()->has("successUploadPortfolio"))
			<x-badge-success badge_text="{{ session('successUploadPortfolio') }}" class="mb-4"></x-badge-success>
		@elseif(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-4"></x-badge-danger>
		@elseif(session()->has("successDeletePortfolio"))
			<x-badge-warning badge_text="{{ session('successDeletePortfolio') }}" class="mb-4"></x-badge-warning>
		@elseif(session()->has("successUpdateMeetingLink"))
			<x-badge-success badge_text="{{ session('successUpdateMeetingLink') }}" class="mb-4"></x-badge-success>
		@endif

		<!-- For larger screen -->
		<div class="lg:flex mt-4 w-full flex-wrap hidden">
			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id, 'content' => 'activity access']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'activity access' || !request('content')) border-bottom: 4px solid #1db9cf; @endif">
				Activity Access
			</a>

			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id,'content' => 'meeting links']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'meeting links') border-bottom: 4px solid #1db9cf; @endif">
				Meeting Links
			</a>

			<a href="{{ route('teacher.student.show', ['course_id' => $course->id, 'student_id' => $student->id,'content' => 'portfolios']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'portfolios') border-bottom: 4px solid #1db9cf; @endif">
				Portfolios
			</a>
		</div>

		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(request('content') == 'activity access' || !request('content'))
			<div class="w-full overflow-x-auto">
				<form method="post" action="{{ route("teacher.student.update.progress.activity-access", [$course->id, $student->id]) }}" id="activity_access">
					@csrf
					@method('patch')

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
									<td class="py-2 px-4 text-center">{{ $progress->activity->session }}</td>
									<td class="py-2 px-4">{{ $progress->activity->topic->title }}</td>
									<td class="py-2 px-4">{{ $progress->activity->title }}</td>
									<td class="py-2 px-4">
										<div class="w-full flex justify-center">
											<input type="checkbox" id="activity_progress_{{ $progress->id }}"
											class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300"
											@if ($progress->status === 'unlocked') checked @endif>
										</div>
									</td>
								</tr>
							@empty
								<tr><td colspan="4" class="text-center font-semibold bg-white rounded-xl p-5">- No activities yet added to this course -</td></tr>
							@endforelse
						</tbody>
					</table>

					@if($newestprogress->isNotEmpty())
						<div class="flex gap-2 mt-10 mb-3 w-full justify-center">
							<x-button class=" w-full md:w-1/6">Save</x-button>
							<x-cancel-button class="w-full md:w-1/6" href="{{ route('teacher.student.select-student', $course->id) }}">Cancel</x-cancel-button>
						</div>
					@endif
				</form>
			</div>
		@endif

		@if(request('content') == 'meeting links')
			<div class="w-full overflow-x-auto">
				<form method="post" action="{{ route("teacher.student.update.progress.meeting-link", [$course->id, $student->id]) }}" id="meeting_link">
					@csrf
					@method('patch')

					<input type="hidden" name="content" value="meeting links">

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
									<td class="py-2 px-4 text-center">{{ $progress->activity->session }}</td>
									<td class="py-2 px-4">{{ $progress->activity->topic->title }}</td>
									<td class="py-2 px-4">{{ $progress->activity->title }}</td>
									<td class="py-2 px-4 w-1/3">
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
						<div class="flex gap-2 mt-10 mb-3 w-full justify-center">
							<x-button class=" w-full md:w-1/6">Save</x-button>
							<x-cancel-button class="w-full md:w-1/6" href="{{ route('teacher.student.select-student', $course->id) }}">Cancel</x-cancel-button>
						</div>
					@endif
				</form>
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
								<button type="submit" class="bg-red rounded-lg py-2 px-4 text-white" onclick="return confirm('Apakah anda yakin ingin menghapus foto ini dari pengembalian ini?')"><i class="bi bi-trash3"></i></button>
							</form>

							<a href="{{ $portfolio->type != 'link' ? Storage::url('app/public/' . $portfolio->path) : $portfolio->path }}" target="_blank" class="relative imeeji">
								<div class="absolute flex w-full h-full items-center justify-center text-white hint-text" style="display: none; background: rgba(0, 0, 0, 0.7);">Click to view the full file</div>

								@if($portfolio->type == 'image')
									<img src="{{ Storage::url('app/public/' . $portfolio->path) }}" alt="img" style="object-fit: cover;" class="w-full h-full rounded-lg">
								@elseif($portfolio->type == "video")
									<video class="w-full h-full rounded-lg" style="object-fit: cover;" controls>
										<source src="{{ Storage::url('app/public/' . $portfolio->path) }}" type="{{ Storage::mimeType('app/public/' . $portfolio->path) }}">
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
						// $.each(files, function(index, file){
						// 	let reader = new FileReader();

						// 	reader.onload = function(e) {
						// 		let imgElement = $("<img>")
						// 			.attr("src", e.target.result)
						// 			.css({"width": "300px", "height": "200px", "object-fit": "cover", "border-radius": "8px"});

						// 		previewContainer.append(imgElement);
						// 	};

						// 	reader.readAsDataURL(file);
						// });
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
	</x-section-container>
@endsection
