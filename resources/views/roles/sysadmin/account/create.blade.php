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

	<title>Sangnila Academy | LMS</title>

</head>

<body>
	<div class="bg-cover h-screen" style="background-image: url({{ asset('img/background.png') }}) ; width: 100%;">
		<div class="flex justify-center align-items-center h-screen">
			<div class="w-96 my-auto">
				<img src="{{ asset('img/Sangnila_Arts.png') }}" alt="" width="200px" class="m-auto">

				<form method="POST" action="{{ route('sysadmin.account.store') }}"
					class="bg-white rounded-2xl p-5  border-blue-300 border-2">
					@csrf

					<!-- Name -->
					<div>
						<x-label for="name" :value="__('Name')" />

						<x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
							autofocus />
					</div>

					<!-- Email Address -->
					<div class="mt-4">
						<x-label for="email" :value="__('Email')" />
						<x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus />
					</div>

					<!-- Password -->
					<div class="mt-4">
						<x-label for="password" :value="__('Password')" />
						<x-input id="password" class="block mt-1 w-full" type="password" name="password"
							autocomplete="current-password" />
					</div>

					<!-- Confirm Password -->
					<div class="mt-4">
						<x-label for="password_confirmation" :value="__('Confirm Password')" />

						<x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
					</div>

					<!-- Role Selection -->
					<div class="mt-4">
						<x-label for="role" :value="__('Role')" />

						<select name="role" id="role"
							class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-1">
							@forelse ($roles as $role)
								@if ($role->role_name !== 'SysAdmin')
									<option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
								@endif
							@empty
							@endforelse

						</select>
					</div>
					<div class="flex items-center justify-end mt-2">

						<x-button class="ml-3">
							{{ __('Create Account') }}
						</x-button>
					</div>
				</form>

			</div>
		</div>
	</div>

</body>

</html>
