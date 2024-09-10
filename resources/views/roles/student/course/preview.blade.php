@extends("layouts.main-student")

@section("title")
	<h1>{{ $material->topic->title }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('student.mycourse.show', $material->topic->course->id) }}" class="text-yellow-500 font-bold">{{ $material->topic->course->course_name }}</a>
	> <span>{{ $material->title }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ $material->title }}</x-page-title>
		<div class="flex gap-3 my-4">
			<x-anchor-button type="button" target="blank" href="{{ $material->link }}" class="bg-orange-500">
				Visit Link
			</x-anchor-button>
			<x-button type="button" onclick="history.back()" class="bg-slate-600">
				Back
			</x-button>
		</div>

		<iframe src="{{ $preview_link }}" width="100%" style="height: 70vh;" class="my-5 border-2" id="contentpreview"></iframe>

		<p class="text-center mt-10 mb-3 font-semibold text-blue-950">{{ $material->desc }}</p>

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
	</x-section-container>
@endsection
