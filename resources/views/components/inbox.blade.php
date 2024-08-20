@php
	$n = 0;
	foreach(Auth::user()->inboxes as $inbox){
		if($inbox->status == "unread"){
			$n++;
		}
	}
@endphp

<div class="relative flex flex-col items-end">
	@if($n > 0)
		<div class="absolute h-6 w-7 bg-red-600 rounded-full flex justify-center items-center text-white" style="top: -0.5rem; right: -0.5rem;">{{ $n }}</div>
	@endif
	<x-button class="bg-yellow-600" type="button" id="inbox-toggler">Inbox <i class="bi bi-chevron-compact-down"></i></x-button>
	<div class="absolute z-10 bg-white top-11 h-96 w-80 rounded-xl p-2 flex flex-col" id="inbox-dropdown" style="@if(!session()->has('successMarkAsRead')) display: none; @endif box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
		<div class="flex flex-col items-center gap-2 grow w-full text-xs py-2 px-1 overflow-y-auto">
			@forelse(Auth::user()->inboxes()->orderByRaw('CASE WHEN status = "unread" THEN 0 ELSE 1 END')->orderBy('created_at', 'desc')->get(); as $i => $inbox)
				@if($i != 0)
					<div class="w-full border border-slate-400"></div>
				@endif

				<div class="@if($inbox->status == "unread") font-bold bg-slate-200 @endif hover:bg-slate-100 text-blue-800 p-2">
					<div class="flex gap-3">
						<i class="bi bi-envelope-exclamation"></i>
						<p>{{ $inbox->message }}</p>
					</div>

					@if($inbox->status == "unread")
						<form action="{{ route('notification.mark-read', $inbox->id) }}" class="flex justify-end w-full mt-2" method="post">
							@csrf
							<button type="submit" class="font-semibold hover:underline text-orange-600">Mark as read</button>
						</form>
					@endif
				</div>
			@empty
				<div class="h-full w-full flex items-center justify-center text-blue-800 font-semibold">
					- There are currently no notifications -
				</div>
			@endforelse
		</div>
		<form action="{{ route('notification.mark-all-read') }}" class="flex justify-center w-full" method="post">
			@csrf
			<button type="submit" class="font-semibold hover:underline text-orange-400 text-sm">Mark all as read</button>
		</form>
	</div>
</div>

<script>
	$("#inbox-toggler").click(() => {
		$("#inbox-dropdown").toggle();
	});
</script>
