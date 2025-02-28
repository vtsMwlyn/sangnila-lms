<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col sm:text-base text-sm" style="background: #FEFEFEB2;">
	<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8" alt="back"></button>
	<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $announcement->title }}</h1>
	<div class="w-full bg-slate-400 mb-3 mt-2" style="height: 2px;"></div>

	<div class="flex w-full justify-center my-10">
		@if($announcement->image_path)
			<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" class="w-3/4 rounded-3xl" alt="announcement_img">
		@else
			<div class="flex bg-slate-200 items-center justify-center text-white font-extrabold w-3/4 h-[500px]">
				<i class="bi bi-megaphone-fill text-6xl"></i>
			</div>
		@endif
	</div>

	<div class="w-full">
		{!! $announcement->content !!}
	</div>
</div>
