@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Disable Account") }}</x-page-title>

		<form method="POST" action="{{ route('admin.account.acc_disable', $account->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-8">
			@csrf
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white">
					You are about to <span class="text-red-500 font-bold">disable</span> account
					<span class="text-yellow-400 font-bold">{{ $account->full_name }}</span>
					from Sangnila LMS. Disabled accounts can't be used again by the user. Please enter the reason then proceed to continue.
				</h1>
			</div>

			<!-- Input disabling reason -->
			<div class="">
				<x-label style="color: white;">{{ __("Account disabling reason:") }}</x-label>
				<x-input type="text" name="disable_reason" id="disable_reason" class="w-full mt-1" placeholder="Enter the account disabling reason" :value="old('disable_reason')"/>
			</div>

			<!-- Yes/No Buttons -->
			<div class="flex gap-3 w-full justify-center mt-8">
				<x-button type="submit" class="bg-orange-500 w-full md:w-1/6">Proceed</x-button>
				<x-button type="button" onclick="history.back()" class="bg-orange-500 w-full md:w-1/6">Cancel</x-button>
			</div>
		</form>

	</x-section-container>
@endsection
