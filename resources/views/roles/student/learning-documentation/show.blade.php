@extends('layouts.main-student')

@section('title')
    <h1>Learning Documentation</h1>
@endsection

@section('popup')
	{{-- Upload portfolio  --}}
	<x-popup popup_title="Upload Portfolio" class="w-11/12 xl:w-2/3 flex flex-col items-stretch justify-center overflow-y-auto" id="upload-portfolio">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" action="{{ route('student.learning-documentation.store.portfolio', $course->id) }}" class="mt-4" enctype="multipart/form-data">
				@csrf
				{{-- File uploads --}}
				<div class="flex flex-col">
					<label for="files">Select Images to Upload</label>
					<x-input id="files" class="w-full mt-1" type="file" name="files[]" style="border-width: 3px;" accept="video/*,image/*,.pdf" multiple/>
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

@section('content')
    <x-section-container>
        <x-back-button href="{{ route('student.learning-documentation.index') }}"></x-back-button>
        <x-page-title>{{ $course->course_name }} - {{ ucwords($course->level) }}</x-page-title>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

        {{-- For larger screen --}}
		<div class="mt-4 w-full flex">
			<a href="{{ route('student.learning-documentation.show', ['course_id' => $course->id, 'content' => 'portfolios']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(!request('content') || request('content') == 'portfolios') border-bottom: 4px solid #1db9cf; @endif">
				Portfolios
			</a>
            <a href="{{ route('student.learning-documentation.show', ['course_id' => $course->id, 'content' => 'certificates']) }}"
				class="py-2 w-1/2 xl:w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('content') == 'certificates') border-bottom: 4px solid #1db9cf; @endif">
				Certificate
			</a>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

        @if(!request('content') || request('content') == 'portfolios')
            <div class="flex flex-col w-full">
                <x-button type="button" id="upload-portfolio-btn" class="w-fit mt-4"><i class="bi bi-plus-lg"></i> Upload Portfolios</x-button>

                <div class="flex gap-3 flex-wrap mt-6 w-full overflow-y-auto items-start media-scroll" style="height: 60vh;">
                    @forelse(Auth::user()->portfolios()->orderBy('created_at', 'desc')->get() as $portfolio)
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
                        <div class="text-center w-full bg-white rounded-xl py-2 px-4">- No data found -</div>
                    @endforelse
                </div>
            </div>
        @endif

        @if(request('content') == 'certificates')
			@php
				$assessment = App\Models\Assessment::where('student_id', Auth::user()->id)->where('course_id', $course->id)->first();
				$cs = App\Models\CourseStudent::where('student_id', Auth::user()->id)->where('course_id', $course->id)->first();
			@endphp
            <div class="mt-4 flex flex-col items-center gap-10 py-10">
				@if($assessment && $assessment->certificate_accessible && $cs->learning_status == 'complete')
					<p class="font-semibold text-dark-blue">Your certificate is available to download!</p>
					<x-anchor-button target="_blank" href="{{ route('student.learning-documentation.view-certificate', [$course->id, Auth::user()->id]) }}"><i class="bi bi-file-earmark-arrow-down"></i> View Certificate</x-anchor-button>
				@else
					<img src="{{ asset('img/certificate-not-available.png') }}" class="w-1/4" alt="certificate-not-available">
					<p class="font-semibold text-dark-blue">Your certificate is not available yet</p>
				@endif
            </div>
        @endif
    </x-section-container>

    <script>
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
								const source = $('<source>').attr("src", e.target.result).attr("type", file.type);
								mediaElement = $("<video>")
									.addClass('oneperthree').attr("controls", true)
									.css({"height": "200px", "border-radius": "8px"})
									.append(source);
							}
							else {
								mediaElement = $('<div>').addClass('oneperthree bg-gray-400 flex items-center justify-center text-6xl text-white').css({"height": "200px", "border-radius": "8px"}).html('<i class="bi bi-filetype-pdf"></i>');
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

			// Handle lazy loading video
			const $lazyVideos = $('video.lazy-video');
			const $scrollContainer = $('.media-scroll').get(0); // raw DOM element

			if ('IntersectionObserver' in window) {
				const observer = new IntersectionObserver(function (entries, observer) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							const video = entry.target;
							const $video = $(video);
							const $source = $video.find('source');

							const dataSrc = $source.attr('data-src');
							if (dataSrc) {
								$source.attr('src', dataSrc);
								video.load();
								observer.unobserve(video);
							}
						}
					});
				}, {
					root: $scrollContainer,
					threshold: 0.2
				});

				$lazyVideos.each(function () {
					observer.observe(this);
				});
			}
		});
    </script>
@endsection