@props(["badge_text" => "Warning!"])

<div {{ $attributes->merge(["class" => "bg-yellow-400 text-orange-700 p-5 px-5 rounded-lg font-semibold mt-8 w-full flex justify-between items-center gap-3 badge"]) }}>
	<span><i class="bi bi-check-lg"></i> {!! $badge_text !!}</span>
	<button class="text-orange-700 border-none bg-none" style="shadow: none; cursor: url('{{ asset('img/cursor2.cur') }}'), pointer;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300);
	}
</script>
