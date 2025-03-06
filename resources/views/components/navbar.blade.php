<!-- Main navbar -->
<div class="w-full text-white sticky top-0 z-20 transition duration-500" style="background-color: rgba(255, 255, 255, 1); backdrop-filter: blur(3px);" id="navbar-container">
	<div class="flex items-center justify-between sticky top-0 px-5 w-full space-x-5" id="navbar">
		<!-- Logo/Brand Image -->
		<div class="flex items-stretch gap-4">
			<div class="flex justify-center">
				<a href="{{ route('home') }}">
					<img src={{ asset("img/Sangnila_Arts.png") }} alt="logo" style="min-width: 65px; max-width: 65px;">
				</a>
			</div>

			<div class="w-0 border-blue my-2" style="border-width: 1.5px"></div>

			<div class="flex items-start flex-col justify-center">
				<div class="text-md md:text-lg text-blue font-semibold mb-1.5 md:mb-0">LEARNING MANAGEMENT SYSTEM</div>
				<div class="flex gap-1 text-slate-400 font-light italic" style="font-size: 8pt; margin-top: -5px">{{ trans("strings.version") }}<h1 id="screen"></h1></div>
			</div>
		</div>

		<div class="flex gap-4 items-center">
			<!-- INBOX -->
			@auth
				@php
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

				<div class="relative flex flex-col items-end dropdown-container">
					@if($n > 0)
						<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
					@endif
					<button type="button" class="dropdown-toggler"><img src="{{ asset('img/mail-icon.svg') }}" alt="mail-icon" class="h-6"></button>

					<div class="absolute z-10 bg-white top-16 w-96 rounded-3xl px-5 py-3 flex flex-col dropdown-menu" style="@if(!session()->has('successNotifAction')) display: none; @endif box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); height: 600px;">
						<div class="w-full flex items-center justify-between">
							<h1 class="font-extrabold text-dark-blue">Notifications</h1>
							@if($inboxes->count())
								@if($inboxes->where("status", "unread")->count())
									<form action="{{ route('notification.mark-all-read') }}" class="flex justify-center" method="post">
										@csrf
										<button type="submit" class="font-semibold hover:underline text-light-blue text-xs">Mark All Read</button>
									</form>
								@else
									<div type="button" class="font-bold text-slate-600 text-xs">Mark All Read</div>
								@endif
								{{-- <form action="{{ route('notification.dismiss-all') }}" class="flex justify-center" method="post">
									@csrf
									<button type="submit" class="font-semibold hover:underline text-orange-400 text-sm" onclick="return confirm('All of your read inbox will be cleared, are you sure want to proceed?');">Dismiss all</button>
								</form> --}}
							@endif
						</div>

						<div class="w-full bg-slate-400 mt-1 mb-3" style="height: 2px;"></div>

						<div class="flex flex-col items-center gap-3 grow w-full text-xs py-2 px-1 overflow-y-auto">
							@forelse($inboxes as $i => $inbox)
								<div class="@if($inbox->status == "unread") font-extrabold @endif rounded-3xl text-black px-4 py-5" style="background: linear-gradient(90deg, rgba(190, 226, 219, 0.49) 0%, rgba(104, 124, 120, 0) 100%);">
									<p>{{ $inbox->message }}</p>

									@if($inbox->status == "unread")
										<form action="{{ route('notification.mark-read', $inbox->id) }}" class="flex justify-end w-full mt-2" method="post">
											@csrf
											<button type="submit" class="font-semibold hover:underline text-light-blue">Mark Read</button>
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
					</div>
				</div>
			@endauth

			<div class="relative flex flex-col items-end dropdown-container">
				<button type="button" class="dropdown-toggler"><img src="{{ asset('img/burger-icon-navbar-pc.svg') }}" alt="burger-icon" class="h-6"></button>
				<div class="absolute z-10 bg-white top-16 w-80 rounded-3xl flex flex-col py-2 dropdown-menu overflow-hidden" style="display: none; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
					<a href="#"><div class="w-full px-5 py-1.5 text-black font-semibold flex items-center gap-1 hover:bg-slate-100"><img src="{{ asset('img/navbar-help-and-support.svg') }}" class="h-5 w-5" alt="sidebar-icon"> Help and Support</div></a>
					<a href="#"><div class="w-full px-5 py-1.5 text-black font-semibold flex items-center gap-1 hover:bg-slate-100"><img src="{{ asset('img/navbar-send-feedback.svg') }}" class="h-5 w-5" alt="sidebar-icon"> Send Feedback</div></a>
					<a href="{{ route("profile.show") }}" class="block lg:hidden"><div class="w-full px-5 py-0.5 text-black font-semibold flex items-center gap-1 hover:bg-slate-100"><i class="bi bi-person-fill text-slate-400 text-lg mr-0.5"></i> Profile</div></a>

					@auth
						<form method="POST" action="{{ route('logout') }}" class="grow flex items-center gap-2">
							@csrf
							<button
								class="w-full px-5 py-1 text-black font-bold flex items-center gap-1 hover:bg-slate-100" onclick="return confirm('Are you sure want to logout from your account?');">
								<img src="{{ asset('img/navbar-logout.svg') }}" class="h-5 w-5" alt="sidebar-icon"> {{ __('Log Out') }}
							</button>
						</form>
					@else
						<a href="{{ route("home") }}"><div class="w-full px-5 py-1 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/navbar-logout.svg') }}" class="h-5 w-5" alt="sidebar-icon"> {{ __('Log Out') }}</div></a>
					@endauth
				</div>
			</div>
		</div>
	</div>

    <script>
        // Toggle mobile menu visibility
        $("#mobileMenuButton").click(() => {
			$("#navbar").slideToggle();
		});
    </script>
</div>



