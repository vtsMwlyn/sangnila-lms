<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('site.webmanifest') }}">

	<title>Sangnila Academy| LMS</title>

</head>

<body>
	<div class="bg-cover h-screen" style="background-image: url({{ asset('img/background.png') }}) ; width: 100%;">
		<div class="flex justify-center align-items-center h-screen">
			<div class="w-96 my-auto">
				<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="" width="200px" class="m-auto">

				{{-- <div class="flex justify-center mx-4 my-6">
					<a
						class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
						href="{{ route('guest.index') }}">
						Login as guest
					</a>
				</div> --}}

				{{-- <!-- Session Status -->
				<x-auth-session-status class="mb-4" :status="session('status')" />

				<!-- Validation Errors -->
				<x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}

				<form method="POST" action="{{ route('login') }}" class="bg-white rounded-2xl p-5  border-blue-300 border-2">
					@csrf

					<!-- Email Address -->
					<div>
						<x-label for="email" :value="__('Email')" />
						<x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
							autofocus />
					</div>

					<!-- Password -->
					<div class="mt-4">
						<x-label for="password" :value="__('Password')" />
						<x-input id="password" class="block mt-1 w-full" type="password" name="password" required
							autocomplete="current-password" />
					</div>

					<!-- Remember Me -->
					<div class="block mt-4">
						<label for="remember_me" class="inline-flex items-center">
							<input id="remember_me" type="checkbox"
								class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
								name="remember">
							<span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
						</label>
					</div>

					<div class="flex items-center justify-end mt-2">
						@if (Route::has('password.request'))
							<a class="underline text-sm text-gray-300 hover:text-gray-900" href="{{ route('password.request') }}">
								{{ __('Forgot your password?') }}
							</a>
						@endif

						<x-button class="ml-3">
							{{ __('Log in') }}
						</x-button>
					</div>
				</form>
				<div class="flex justify-center my-2">
					<a class="text-lg font-weight-bold underline text-blue-800 hover:text-blue-950 bg-blue-200 px-5 py-2 rounded-lg"
						href="{{ route('guest.index') }}">
						{{ __('Login As Guest') }}
					</a>
				</div>
			</div>
		</div>
	</div>

</body>

</html>
