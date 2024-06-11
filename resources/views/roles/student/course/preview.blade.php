@extends("layouts.main-student")

@section("title")
	<h1>{{ $material->topic->title }}</h1>
@endsection

@section("content")
	<x-page-title>{{ $material->title }}</x-page-title>
	<div class="flex gap-1 my-4">
		<x-button type="button" onclick="history.back()" class="bg-orange-500">
			Back
		</x-button>
		<x-anchor-button type="button" target="blank" href="{{ $material->link }}" class="bg-orange-500">
			Visit Link
		</x-anchor-button>
	</div>
	<iframe src="{{ $preview_link }}" width="100%" style="height: 70vh;" class="my-5 border-2" id="contentpreview"></iframe>
	<p>{{ $material->desc }}</p>

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
@endsection
