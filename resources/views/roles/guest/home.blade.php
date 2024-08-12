@extends("layouts.login-register")

@section("content")
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5 bg-orange-500 p-10">
		<div class="my-10 w-32 md:w-52">
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
				<button type="button" class="absolute right-3 top-3.5 text-slate-500 font-bold" id="togglePassword"></button>
				<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px; padding-right: 50px;"
					autocomplete="current-password" placeholder="Password" />
			</div>

			<!-- Remember Me -->
			<div class="mt-8 flex items-center">
				<input type="checkbox" name="remember" id="remember_me" class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" />
				<label for="remember_me" class="text-white">Remember me</label>
			</div>

            {{-- <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember_me">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

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

			// $("#login-form").on("submit", function(e) {
			// 	e.preventDefault();

			// 	// Remove any existing hidden input with the name "remember"
			// 	$('input[type="hidden"][name="remember"]').remove();

			// 	// Determine the value for the hidden input based on the checkbox state
			// 	let cbv = $("#remember_me").is(":checked") ? 1 : 0;

			// 	// Append the new hidden input with the appropriate value
			// 	$(this).append($("<input>").attr({
			// 		"type": "hidden",
			// 		"name": "remember",
			// 		"value": cbv
			// 	}));

			// 	this.submit();
			// });
		</script>
	</div>
@endsection
