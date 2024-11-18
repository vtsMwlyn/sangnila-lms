<div class="rounded-3xl w-full md:w-5/6 py-5 px-8 mb-6 flex flex-col sm:text-base text-sm self-start" style="background: #FEFEFEB2;">
	<h1 class="text-dark-blue font-bold text-lg">{{ __("Account Profile") }}</h1>
	<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

	@if(session()->has("successUpdateProfile"))
		<x-badge-success badge_text="{{ session('successUpdateProfile') }}">
		</x-badge-success>
	@elseif(session()->has("successPay"))
		<x-badge-success badge_text="{{ session('successPay') }}"></x-badge-success>
	@elseif(session()->has("systemFail"))
		<x-badge-danger badge_text="{{ session('systemFail') }}"></x-badge-danger>
	@endif

	<div class="mt-5">
		<form action="{{ route("profile.update") }}" method="post" class="flex gap-10" enctype="multipart/form-data">
			@csrf
			<div class="flex flex-col items-center relative">
				<div class="relative w-40 h-40 rounded-full overflow-hidden shadow-lg">
					@if(Auth::user()->details->profpic)
						<img id="img-preview" src="{{ Storage::url("app/public/" . Auth::user()->details->profpic) }}" alt="Image Preview" class="w-full h-full object-cover rounded-full">
					@else
						<img id="img-preview" src="{{ asset('img/tempblankprofpic.png') }}" alt="Image Preview" class="w-full h-full object-cover rounded-full">
					@endif
					<label for="image" class="text-3xl absolute bottom-0 w-full h-10 bg-black bg-opacity-50 text-white flex justify-center items-center cursor-pointer">
						<i class="bi bi-camera-fill"></i>
					</label>
				</div>
				<input type="file" id="image" name="image" accept="image/*" class="hidden">
			</div>

			<div class="flex flex-col grow">
				<!-- Name -->
				<div>
					<x-label for="full_name">{{ __("Full Name") }}</x-label>
					<x-input id="full_name" class="w-full mt-1" type="text" name="full_name" :value="old('full_name', $account_data->full_name)" placeholder="Full Name" />
				</div>

				<!-- Email Address -->
				<div class="mt-6">
					<x-label for="email">{{ __("Email Address") }}</x-label>
					<x-input id="email" class="w-full mt-1" type="email" name="email" :value="$account_data->email" disabled  />
				</div>

				<!-- Phone Number -->
				<div class="mt-6">
					<x-label for="phone_number">{{ __("Phone Number") }}</x-label>
					<x-input id="phone_number" class="w-full mt-1" type="text" name="phone_number" placeholder="Phone Number" :value="old('phone_number', $account_data->details->phone_number)" />
				</div>

				<!-- Password -->
				<div class="mt-6">
					<x-label for="password">{{ __("Change Password") }}<span class="font-semibold">*</span></x-label>
					<div class="relative w-full flex items-center mt-1">
						<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="togglePassword"></button>
						<x-input id="password" class="w-full" type="password" name="password" style="padding-right: 60px;"
							autocomplete="current-password" placeholder="New Password" />
					</div>
				</div>

				<!-- Password Confirmation -->
				<div class="mt-6">
					<x-label for="password_confirmation">{{ __("Confirm Password") }}<span class="font-semibold">*</span></x-label>
					<div class="relative w-full flex items-center mt-1">
						<button type="button" class="absolute right-2 h-full text-slate-500 font-bold w-12" id="toggleConfPassword"></button>
						<x-input id="password_confirmation" class="w-full" type="password" name="password_confirmation" style="padding-right: 60px;"
							autocomplete="current-password" placeholder="Confirm New Password" />
					</div>
				</div>

				<p class="mt-5 text-xs">*Only fill these if you want to change your password</p>

				<div class="flex items-stretch gap-2 justify-end mt-4 mb-3 w-full">
					<x-cancel-button class="w-full md:w-1/6">
						Return
					</x-cancel-button>
					<x-button class="bg-orange-500 w-full md:w-1/6">
						{{ __('Save') }}
					</x-button>
				</div>
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

		$("#image").on("change", function(){
			const oFReader = new FileReader();
			oFReader.readAsDataURL(image.files[0]);

			oFReader.onload = function(oFEvent){
				$("#img-preview").attr("src", oFEvent.target.result);
			}

			$("#img-preview-label").show();
		});
	</script>
</div>
