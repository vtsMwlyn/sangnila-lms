<x-section-container>
	<x-page-title>{{ __("Account Profile") }}</x-page-title>

	@if(session()->has("successUpdateProfile"))
		<x-badge-success badge_text="{{ session('successUpdateProfile') }}">
		</x-badge-success>
	@elseif(session()->has("successPay"))
		<x-badge-success badge_text="{{ session('successPay') }}"></x-badge-success>
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
				<div class="relative w-full h-full">
					<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="togglePassword"></button>
					<x-input id="password" class="w-full rounded-xl" type="password" name="password" style="padding-right: 60px;"
						autocomplete="current-password" placeholder="New Password" />
				</div>
			</div>

			<!-- Password Confirmation -->
			<div class="mt-4 flex gap-3 items-stretch">
				<x-boxed-label for="password_confirmation">{{ __("Confirm Password") }}<span class="font-bold">*</span></x-boxed-label>
				<div class="relative w-full h-full">
					<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="toggleConfPassword"></button>
					<x-input id="password_confirmation" class="w-full rounded-xl" type="password" name="password_confirmation" style="padding-right: 60px;"
						autocomplete="current-password" placeholder="Confirm New Password" />
				</div>
			</div>

			<p class="text-red-900 font-bold mt-5 text-sm">*Only fill these if you want to change your password</p>

			<div class="flex items-stretch gap-2 justify-center mt-20 mb-3 w-full">
				<x-button class="bg-orange-500 w-full md:w-1/6">
					{{ __('Save') }}
				</x-button>
				<x-button type="button" onclick="history.back()" class="bg-slate-600 w-full md:w-1/6">
					Return
				</x-button>
			</div>
		</form>
	</div>

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
	</script>
</x-section-container>
