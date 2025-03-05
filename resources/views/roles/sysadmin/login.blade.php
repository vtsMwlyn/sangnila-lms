@extends("layouts.login-register")

@section("content")
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5  p-6">
		<div class="my-6 w-32 md:w-44">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100%">
		</div>

		<form method="POST" action="{{ route('sysadmin.login') }}" class="flex flex-col justify-center items-stretch w-full md:w-1/2 mt-3 md:mt-5 md:mb-5 md:m-0 p-5 md:p-10 bg-blue-950 rounded-2xl" id="login-form">
			@csrf

			<!-- Session Status -->
			<x-auth-session-status class="mb-4" :status="session('status')" />

			@if(session()->has("status"))
				<x-badge-success badge_text="{{ session('status') }}"></x-badge-success>
			@endif

			<p class="text-xl font-extrabold text-center text-yellow-500">(FOR MAINTENANCE ONLY)</p>

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

			<div class="mt-12 w-full flex justify-center">
				<x-button class=" w-1/2">
					{{ __('LOGIN') }}
				</x-button>
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
