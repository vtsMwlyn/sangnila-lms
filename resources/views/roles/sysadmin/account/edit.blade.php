@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Edit Account's Data") }}</x-page-title>

	<form action="{{ route('sysadmin.account.acc_edit.store', $account->id) }}" method="post" class="bg-indigo-200 py-5 px-10 rounded-xl">
		@csrf
		@method('PATCH')
		<!-- Account Name -->
		<div>
			<x-label for="full_name" :value="__('New Account Name')" />
			<x-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="$account->full_name"
				autofocus />
		</div>

		<!-- Account Email -->
		<div class="mt-3">
			<x-label for="email" :value="__('New Account Email')"/>
			<x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="$account->email"  />
		</div>

		<div class="flex items-stretch justify-end mt-4">
			<x-button type="button" onclick="history.back()" class="bg-orange-500">
				Cancel
			</x-button>
			<x-button class="ml-4 bg-orange-500">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
