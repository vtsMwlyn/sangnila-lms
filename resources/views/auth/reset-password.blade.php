@extends("layouts.login-register")

@section("content")
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5  p-10">
		<div class="my-10 w-32 md:w-40">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100%">
		</div>

		<div class="w-full md:w-4/5 bg-indigo-950 p-10 rounded-xl">
			<div class="mb-4 text-md text-white">
				{{ __('Your action to do password reset for the email inputted has been confirmed. Now you can enter the new password for your account. After clicking reset password, you can login to your account using your new password.') }}
			</div>

			<form method="POST" action="{{ route('password.update') }}">
				@csrf

				<!-- Password Reset Token -->
				<input type="hidden" name="token" value="{{ $request->route('token') }}">

				<!-- Email Address -->
				<div>
					<x-label for="email" :value="__('Email')" style="color: white;"/>

					<x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" autofocus />
				</div>

				<!-- Password -->
				<div class="mt-4">
					<x-label for="password" :value="__('Password')" style="color: white;"/>

					<x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
				</div>

				<!-- Confirm Password -->
				<div class="mt-4">
					<x-label for="password_confirmation" :value="__('Confirm Password')" style="color: white;"/>

					<x-input id="password_confirmation" class="block mt-1 w-full"
										type="password"
										name="password_confirmation" required />
				</div>

				<div class="flex items-center justify-center mt-8">
					<x-button >
						{{ __('Reset Password') }}
					</x-button>
				</div>
			</form>
		</div>
	</div>
@endsection
