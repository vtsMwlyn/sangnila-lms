@extends("layouts.login-register")

@section("content")
	<div class="flex rounded-2xl w-2/3 bg-orange-500" style="height: 80vh">
		<form method="POST" action="{{ route('login') }}" class="flex flex-col justify-center items-stretch w-1/2 p-10 bg-blue-950 rounded-2xl">
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

		<div class="flex flex-col items-center justify-center w-1/2">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100px">
			<img src={{ asset("img/loginimg.png") }} alt="logo" width="400px">
		</div>
	</div>
@endsection
