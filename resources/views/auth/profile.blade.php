<div class="rounded-3xl w-full xl:w-5/6 py-5 px-8 mb-6 flex flex-col sm:text-base text-sm self-start" style="background: #FEFEFEB2;">
	<x-page-title>{{ __("Account Profile") }}</x-page-title>
	<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

	@if(session()->has("success"))
		<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
	@elseif(session()->has("warning"))
		<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
	@elseif(session()->has("danger"))
		<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
	@endif

	<div>
		<div style="display: none;">
			<h1 class="font-bold text-lg text-blue mb-3">New Profile Picture Editor</h1>
			<div class="overflow-y-auto w-full flex mb-5" style="max-height: 70vh;">
				<img id="image-editor" class="w-full h-full">
			</div>
		</div>

		<h1 class="font-bold text-lg text-blue">My Profile Data</h1>
		<form action="{{ route("profile.update") }}" method="post" class="flex flex-col md:flex-row gap-10 mt-4" enctype="multipart/form-data">
			@csrf
			<div class="flex flex-col items-center relative">
				<div class="relative w-40 h-40 rounded-full overflow-hidden shadow-lg">
					@if(Auth::user()->details->profpic)
						<img src="{{ Storage::url("app/public/" . Auth::user()->details->profpic) }}" alt="Image Preview" class="w-full h-full object-cover rounded-full" loading="lazy">
					@else
						<img src="{{ asset('img/tempblankprofpic.png') }}" alt="Image Preview" class="w-full h-full object-cover rounded-full" loading="lazy">
					@endif
					<label for="image" class="text-3xl absolute bottom-0 w-full h-10 bg-black bg-opacity-50 text-white flex justify-center items-center cursor-pointer">
						<i class="bi bi-camera-fill"></i>
					</label>
				</div>
				<input type="file" id="image" name="image" accept="image/*" class="hidden">
			</div>

			<div class="flex flex-col grow">
				{{-- Name --}}
				<div>
					<x-label for="full_name">Full Name<span class="text-red">*</span></x-label>
					<x-input id="full_name" class="w-full mt-1" type="text" name="full_name" :value="old('full_name', $account_data->full_name)" placeholder="Full Name" />
				</div>

				{{-- Email Address --}}
				<div class="mt-6">
					<x-label for="email">Email Address<span class="text-red">*</span></x-label>
					<x-input id="email" class="w-full mt-1" type="email" name="email" :value="$account_data->email" disabled  />
				</div>

				{{-- Phone Number --}}
				<div class="mt-6">
					<x-label for="phone_number">Phone Number</x-label>
					<x-input id="phone_number" class="w-full mt-1" type="text" name="phone_number" placeholder="Phone Number" :value="old('phone_number', $account_data->details->phone_number)" autocomplete="tel"/>
				</div>

				{{-- Password --}}
				<div class="mt-6">
					<x-label for="password">Change Password</x-label>
					<div class="relative w-full flex items-center mt-1">
						<button type="button" class="absolute top-0 h-11 right-2 text-slate-500 font-bold w-12" id="togglePassword"></button>
						<div class="flex flex-col w-full">
							<x-input id="password" class="w-full" type="password" name="password" style="padding-right: 60px;"
							autocomplete="current-password" placeholder="New Password" autocomplete="new-password" />
						</div>
					</div>
				</div>

				{{-- Password Confirmation --}}
				<div class="mt-6">
					<x-label for="password_confirmation">Confirm Password</x-label>
					<div class="relative w-full flex items-center mt-1">
						<button type="button" class="absolute top-0 h-11 right-2 text-slate-500 font-bold w-12" id="toggleConfPassword"></button>
						<div class="flex flex-col w-full">
							<x-input id="password_confirmation" class="w-full" type="password" name="password_confirmation" style="padding-right: 60px;"
							autocomplete="current-password" placeholder="Confirm New Password"  autocomplete="new-password"/>
						</div>
					</div>
				</div>

				<div class="flex items-stretch gap-2 justify-end mt-8 mb-3 w-full">
					<x-cancel-button class="w-full md:w-40 xl:w-1/6">
						Return
					</x-cancel-button>
					<x-button class=" w-full md:w-40 xl:w-1/6">
						{{ __('Save') }}
					</x-button>
				</div>
			</div>
		</form>
	</div>

	<script>
		let toggleStatus = 0;
		let cropper;

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

		$("#image").on("change", function(e) {
			const file = e.target.files[0];

			if (file) {
				const reader = new FileReader();
				reader.onload = function(e) {
					$("#image-editor").attr("src", e.target.result);
					$("#image-editor").parent().parent().show();

					if(cropper){
						cropper.destroy();
					}

					cropper = new Cropper(document.getElementById("image-editor"), {
						aspectRatio: 1, // Square crop
						viewMode: 2
					});
				};
				reader.readAsDataURL(file);
			}
		});

		$("form").on("submit", function (e) {
			if (cropper) {
				e.preventDefault(); // Prevent immediate submission

				cropper.getCroppedCanvas().toBlob((blob) => {
					let formData = new FormData(e.target); // Use the existing form data
					formData.append("cropped_image", blob, "cropped_image.jpg"); // Append the cropped image blob

					// Create a new form and submit it programmatically
					let newForm = document.createElement("form");
					newForm.action = e.target.action;
					newForm.method = e.target.method;
					newForm.enctype = "multipart/form-data";

					// Append all original form fields
					for (let [key, value] of formData.entries()) {
						if (value instanceof File) {
							// Append File Inputs Properly
							let fileInput = document.createElement("input");
							fileInput.type = "file";
							fileInput.name = key;

							// Create a DataTransfer object to set the File input's files property
							let dataTransfer = new DataTransfer();
							dataTransfer.items.add(value);
							fileInput.files = dataTransfer.files;

							newForm.appendChild(fileInput);
						} else {
							// Append regular inputs as hidden fields
							let input = document.createElement("input");
							input.type = "hidden";
							input.name = key;
							input.value = value;
							newForm.appendChild(input);
						}
					}

					document.body.appendChild(newForm);
					newForm.submit();
				}, "image/jpeg");
			}
		});


	</script>
</div>
