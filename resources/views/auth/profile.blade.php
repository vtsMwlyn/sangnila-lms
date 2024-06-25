<x-section-container>
	<x-page-title class="mt-5 mb-8">{{ __("Account Profile") }}</x-page-title>

	@if(session()->has("successUpdateProfile"))
		<div class="w-full bg-green-500 px-5 py-3 mt-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateProfile") }}</p>
		</div>
	@elseif(session()->has("successPay"))
		<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
			<p class="text-green-900">{{ session("successPay") }}</p>
		</div>
	@endif

	<div class="mt-5">
		<form action="{{ route("profile.update") }}" method="post">
			@csrf
			<!-- Name -->
			<div class="flex gap-3 items-stretch">
				<x-boxed-label for="full_name">{{ __("Full Name") }}</x-boxed-label>
				<x-input id="full_name" class="w-full" type="text" name="full_name" :value="old('full_name', $account_data->full_name)" placeholder="Full Name" />
			</div>

			<!-- Email Address -->
			<div class="mt-4 flex gap-3 items-stretch">
				<x-boxed-label for="email">{{ __("Email Address") }}</x-boxed-label>
				<x-input id="email" class="w-full text-slate-500" type="email" name="email" :value="$account_data->email" disabled  />
			</div>

			<!-- Password -->
			<div class="mt-8 flex gap-3 items-stretch">
				<x-boxed-label for="password">{{ __("Change Password") }}<span class="font-bold">*</span></x-boxed-label>
				<x-input id="password" class="w-full" type="password" name="password"
					autocomplete="current-password" placeholder="New Password" />
			</div>

			<!-- Password Confirmation -->
			<div class="mt-4 flex gap-3 items-stretch">
				<x-boxed-label for="password_confirmation">{{ __("Confirm Password") }}<span class="font-bold">*</span></x-boxed-label>
				<x-input id="password_confirmation" class="w-full" type="password" name="password_confirmation"
					autocomplete="current-password" placeholder="New Password Confirmation" />
			</div>

			<p class="text-red-900 font-bold mt-5 text-sm">*Only fill these if you want to change your password</p>

			<div class="flex items-stretch gap-2 justify-center mt-20 mb-3 w-full">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-button type="button" onclick="history.back()" class="bg-orange-500 w-full md:w-1/6">
					Return
				</x-button>
			</div>
		</form>
	</div>
</x-section-container>
