@props(["badge_text" => "Failed/error!"])

<div class="w-full bg-red-700 text-red-100 p-5 mt-8 font-semibold rounded-lg flex items-center justify-between gap-3 badge">
	<span><i class="bi bi-exclamation-circle"></i> {{ $badge_text }}</span>
	<button class="text-green-100 border-none bg-none" style="shadow: none;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300, function() {
            $(this).remove();
        });
	}
</script>
