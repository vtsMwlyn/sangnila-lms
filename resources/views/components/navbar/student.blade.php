<!-- Main sidebar -->
<div class="w-full text-white sticky top-0 z-20 transition duration-500" style="background-color: rgba(255, 255, 255, 1); backdrop-filter: blur(3px);" id="navbar-container">
	<!-- Sidebar toggler for mobile -->
	<button id="mobileMenuButton" class="lg:hidden bg-blue-950 text-white font-semibold text-xl transition duration-300 absolute m-2 px-4 py-3 z-10">
		<span class="inline-block">&#9776;</span>
	</button>

	<div class="lg:flex flex-col lg:flex-row items-center justify-between sticky top-0 px-5 w-full hidden space-x-5" id="navigation">
		<!-- Logo/Brand Image -->
		<div class="flex items-stretch gap-4">
			<div class="flex justify-center">
				<a href="{{ route('home') }}" style="cursor: url({{ asset('img/cursor2.cur') }}), pointer;">
					<img src={{ asset("img/Sangnila_Arts.png") }} alt="logo" style="min-width: 65px; max-width: 65px;">
				</a>
			</div>

			<div class="w-0 border-blue my-2" style="border-width: 1.5px"></div>

			<div class="text-blue font-semibold flex items-center">LEARNING MANAGEMENT SYSTEM</div>
		</div>

		<div class="flex gap-4 items-center">
			<!-- INBOX -->
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

			<div class="relative flex flex-col items-end">
				@if($n > 0)
					<div class="absolute h-4 w-4 bg-red-600 rounded-full flex justify-center items-center text-white" style="top: -0.5rem; right: -0.5rem;"></div>
				@endif
				<button type="button" id="inbox-toggler"><img src="{{ asset('img/mail-icon.svg') }}" alt="mail-icon" class="h-6"></button>
				<div class="absolute z-10 bg-white top-16 h-96 w-80 rounded-xl p-2 flex flex-col" id="inbox-dropdown" style="@if(!session()->has('successNotifAction')) display: none; @endif box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
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

			<div class="relative flex flex-col items-end">
				<button type="button" id="burger-toggler"><img src="{{ asset('img/burger-icon-navbar-pc.svg') }}" alt="burger-icon" class="h-6"></button>
				<div class="absolute z-10 bg-white top-16 w-80 rounded-xl flex flex-col py-2" id="burger-dropdown" style="@if(!session()->has('successNotifAction')) display: none; @endif box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
					<a href="#"><div class="w-full px-5 py-1 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/navbar-help-and-support.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Help and Support</div></a>
					<a href="#"><div class="w-full px-5 py-1 text-black font-semibold flex items-center gap-1"><img src="{{ asset('img/navbar-send-feedback.svg') }}" class="h-4 w-4" alt="sidebar-icon"> Send Feedback</div></a>
					<form method="POST" action="{{ route('logout') }}" class="grow flex items-center gap-2">
						@csrf
						<button
							class="w-full px-5 py-1 text-black font-bold flex items-center gap-1" onclick="return confirm('Are you sure want to logout from your account?');">
							<img src="{{ asset('img/navbar-logout.svg') }}" class="h-5 w-5" alt="sidebar-icon"> {{ __('Log Out') }}
						</button>
					</form>
				</div>
			</div>

			<script>
				$(document).ready(function () {
					// Toggle dropdown on button click
					$("#inbox-toggler").click(function (e) {
						e.stopPropagation(); // Prevent the click event from bubbling up to the document

						$("#inbox-dropdown").toggle();
					});

					// Toggle dropdown on button click
					$("#burger-toggler").click(function (e) {
						e.stopPropagation(); // Prevent the click event from bubbling up to the document

						$("#burger-dropdown").toggle();
					});

					// Close dropdown when clicking outside of it
					$(document).click(function (e) {
						if (!$(e.target).closest("#inbox-dropdown, #burger-dropdown, #burger-toggler, #inbox-toggler").length) {
							$("#inbox-dropdown, #burger-dropdown").hide();
						}
					});
				});
			</script>
		</div>

		{{-- <a href="{{ route("profile.show") }}" class="flex flex-col items-center font-bold hover:text-yellow-400 hover:scale-110 transition ease-in-out text-white absolute top-4 lg:top-0 right-0 lg:relative w-1/12">
			<i class="bi bi-person-circle text-3xl "></i>
			<span class=" text-center text-sm mt-1">{{ Auth::user()->full_name }}</span>
		</a> --}}
	</div>

    <script>
        // Toggle mobile menu visibility
        $("#mobileMenuButton").click(() => {
			$("#navigation").slideToggle();
		});
    </script>
