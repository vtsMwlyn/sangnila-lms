@extends("layouts.login-register")

@section("content")
	<div class="w-11/12 min-h-screen flex justify-evenly items-center" style="max-width: 2000px;">
		<img src="{{ asset('img/loginwords.svg') }}" class="xl:block hidden w-2/5" alt="login-words">

		<form method="POST" action="{{ route('login') }}" class="flex flex-col overflow-y-auto justify-center items-center w-full md:w-3/5 xl:w-2/5 py-6 md:py-8 xl:py-16 rounded-2xl shadow-xl" style="background: rgba(254, 254, 254, 0.7);" id="login-form">
			@csrf

			<img src="{{ asset('img/Sangnila_Arts.png') }}" class="h-24 w-24" alt="logo">

			<div class="text-center">
				<h1 class="font-extrabold text-blue text-2xl md:text-3xl">Welcome to</h1>
				<h1 class="font-extrabold text-blue text-2xl md:text-3xl">Sangnila Arts Academy!</h1>
			</div>

			<div class="w-4/5 flex flex-col items-stretch mt-8">
				{{-- Session Status --}}
				<x-auth-session-status class="mb-4" :status="session('status')" />

				@if(session()->has("success"))
					<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
				@elseif(session()->has("warning"))
					<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
				@elseif(session()->has("danger"))
					<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
				@endif

				{{-- Email Address --}}
				<div class="mt-4">
					<x-input id="email" class="w-full rounded-xl" type="email" name="email" :value="old('email')" placeholder="Email Address" style="height: 50px" autofocus />
				</div>

				{{-- Password --}}
				<div class="mt-6 relative">
					<button type="button" class="absolute right-2 h-full text-slate-400 font-bold w-12" id="togglePassword"></button>
					<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px; padding-right: 60px;"
						autocomplete="current-password" placeholder="Password" />
				</div>

				<div class="w-full flex justify-between mt-0 md:mt-3 items-center">
					{{-- Remember Me --}}
					<div class="flex items-center">
						<input type="checkbox" name="remember" id="remember_me" class="mr-2 form-checkbox h-5 w-5 border-2 rounded border-slate-400 text-blue-500 bg-white" />
						<label for="remember_me" class="text-dark-blue">Remember me</label>
					</div>

					@if (Route::has('password.request'))
						<a class="underline text-sm text-dark-blue hover:text-slate-600 text-right mt-16 md:mt-0" href="{{ route('password.request') }}">
							{{ __('Forgot your password?') }}
						</a>
					@endif
				</div>

				<div class="mt-6 md:mt-12 w-full flex justify-center">
					<x-button class=" w-full py-4">
						{{ __('LOGIN') }}
					</x-button>
				</div>

				<div class="mt-4 flex justify-between items-center">
					<a class="underline text-sm text-dark-blue hover:text-slate-600"
						href="https://register.sangnilaindonesia.com/trial-class/book">
						{{ __('Trial Class') }}
					</a>

					<a class="underline text-sm text-dark-blue hover:text-slate-600"
						href="{{ route('guest.index') }}">
						{{ __('Go Without Account') }}
					</a>
				</div>
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
@endsection
