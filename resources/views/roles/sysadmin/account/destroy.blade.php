@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Delete Account</h1>
	<form method="POST" action="{{ route('sysadmin.account.destroy', $account->id) }}" class="bg-white rounded-2xl p-5 border-blue-300 border-2">
		@csrf
		@method('delete')
		<!-- Confirmation Text -->
		<div class="mb-6">
			<h1 class="text-xl font-semibold text-blue-900">
				Are you sure you want to permanently delete account
				<span class="text-red-500 font-bold">{{ $account->full_name }}</span>
				from Sangnila LMS?
			</h1>
		</div>

		<!-- Yes/No Buttons -->
		<div class="flex space-x-4">
			<button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-red-500 transition duration-300">Yes</button>
			<button type="button" onclick="history.back()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-900 transition duration-300">No</button>
		</div>
	</form>
@endsection
