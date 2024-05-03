<x-guest-layout>
	<x-auth-card>
		<x-slot name="logo">
		</x-slot>

		<form method="POST" action="{{ route('store_register') }}">
			@csrf

			<!-- Name -->
			<div>
				<x-label for="name" :value="__('Name')" />

				<x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
			</div>

			<!-- Email Address -->
			<div class="mt-4">
				<x-label for="email" :value="__('Email')" />

				<x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
			</div>

			<!-- Password -->
			<div class="mt-4">
				<x-label for="password" :value="__('Password')" />

				<x-input id="password" class="block mt-1 w-full" type="password" name="password" required
					autocomplete="new-password" />
			</div>

			<!-- Confirm Password -->
			<div class="mt-4">
				<x-label for="password_confirmation" :value="__('Confirm Password')" />

				<x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"
					required />
			</div>

			<!-- Role Selection -->
			<div class="mt-4">
				<x-label for="role" :value="__('Role')" />

				{{-- <x-input id="role" class="block mt-1 w-full" type="text" name="role" :value="old('role')" required /> --}}

				<select name="role" id="role" class="block mt-1 w-full">
					@forelse ($roles as $role)
						<option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
					@empty
					@endforelse

				</select>
			</div>

			<div class="flex items-center justify-end mt-4">
				<a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('home') }}">
					{{ __('Already registered?') }}
				</a>

				<x-button class="ml-4">
					{{ __('Register') }}
				</x-button>
			</div>
		</form>
	</x-auth-card>
</x-guest-layout>
