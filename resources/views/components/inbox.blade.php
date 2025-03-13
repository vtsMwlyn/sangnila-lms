@php
	// Remove expired announcement


	// Remove 30 days read inboxes
	$n = 0;
	foreach(Auth::user()->inboxes as $inbox){
		if($inbox->status == "unread"){
			$n++;
		} else {
			$current_time = Carbon\Carbon::now();
			$inbox_age = Carbon\Carbon::parse($inbox->updated_at);

			$time_diff = $inbox_age->diffInDays($current_time);

			if($time_diff >= 30){
				App\Models\Notification::destroy($inbox->id);
			}
		}
	}

	$inboxes = Auth::user()->inboxes()->orderByRaw('CASE WHEN status = "unread" THEN 0 ELSE 1 END')->orderBy('created_at', 'desc')->get();
@endphp

<div class="relative flex flex-col items-end" id="inbox-container">
	@if($n > 0)
		<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
	@endif
	<x-button class="bg-yellow-600" type="button" id="inbox-toggler">Inbox <i class="bi bi-chevron-compact-down"></i></x-button>
	<div class="absolute z-10 bg-white top-11 h-96 w-80 rounded-xl p-2 flex flex-col" id="inbox-dropdown" style="@if(!session()->has('successNotifAction')) display: none; @endif ">
		<div class="flex flex-col items-center gap-2 grow w-full text-xs py-2 px-1 overflow-y-auto">
			@forelse($inboxes as $i => $inbox)
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
					@else
						{{-- <form action="{{ route('notification.dismiss', $inbox->id) }}" class="flex justify-end w-full mt-2" method="post">
							@csrf
							<button type="submit" class="font-semibold hover:underline text-orange-600">Dismiss</button>
						</form> --}}
					@endif
				</div>
			@empty
				<div class="h-full w-full flex flex-col items-center gap-5 justify-center text-blue-800 font-semibold">
					<img src="{{ asset("img/enpelop.png") }}" alt="envelope" class="w-1/2">
					<div>- There are currently no notifications -</div>
				</div>
			@endforelse
		</div>

		@if($inboxes->count())
			<div class="flex {{-- justify-between --}} justify-center w-full px-5">
				@if($inboxes->where("status", "unread")->count())
					<form action="{{ route('notification.mark-all-read') }}" class="flex justify-center" method="post">
						@csrf
						<button type="submit" class="font-semibold hover:underline text-orange-400 text-sm">Mark all as read</button>
					</form>
				@else
					<div type="button" class="font-bold text-slate-600 text-sm">Mark all as read</div>
				@endif
				{{-- <form action="{{ route('notification.dismiss-all') }}" class="flex justify-center" method="post">
					@csrf
					<button type="submit" class="font-semibold hover:underline text-orange-400 text-sm" onclick="return confirm('All of your read inbox will be cleared, are you sure want to proceed?');">Dismiss all</button>
				</form> --}}
			</div>
		@endif
	</div>
</div>

<script>
	$(document).ready(function () {
		// Toggle dropdown on button click
		$("#inbox-toggler").click(function (e) {
			e.stopPropagation(); // Prevent the click event from bubbling up to the document

			$("#inbox-dropdown").toggle();
		});

		// Close dropdown when clicking outside of it
		$(document).click(function (e) {
			if (!$(e.target).closest("#inbox-dropdown, #inbox-toggler").length) {
				$("#inbox-dropdown").hide();
			}
		});
	});
</script>
