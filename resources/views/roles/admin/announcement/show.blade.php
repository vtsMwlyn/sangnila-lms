<x-section-container  style="background-color: white;">
	<x-page-title>{{ $announcement->title }}</x-page-title>

	<div class="flex w-full justify-center my-10">
		@if($announcement->image_path)
			<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" class="w-3/4" alt="announcement_img">
		@else
			<div class="flex bg-slate-200 items-center justify-center text-white font-extrabold w-3/4">
				<i class="bi bi-megaphone-fill text-6xl"></i>
			</div>
		@endif
	</div>

	<div class="w-full">
		{!! $announcement->content !!}
	</div>
</x-section-container>