</div>



{{-- <nav class="bg-blue-200 w-full py-3 sticky top-0">
    <div class="container mx-auto flex flex-col md:flex-row items-center">
        <div class="flex items-center  ">
		<!-- Logo/Brand Image -->
		<a href="{{ route('home') }}" class="flex items-center pl-3 mb-1 md:mb-0">
			<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="Your Logo/Brand" width="70px" class="mr-20 ml-4 md:mr-2">
			<div class="flex flex-col">
				<span class="text-blue-700 text-2xl font-semibold hidden lg:flex ">SANGNILA LMS</span>
				@auth
					<span class="text-blue-700 text-md font-semibold hidden lg:flex ">Logged in as: {{ Auth::user()->full_name }}</span>
				@endauth
			</div>
		</a>

			<!-- Mobile Menu Button with 3-line icon -->
			<button id="mobileMenuButton"
				class="md:hidden text-indigo-700 font-semibold text-3xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 pl-40 pr-3 mx-1">
				<span class="inline-block">&#9776;</span> <!-- 3-line icon (hamburger) -->
			</button>

            <!-- Standard Navigation Links for Larger Screens -->
            <div class="hidden md:flex md:items-center md:space-x-4 md:justify-start px-14">
                <div class="flex items-center space-x-4 justify-start">
					<a href="{{ route('student.mycourse.index') }}"
						class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
						Courses
					</a>
					<a href="{{ route('student.assignment.index') }}"
						class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
						Assignments
					</a>
					<a href="{{ route('student.attendance.index') }}"
						class="text-indigo-700 font-semibold text-xl hover:text-blue-950 hover:scale-110 transition duration-300 py-2 px-5">
						Attendance
					</a>

                    <!-- Add more navigation links as needed -->
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class=" ml-auto hidden md:flex pr-10 ">
            @csrf
			<button type="submit"
            class="text-indigo-700 font-semibold text-xl transition duration-300 py-2 hover:text-red-500 px-1">
            {{ __('Log Out') }}
        </button>
        <svg class=" h-12 w-8 text-red-500 " width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
            <path d="M7 12h14l-3 -3m0 6l3 -3" />
        </svg>

        </form>

        <!-- Dropdown Menu (hidden by default on larger screens) -->
        <div id="mobileMenu" class="md:hidden mt-2 px-6 w-full" style="display: none;">
			<a href="{{ route('student.mycourse.index') }}"
				class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
				Courses
			</a>
			<a href="{{ route('student.assignment.index') }}"
				class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
				Assignments
			</a>
			<a href="{{ route('student.attendance.index') }}"
				class="block text-indigo-700 font-semibold text-md hover:text-blue-950 hover:scale-110 transition duration-300 py-1">
				Attendance
			</a>

            <!-- Add more navigation links as needed -->

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" >
                @csrf
                <button type="submit"
                    class="text-red-500 font-semibold text-md pt-1 pb-3">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle mobile menu visibility
        document.getElementById('mobileMenuButton').addEventListener('click', function () {
            var mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.style.display = (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') ? 'block' : 'none';
        });
    </script>
</nav> --}}



