@extends("layouts.main-student")

@section("title")
	<h1>Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('student.mycourse.show', $activity->topic->course->id) }}" class="text-yellow-500 font-bold">{{ $activity->topic->course->course_name }}</a>
	> <span>{{ $activity->title }}</span>
@endsection

@section("content")
	<div class="rounded-3xl w-full py-5 px-8 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
		<x-back-button href="{{ route('student.mycourse.show', $activity->topic->course->id) }}"></x-back-button>

		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ __($activity->topic->title . ": " . $activity->title) }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		<h2 class="">{{ $activity->desc }}</h2>

		<div class="flex gap-3 mt-8 mb-4">
			<x-anchor-button type="button" target="_blank" href="{{ $activity->link }}" style="background: linear-gradient(90deg, #1EB8CD 0%, #354D9B 100%);">
				<i class="bi bi-box-arrow-up-right"></i> Visit Link
			</x-anchor-button>
		</div>

		<iframe src="{{ $preview_link }}" width="100%" style="height: 70vh;" class="my-5 border-2 rounded-3xl" id="contentpreview"></iframe>

		<script>
			function adjustIframeHeight() {
				const iframe = document.querySelector('#contentpreview');
				const width = iframe.offsetWidth;
				// Example: Maintain a 16:9 aspect ratio
				const height = (width * 9) / 16;
				iframe.style.height = height + 'px';
			}

			window.addEventListener('load', adjustIframeHeight);
			window.addEventListener('resize', adjustIframeHeight);
		</script>
	</div>
@endsection
