@extends("layouts.login-register")

@section("content")
	{{-- <div class="flex rounded-2xl w-11/12 md:w-2/3 bg-orange-500" style="min-height: 80vh;">
		<form method="POST" action="{{ route('login') }}" class="flex flex-col justify-center items-stretch w-full md:w-1/2 m-5 md:m-0 p-10 bg-blue-950 rounded-2xl">
			@csrf

			<h1 class="text-center text-2xl font-bold text-white">LOGIN</h1>
			<h1 class="text-center text-md text-white">Login to your account</h1>

			<!-- Email Address -->
			<div class="mt-8">
				<x-input id="email" class="w-full rounded-xl" type="email" name="email" :value="old('email')" placeholder="Email Address" style="height: 50px" autofocus />
			</div>

			<!-- Password -->
			<div class="mt-4">
				<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px"
					autocomplete="current-password" placeholder="Password" />
			</div>

			<div class="flex flex-col items-center mt-8">
				<x-button class="bg-orange-500 w-1/2">
					{{ __('LOGIN') }}
				</x-button>

				<a class="text-sm mt-3 text-white hover:text-slate-400 hover:scale-110 transition duration-300 px-5 py-2 rounded-lg"
					href="{{ route('guest.index') }}">
					{{ __('Login As Guest') }}
				</a>
			</div>
		</form>

		<div class="hidden md:flex flex-col items-center justify-center w-full md:w-1/2">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100px">
			<img src={{ asset("img/loginpict.png") }} alt="logo" width="400px">
		</div>
	</div> --}}
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5 bg-orange-500 p-10">
		<div class="my-10 w-32 md:w-52">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100%">
		</div>

		<form method="POST" action="{{ route('login') }}" class="flex flex-col justify-center items-stretch w-full md:w-1/2 mt-3 md:mt-10 md:m-0 p-5 md:p-10 bg-blue-950 rounded-2xl">
			@csrf

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

			<div class="mt-8 flex items-center">
				<input type="checkbox" name="remember_me" id="remember_me" class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" />
				<label for="remember_me" class="text-white">Remember me</label>
			</div>

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
	</div>
@endsection
