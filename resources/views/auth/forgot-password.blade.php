@extends("layouts.login-register")

@section("content")
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5 bg-orange-500 p-10">
		<div class="my-10 w-32 md:w-40">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100%">
		</div>

		<div class="w-full md:w-4/5 bg-indigo-950 p-10 rounded-xl">
			<div class="mb-4 text-md text-white">
				{{ __('Forgot your password? No problem. Just let us know your account\'s email address and we will email you a password reset link that will allow you to choose a new one.') }}
			</div>

			<!-- Session Status -->
			<x-auth-session-status class="mb-4" :status="session('status')" />

			<form method="POST" action="{{ route('password.email') }}" class="w-full">
				@csrf

				<!-- Email Address -->
				<div>
					<x-label for="email" :value="__('Email')" style="color: white"/>
					<x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus />
				</div>

				<div class="flex items-center justify-center mt-8">
					<x-button class="bg-orange-500">
						{{ __('Email Password Reset Link') }}
					</x-button>
				</div>
			</form>
		</div>
	</div>
@endsection
