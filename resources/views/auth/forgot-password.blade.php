@extends("layouts.login-register")

@section("content")
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5  p-10">
		<div class="w-full md:w-4/5 p-10 rounded-xl" style="background: rgba(254, 254, 254, 0.7);">
			<div class="flex w-full justify-center">
				<img src="{{ asset('img/Sangnila_Arts.png') }}" class="h-24 w-24" alt="logo">
			</div>

			<div class="mb-4 text-md mt-6">
				{{ __('Forgot your password? No problem. Just let us know your account\'s email address and we will email you a password reset link that will allow you to choose a new one.') }}
			</div>

			{{-- Session Status --}}
			@if(session()->has('status'))
				<x-badge-success class="mb-4" badge_text="{{ session('status') }}"></x-badge-success>
			@endif

			<form method="POST" action="{{ route('password.email') }}" class="w-full">
				@csrf

				{{-- Email Address --}}
				<div>
					<x-label for="email" :value="__('Email')"/>
					<x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus placeholder="Enter your account's email" />
				</div>

				<div class="flex items-center justify-center mt-8">
					<x-button >
						{{ __('Email Password Reset Link') }}
					</x-button>
				</div>
			</form>
		</div>
	</div>
@endsection
