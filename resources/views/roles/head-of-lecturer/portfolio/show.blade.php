@extends('layouts.main-head-of-lecturer')

@section('title')
    <h1>Portfolios</h1>
@endsection

@section('popup')
    {{-- Delete portfolio --}}
	<x-confirmation method="delete" popup_title="Delete Portfolio" id="delete-portfolio-popup">
		Are you sure want to <span class="font-bold text-red">remove</span> this file from the student's portfolio?
	</x-confirmation>

    {{-- Highlight portfolio --}}
	<x-confirmation popup_title="Highlight Portfolio" id="highlight-portfolio-popup">
		Are you sure want to <span class="font-bold text-light-blue">highlight</span> this student's portfolio? <strong>The portfolio will be publicly visible.</strong>
	</x-confirmation>

    {{-- Unhighlight portfolio --}}
	<x-confirmation popup_title="Unhighlight Portfolio" id="unhighlight-portfolio-popup">
		Are you sure want to <span class="font-bold text-light-blue">unhighlight</span> this student's portfolio? <strong>The portfolio will be hidden from public.</strong>
	</x-confirmation>
@endsection

@section('content')
    <x-section-container>
        <x-back-button href="{{ route('head-of-lecturer.portfolio.index') }}"></x-back-button>
        <x-page-title>All Portfolio</x-page-title>
        <h1 class="font-bold text-lg text-blue mt-1">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>

        <div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

        <form class="flex w-full justify-center mt-4 items-center gap-3" action="{{ route("head-of-lecturer.portfolio.show", $course->id) }}">
            <x-input type="text" name="student" placeholder="Search by student..." :value="request('student')"/>
            <x-input type="text" name="teacher" placeholder="Search by teacher..." :value="request('teacher')"/>
            <x-button type="submit"><i class="bi bi-search"></i> Filter</x-button>
        </form>

        <div class="flex gap-3 flex-wrap mt-6 w-full overflow-y-auto items-start media-scroll" style="height: 90vh;">
            @forelse($portfolios as $portfolio)
                <div class="relative oneperthree rounded-lg bg-white p-5 overflow-hidden">
                    @if($portfolio->is_highlighted)
                        <div class="absolute py-2 px-20 text-white" style="top: 20px; left: -50px; z-index: 5; background-color: rgb(29, 185, 207, 0.9); transform: rotate(-30deg);">
                            {{ $portfolio->is_highlighted == 1 ? 'Highlighted' : 'Unhighlighted' }}
                        </div>
                    @endif

                    <div class="relative rounded-lg overflow-hidden" style="height: 250px;">
                        <div class="absolute flex items-center gap-1" style="top: 10px; right: 10px; z-index: 5;">
                            <button type="button" data-route="{{ route('head-of-lecturer.portfolio.destroy', $portfolio->id) }}" class="bg-red rounded-lg py-2 px-4 text-white hover:bg-slate-700 delete-portfolio-btn" title="Remove this file from student's portfolio"><i class="bi bi-trash3"></i></button>
                            <button type="button" data-route="{{ $portfolio->is_highlighted == 0 ? route('head-of-lecturer.portfolio.highlight', $portfolio->id) : route('head-of-lecturer.portfolio.unhighlight', $portfolio->id) }}" class="bg-indigo-600 rounded-lg py-2 px-4 text-white hover:bg-slate-700 {{ $portfolio->is_highlighted == 1 ? 'unhighlight-portfolio-btn' : 'highlight-portfolio-btn' }}" title="{{ $portfolio->is_highlighted == 1 ? 'Unhighlight' : 'Highlight' }} this portfolio">
                                @if($portfolio->is_highlighted == 1)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            </button>
                        </div>

                        <a href="{{ $portfolio->type != 'link' ? Storage::url("app/public/" . $portfolio->path) : $portfolio->path }}" target="_blank" class="relative imeeji">
                            <div class="absolute flex w-full h-full items-center justify-center text-white hint-text" style="display: none; background: rgba(0, 0, 0, 0.7);">Click to view the full file</div>

                            @if($portfolio->type == 'image')
                                <img src="{{ Storage::url("app/public/" . $portfolio->path) }}" alt="img" style="object-fit: cover;" loading="lazy" class="w-full h-full rounded-lg">
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

                    <div class="mt-4">
                        @php
                            $cs = App\Models\CourseStudent::where('course_id', $portfolio->course_id)->where('student_id', $portfolio->student_id)->first();
                        @endphp
                        <div class="flex gap-2 items-center"><i class="bi bi-calendar2-date text-slate-400 text-xl"></i> {{ Carbon\Carbon::parse($portfolio->created_at)->format('D, d M Y') }}</div>
                        <div class="flex gap-2 items-center"><img src="{{ asset('img/lecturer.svg') }}" alt="icon"> {{ $cs ? $cs->student->full_name : 'Unknown' }}</div>
                        <div class="flex gap-2 items-center"><img src="{{ asset('img/lecturer.svg') }}" alt="icon"> @if($cs){{ $cs->teacher->details->gender == 1? 'Mr.' : 'Ms.' }} {{ $cs->teacher->full_name }}@else Unknown @endif</div>
                    </div>
                </div>
            @empty
                <div class="text-center w-full bg-white rounded-xl py-2 px-4">- No data found -</div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $portfolios->links() }}
        </div>
    </x-section-container>

    <script>
        $(document).ready(() => {
            $('.imeeji').on({
				'mouseover': function(){
					$(this).find('.hint-text').show();
				},
				'mouseout': function(){
					$(this).find('.hint-text').hide();
				}
			});

            $('.delete-portfolio-btn').on('click', function(){
				$('#delete-portfolio-popup').find('form').attr('action', $(this).data('route'));
				$('#delete-portfolio-popup').parent().show();
			});

            $('.highlight-portfolio-btn').on('click', function(){
				$('#highlight-portfolio-popup').find('form').attr('action', $(this).data('route'));
				$('#highlight-portfolio-popup').parent().show();
			});

            $('.unhighlight-portfolio-btn').on('click', function(){
				$('#unhighlight-portfolio-popup').find('form').attr('action', $(this).data('route'));
				$('#unhighlight-portfolio-popup').parent().show();
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