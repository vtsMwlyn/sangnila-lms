<x-section-container>
	<x-page-title>Announcement List</x-page-title>
	<div class="w-full bg-slate-400 mb-4 mt-2" style="height: 2px;"></div>

	@forelse($announcements as $a)
		<a href="{{ route('view-announcement', $a->id) }}" class="transition duration-300 selectable-cards">
			<div class="rounded-3xl w-full @if($loop->index != 0) mt-4 @endif py-5 px-8 flex flex-col gap-2 sm:text-base text-sm" style="background: @if($loop->iteration % 2 == 1) white @else linear-gradient(to right, rgba(190, 226, 219, 0.49) 0%, rgba(104, 124, 120, 0) 100%) @endif;">
				<h1 class="font-bold text-blue">{{ $a->title }}</h1>
				<div class="">Sangnila Arts Academy - {{ Carbon\Carbon::parse($a->announce_from)->format('d M Y, H:i') }} GMT+7</div>
			</div>
		</a>
	@empty
		- There are no announcements -
	@endforelse
</x-section-container>

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

