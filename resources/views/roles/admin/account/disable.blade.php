@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Disable Account") }}</x-page-title>

	<div class="bg-indigo-200 rounded-3xl p-5 mt-10">
		<form method="POST" action="{{ route('admin.account.acc_disable', $account->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10">
			@csrf
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white">
					Are you sure you want to <span class="text-red-500 font-bold">disable</span> account
					<span class="text-yellow-400 font-bold">{{ $account->full_name }}</span>
					from Sangnila LMS? Disabled accounts can't be used again by the user.
				</h1>
			</div>

			<!-- Yes/No Buttons -->
			<div class="flex gap-3 w-full justify-center">
				<x-button type="submit" class="bg-orange-500 w-full md:w-1/12">Yes</x-button>
				<x-button type="button" onclick="history.back()" class="bg-orange-500 w-full md:w-1/12">No</x-button>
			</div>
		</form>
	</div>
@endsection
