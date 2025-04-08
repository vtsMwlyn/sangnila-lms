@props(["badge_text" => "Warning!"])

<div {{ $attributes->merge(["class" => "bg-light-blue text-white p-5 rounded-2xl font-semibold my-4 w-full flex justify-between items-center gap-3 badge"]) }}>
	<div class="flex gap-3 items-center"><img src="{{ asset('img/badge-success-icon.svg') }}" class="h-6 w-6" alt="icon"> {!! $badge_text !!}</div>
	<button class="text-white border-none bg-none" style="shadow: none;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300);
	}
</script>
