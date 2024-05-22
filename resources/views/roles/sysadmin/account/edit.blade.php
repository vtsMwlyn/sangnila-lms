@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Edit Account Data</h1>
	<form action="{{ route('sysadmin.account.acc_edit.store', $account->id) }}" method="post">
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
			<button type="button" onclick="history.back()" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Cancel
			</button>
			<x-button class="ml-4 bg-indigo-400">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
