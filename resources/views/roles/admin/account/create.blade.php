@extends("layouts.login-register")

@section("content")
<div class="w-11/12 h-screen flex justify-evenly items-center" style="max-width: 2000px;">
	<img src="{{ asset('img/loginwords.svg') }}" class="md:block hidden w-2/5" alt="login-words">

	<form method="POST" action="{{ route('admin.account.store') }}" class="flex flex-col overflow-y-auto items-center w-full md:w-1/2 h-5/6 py-6 rounded-2xl shadow-xl" style="background: rgba(254, 254, 254, 0.7); max-width: 36vw;">
		@csrf
			<div class="w-full flex justify-center">
				<img src="{{ asset('img/Sangnila_Arts.png') }}" class="h-24 w-24" alt="logo">
			</div>

			<div class="text-center">
				<h1 class="font-extrabold text-blue text-3xl">Create New Account</h1>
			</div>

			<div class="w-4/5 flex flex-col items-stretch">
				{{-- Name --}}
				<div class="mt-8">
					<x-input id="name" class="w-full rounded-xl" type="text" name="name" :value="old('name')" placeholder="Full Name" style="height: 50px" autofocus />
				</div>

				{{-- Email Address --}}
				<div class="mt-4">
					<x-input id="email" class="w-full rounded-xl" type="email" name="email" :value="old('email')" placeholder="Email Address" style="height: 50px" />
				</div>

				{{-- Password --}}
				<div class="mt-4 relative">
					<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="togglePassword"></button>
					<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="height: 50px; padding-right: 60px;"
						autocomplete="current-password" placeholder="Password" value="{{ trans('strings.default_password') }}"/>
				</div>

				{{-- Password Confirmation --}}
				<div class="mt-4 relative">
					<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="toggleConfPassword"></button>
					<x-input id="password_confirmation" class="w-full rounded-xl" type="password" name="password_confirmation" style="height: 50px; padding-right: 60px;"
						autocomplete="current-password" placeholder="Password Confirmation" value="{{ trans('strings.default_password') }}"/>
				</div>

				<div class="mt-4 flex items-center">
					<input type="checkbox" id="use_default_password" name="use_default_password" class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" checked>
					<x-label for="user_default_password" class="text-blue font-semibold">Use default password</x-label>
				</div>

				<div class="mt-4 flex flex-col md:flex-row gap-4 w-full">
					{{-- Role Selection --}}
					<div class="w-full md:w-1/2">
						<x-label for="role" class="text-blue" :value="__('Select Role')" />
						<x-select name="role" id="role"
							class="mt-1 w-full">
							<option selected disabled>Pick a Role</option>
							@forelse ($roles as $role)
								@if($role->role_name == 'Finance Admin')
									@continue
								@endif
								<option value="{{ $role->role_name }}" @if(old("role") == $role->role_name) selected @endif>{{ $role->role_name }}</option>
							@empty
							@endforelse
						</x-select>
					</div>

					{{-- Gender Selection --}}
					<div class="w-full md:w-1/2">
						<x-label for="gender" class="text-blue" :value="__('Select Gender')" />
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
					<x-button class=" w-full md:w-1/3">
						{{ __('Create') }}
					</x-button>
					<x-cancel-button class="w-full md:w-1/3">
						Cancel
					</x-cancel-button>
				</div>
			</div>

	</form>

	<script>
		let toggleStatus = 0;

		$(document).ready(function(){
			$("#togglePassword").html('<i class="bi bi-eye"></i>');
			$("#toggleConfPassword").html('<i class="bi bi-eye"></i>');
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

		$("#toggleConfPassword").click(function(){
			if(toggleStatus == 0){
				$(this).html('<i class="bi bi-eye-slash"></i>');
				$("#password_confirmation").attr("type", "text");
				toggleStatus = 1;
			}
			else {
				$(this).html('<i class="bi bi-eye"></i>');
				$("#password_confirmation").attr("type", "password");
				toggleStatus = 0;
			}
		});

		$("#use_default_password").change(function(){
			if($(this).is(":checked")){
				$("#password").val("s4ngnil4@7xB");
				$("#password_confirmation").val("s4ngnil4@7xB");
			}
			else {
				$("#password").val("");
				$("#password_confirmation").val("");
			}
		});
	</script>
</div>
@endsection
