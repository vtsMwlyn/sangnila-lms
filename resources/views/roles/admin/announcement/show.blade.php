<x-section-container>
	<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8" alt="back"></button>
	<x-page-title>{{ $announcement->title }}</x-page-title>
	<div class="w-full bg-slate-400 mb-3 mt-2" style="height: 2px;"></div>

	<div class="flex w-full justify-center my-4 md:my-10">
		@if($announcement->image_path)
			<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" class="w-full md:w-3/4 rounded-lg lg:rounded-3xl" alt="announcement_img">
		@else
			<div class="flex bg-slate-200 items-center justify-center text-white font-extrabold w-full md:w-3/4 h-[200px] lg:h-[500px] rounded-lg lg:rounded-3xl">
				<i class="bi bi-megaphone-fill text-6xl"></i>
			</div>
		@endif
	</div>

	<div class="w-full announcementContent">
		{!! $announcement->content !!}
	</div>
</x-section-container>
