@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Delete Account") }}</x-page-title>

		<div class="mt-10">
			<form method="POST" action="{{ route('admin.account.destroy', $account->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10">
				@csrf
				@method('delete')
				<!-- Confirmation Text -->
				<div class="mb-6">
					<h1 class="text-xl font-semibold text-white">
						Are you sure you want to permanently delete account
					<span class="text-red-500 font-bold">{{ $account->full_name }}</span>
					from Sangnila LMS?
					</h1>
				</div>

				<!-- Yes/No Buttons -->
				<div class="flex gap-3 justify-center w-full">
					<x-button type="submit" class="bg-orange-500 w-full md:w-1/12">Yes</x-button>
					<x-button type="button" onclick="history.back()" class="bg-orange-500 w-full md:w-1/12">No</x-button>
				</div>
			</form>
		</div>
	</x-section-container>
@endsection
