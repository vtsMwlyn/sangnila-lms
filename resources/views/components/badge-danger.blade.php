@props(["badge_text" => "Failed/error!"])

<div {{ $attributes->merge(["class" => "flex xl:hidden w-full bg-red text-white p-5 my-4 font-semibold rounded-2xl items-center justify-between gap-3 badge"]) }}>
	<div class="flex gap-3 items-center"><img src="{{ asset('img/badge-danger-icon.svg') }}" class="h-6 w-6" alt="icon"> {!! $badge_text !!}</div>
	<button class="text-white border-none bg-none" style="shadow: none;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300);
	}
</script>
