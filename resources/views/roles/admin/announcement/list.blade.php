<div class="w-full h-full flex flex-col items-stretch p-5 rounded-xl overflow-y-auto" style="background-color: rgba(254, 254, 254, 0.7);">
	@forelse($announcements as $a)
		<a href="{{ route('view-announcement', $a->id) }}" class="transition duration-300 selectable-cards">
			<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col gap-2 sm:text-base text-sm" style="background: #FEFEFEB2;">
				<h1 class="font-bold">{{ $a->title }}</h1>
				<div class="">Sangnila Arts Academy - {{ Carbon\Carbon::parse($a->announce_from)->format('d M Y, H:i') }} GMT+7</div>
			</div>
		</a>
	@empty
	@endforelse
</div>

<script>
	$(".selectable-cards").on({
		"mouseover": function(){
			$(this).css("transform", "scale(1.02)");
		},
		"mouseout": function(){
			$(this).css("transform", "scale(1)");
		}
	});
</script>

