@extends("layouts.login-register")

@section("content")
	<div class="flex items-center rounded-2xl w-11/12 md:w-2/3 bg-orange-500 p-5">
		<div class="md:flex flex-col items-center justify-center hidden w-0 md:w-1/2">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100px">
			<img src={{ asset("img/loginpict.png") }} alt="logo" width="400px">
		</div>
		<form method="POST" action="{{ route('admin.account.store') }}" class="flex flex-col justify-center items-stretch w-full md:w-1/2 py-6 px-10 bg-blue-950 rounded-2xl">
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

			<div class="mt-4 flex flex-col md:flex-row gap-4 w-full">
				<!-- Role Selection -->
				<div class="w-full md:w-1/2">
					<x-label for="role" class="text-white" :value="__('Select Role')" />
					<x-select name="role" id="role"
						class="mt-1 w-full">
						<option selected disabled>Pick a Role</option>
						@forelse ($roles as $role)
							<option value="{{ $role->role_name }}" @if(old("role") == $role->role_name) selected @endif>{{ $role->role_name }}</option>
						@empty
						@endforelse
					</x-select>
				</div>

				<!-- Gender Selection -->
				<div class="w-full md:w-1/2">
					<x-label for="gender" class="text-white" :value="__('Select Gender')" />
					<x-select name="gender" id="gender"
						class="mt-1 w-full">
						<option selected disabled>Pick a Gender</option>
						@forelse ($gender as $index => $g)
							<option value="{{ $index + 1 }}" @if(old("gender") == $g) selected @endif>{{ $g }}</option>
						@empty
						@endforelse
					</x-select>
				</div>
			</div>

			<div class="flex justify-center w-full gap-3 items-center mt-8">
				<x-button class="bg-orange-500 w-full md:w-1/3">
					{{ __('Create') }}
				</x-button>
				<x-button type="button" onclick="if(confirm('The filled data will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/3">
					Cancel
				</x-button>
			</div>
		</form>
	</div>
@endsection
