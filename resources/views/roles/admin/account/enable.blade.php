@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Enable Account") }}</x-page-title>

	<div class="bg-indigo-200 rounded-3xl p-5">
		<form method="POST" action="{{ route("admin.account.acc_enable", $account->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-10">
			@csrf
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white">
					Are you sure you want to <span class="text-green-500 font-bold">enable</span> account
					<span class="text-yellow-400 font-bold">{{ $account->full_name }}</span>
					? This account can be used again by the user in Sangnila LMS after enabled again.
				</h1>
			</div>

			<!-- Yes/No Buttons -->
			<div class="flex gap-1">
				<x-button type="submit" class="bg-orange-500">Yes</x-button>
				<x-button type="button" onclick="history.back()" class="bg-orange-500">No</x-button>
			</div>
		</form>
	</div>
@endsection
