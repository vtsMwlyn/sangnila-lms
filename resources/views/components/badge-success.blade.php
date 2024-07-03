@props(["badge_text" => "Success!"])

<div class="w-full bg-green-600 text-green-100 p-5 mt-8 rounded-lg font-semibold flex justify-between items-center gap-3 badge">
	<span><i class="bi bi-check-lg"></i> {!! $badge_text !!}</span>
	<button class="text-green-100 border-none bg-none" style="shadow: none;cursor: url('{{ asset('img/cursor2.cur') }}'), pointer;" onclick="closeBadge(this);"><i class="bi bi-x-lg"></i></button>
</div>

<script>
	function closeBadge(element){
		$(element).closest('.badge').fadeOut(300, function() {
            $(this).remove();
        });
	}
</script>
