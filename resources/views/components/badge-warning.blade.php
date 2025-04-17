@props(["badge_text" => "Warning!"])

<div {{ $attributes->merge(["class" => "flex xl:hidden bg-yellow-600 text-white p-5 rounded-2xl font-semibold my-4 w-full justify-between items-center gap-3 badge"]) }}>
	<div class="flex gap-3 items-center"><i class="bi bi-exclamation-diamond-fill"></i> {!! $badge_text !!}</div>
	<button class="text-white border-none bg-none" style="shadow: none;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300);
	}
</script>
