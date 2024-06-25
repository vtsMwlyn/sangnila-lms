@extends("layouts.main-admin")

@section("title")
	<h1>Password Reset</h1>
@endsection

@section("content")
<x-section-container>
	<x-page-title class="mt-5">{{ __("Reset Password") }}</x-page-title>
	<form method="POST" action="{{ route('admin.account.reset-password.proceed', $account->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-8">
		@csrf
		<!-- Confirmation Text -->
		<div class="mb-6">
			<h1 class="text-xl font-semibold text-white">
				You're about to reset password of account <span class="text-yellow-500 font-bold">{{ $account->full_name }}</span>. The password will be resetted to the default password as newly created account password. Are you sure want to proceed?
			</h1>
		</div>

		<!-- Yes/No Buttons -->
		<div class="flex gap-3 justify-center w-full">
			<x-button type="submit" class="bg-orange-500 w-full md:w-1/12">Yes</x-button>
			<x-button type="button" onclick="history.back()" class="bg-orange-500 w-full md:w-1/12">No</x-button>
		</div>
	</form>
</x-section-container>
@endsection
