@extends("layouts.login-register")

@section("content")
	<div class="flex items-center rounded-2xl w-2/3 bg-orange-500 p-5">
		<div class="flex flex-col items-center justify-center w-1/2">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100px">
			<img src={{ asset("img/loginpict.png") }} alt="logo" width="400px">
		</div>
		<form method="POST" action="{{ route('admin.account.store') }}" class="flex flex-col justify-center items-stretch w-1/2 py-6 px-10 bg-blue-950 rounded-2xl">
			@csrf

			<h1 class="text-center text-2xl font-bold text-white">CREATE NEW ACCOUNT</h1>
			<h1 class="text-center text-md text-white">Register new account</h1>

			<!-- Name -->
			<div class="mt-8">
				<x-input id="name" class="w-full rounded-xl" type="text" name="name" :value="old('name')" placeholder="Full Name" style="height: 50px" autofocus />
			</div>

			<!-- Email Address -->
			<div class="mt-4">
				<x-input id="email" class="w-full rounded-xl" type="email" name="email" :value="old('email')" placeholder="Email Address" style="height: 50px" />
			</div>

			<!-- Password -->
			<div class="mt-4">
				<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px"
					autocomplete="current-password" placeholder="Password" />
			</div>

			<!-- Password Confirmation -->
			<div class="mt-4">
				<x-input id="password_confirmation" class="w-full rounded-xl" type="password" name="password_confirmation" style="height: 50px"
					autocomplete="current-password" placeholder="Password Confirmation" />
			</div>

			<!-- Role Selection -->
			<div class="mt-4">
				<x-label for="role" class="text-white" :value="__('Select Role')" />

				<select name="role" id="role"
					class="rounded-md w-1/3 px-4 py-2 shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-1">
					@forelse ($roles as $role)
						<option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
					@empty
					@endforelse

				</select>
			</div>

			<div class="flex justify-end gap-1 items-center mt-8">
				<x-button type="button" onclick="history.back()" class="bg-orange-500">
					Cancel
				</x-button>
				<x-button class="bg-orange-500 w-1/3">
					{{ __('Create') }}
				</x-button>
			</div>
		</form>
	</div>
@endsection
