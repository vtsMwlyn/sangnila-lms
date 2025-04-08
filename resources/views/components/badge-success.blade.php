@props(["badge_text" => "Success!"])

<div {{ $attributes->merge(["class" => "w-full bg-light-blue text-white p-5 my-4 rounded-lg font-semibold flex justify-between items-center gap-3 badge"]) }}>
	<span><i class="bi bi-check-lg"></i> {!! $badge_text !!}</span>
	<button class="text-white border-none bg-none" style="shadow: none;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300);
	}
</script>
