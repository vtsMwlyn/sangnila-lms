@extends("layouts.login-register")

@section("content")
	<div class="flex flex-col items-center justify-center rounded-2xl w-5/6 md:w-3/5 bg-orange-500 p-10">
		<div class="my-10 w-32 md:w-40">
			<img src={{ asset("img/AR.W.png") }} alt="logo" width="100%">
		</div>

		<div class="w-full md:w-4/5 bg-indigo-950 p-10 rounded-xl">
			<div class="mb-4 text-lg font-semibold text-white">
				{{ __('Welcome to Sangnila LMS!') }}
			</div>
			<div class="mb-4 text-md text-white">
				{{ __('Before getting started, you will need to activate your account by clicking on the button/link we\'ve just emailed to your account\'s email address.') }}
			</div>
			<div class="mb-4 text-md text-white">
				{{ __('If you didn\'t receive the email, please press resend verification email button below.') }}
			</div>

			@if (session('status') == 'verification-link-sent')
				<div class="mb-4 font-medium text-sm text-green-600">
					{{ __('A new verification link has been sent to your account\'s email address.') }}
				</div>
			@endif

			<div class="mt-8 flex items-center justify-between">
				<form method="POST" action="{{ route('verification.send') }}">
					@csrf

					<div>
						<x-button class="bg-orange-500">
							{{ __('Resend Verification Email') }}
						</x-button>
					</div>
				</form>

				<form method="POST" action="{{ route('logout') }}">
					@csrf

					<x-button class="bg-red-600">
						{{ __('Log Out') }}
					</x-button>
				</form>
			</div>
		</div>
	</div>
@endsection
