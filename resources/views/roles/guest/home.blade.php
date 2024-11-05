@extends("layouts.login-register")

@section("content")
	<div class="w-11/12 h-screen flex justify-evenly items-center" style="max-width: 2000px;">
		<img src="{{ asset('img/loginwords.svg') }}" class="md:block hidden w-2/5" alt="login-words">

		<form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6 justify-center items-center w-full md:w-1/2 h-5/6 rounded-2xl overflow-y-auto shadow-xl" id="login-form" style="background: rgba(254, 254, 254, 0.7); max-width: 36vw;">
			@csrf

			<img src="{{ asset('img/Sangnila_Arts.png') }}" class="h-24 w-24" alt="logo">

			<div class="text-center">
				<h1 class="font-extrabold text-blue text-3xl">Welcome to</h1>
				<h1 class="font-extrabold text-blue text-3xl">Sangnila Arts Academy!</h1>
			</div>

			<div class="w-4/5 flex flex-col items-stretch">
				<!-- Session Status -->
				<x-auth-session-status class="mb-4" :status="session('status')" />

				@if(session()->has("status"))
					<x-badge-success badge_text="{{ session('status') }}"></x-badge-success>
				@endif

				<!-- Email Address -->
				<div class="mt-4">
					<x-input id="email" class="w-full rounded-xl" type="email" name="email" :value="old('email')" placeholder="Email Address" style="height: 50px" autofocus />
				</div>

				<!-- Password -->
				<div class="mt-6 relative">
					<button type="button" class="absolute right-2 h-full text-slate-400 font-bold w-12" id="togglePassword"></button>
					<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px; padding-right: 60px;"
						autocomplete="current-password" placeholder="Password" />
				</div>

				<div class="w-full flex justify-between mt-3 items-center">
					<!-- Remember Me -->
					<div class="flex items-center">
						<input type="checkbox" name="remember" id="remember_me" class="mr-2 form-checkbox h-5 w-5 border-2 rounded border-slate-400 text-blue-500 bg-white" />
						<label for="remember_me" class="text-dark-blue">Remember me</label>
					</div>

					@if (Route::has('password.request'))
						<a class="underline text-sm text-dark-blue hover:text-slate-600 text-right" href="{{ route('password.request') }}">
							{{ __('Forgot your password?') }}
						</a>
					@endif
				</div>

				<div class="mt-12 w-full flex justify-center">
					<x-button class="bg-orange-500 w-full py-4">
						{{ __('LOGIN') }}
					</x-button>
				</div>

				<a class="underline text-sm text-dark-blue hover:text-slate-600 text-right mt-4"
					href="{{ route('guest.index') }}">
					{{ __('Or login as guest') }}
				</a>
			</div>
		</form>

		<script>
			let toggleStatus = 0;

			$(document).ready(function(){
				$("#togglePassword").html('<i class="bi bi-eye"></i>');
			});

			$("#togglePassword").click(function(){
				if(toggleStatus == 0){
					$(this).html('<i class="bi bi-eye-slash"></i>');
					$("#password").attr("type", "text");
					toggleStatus = 1;
				}
				else {
					$(this).html('<i class="bi bi-eye"></i>');
					$("#password").attr("type", "password");
					toggleStatus = 0;
				}
			});
		</script>
	</div>
	{{-- <div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5 bg-orange-500 p-6">
		<div class="my-6 w-32 md:w-44">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100%">
		</div>

		<form method="POST" action="{{ route('login') }}" class="flex flex-col justify-center items-stretch w-full md:w-1/2 mt-3 md:mt-10 md:m-0 p-5 md:p-10 bg-blue-950 rounded-2xl" id="login-form">
			@csrf

			<!-- Session Status -->
			<x-auth-session-status class="mb-4" :status="session('status')" />

			@if(session()->has("status"))
				<x-badge-success badge_text="{{ session('status') }}"></x-badge-success>
			@endif

			<!-- Email Address -->
			<div class="mt-5">
				<x-input id="email" class="w-full rounded-xl" type="email" name="email" :value="old('email')" placeholder="Email Address" style="height: 50px" autofocus />
			</div>

			<!-- Password -->
			<div class="mt-8 relative">
				<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="togglePassword"></button>
				<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px; padding-right: 60px;"
					autocomplete="current-password" placeholder="Password" />
			</div>

			<!-- Remember Me -->
			<div class="mt-8 flex items-center">
				<input type="checkbox" name="remember" id="remember_me" class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" />
				<label for="remember_me" class="text-white">Remember me</label>
			</div>

			@if (Route::has('password.request'))
				<a class="underline text-sm text-white hover:text-yellow-500 text-right" href="{{ route('password.request') }}">
					{{ __('Forgot your password?') }}
				</a>
			@endif

			<div class="mt-12 w-full flex justify-center">
				<x-button class="bg-orange-500 w-1/2">
					{{ __('LOGIN') }}
				</x-button>
			</div>
		</form>

		<a class="text-lg text-blue-950 mt-10 font-bold hover:scale-110 transition duration-300 ease-in-out px-5 py-2 rounded-lg"
			href="{{ route('guest.index') }}">
			{{ __('Login As Guest') }}
		</a>

		<script>
			let toggleStatus = 0;

			$(document).ready(function(){
				$("#togglePassword").html('<i class="bi bi-eye"></i>');
			});

			$("#togglePassword").click(function(){
				if(toggleStatus == 0){
					$(this).html('<i class="bi bi-eye-slash"></i>');
					$("#password").attr("type", "text");
					toggleStatus = 1;
				}
				else {
					$(this).html('<i class="bi bi-eye"></i>');
					$("#password").attr("type", "password");
					toggleStatus = 0;
				}
			});
		</script>
	</div> --}}
@endsection
