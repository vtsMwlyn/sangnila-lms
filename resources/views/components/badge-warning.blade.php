@props(["badge_text" => "Warning!"])

<div {{ $attributes->merge(["class" => "bg-light-blue text-white p-5 rounded-lg font-semibold my-4 w-full flex justify-between items-center gap-3 badge"]) }}>
	<span><i class="bi bi-check-lg"></i> {!! $badge_text !!}</span>
	<button class="text-white border-none bg-none" style="shadow: none;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300);
	}
</script>
