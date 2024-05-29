@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Edit Account's Data") }}</x-page-title>

	<form action="{{ route('admin.account.acc_edit.store', $account->id) }}" method="post" class="bg-indigo-200 p-10 mt-10 rounded-xl">
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

		<!-- Role Selection -->
		<div class="mt-4">
			<x-label for="role_id" class="text-white" :value="__('Select New Role')" />
			<select name="role_id" id="role_id"
			class="rounded-md shadow-sm border-blue-800 focus:border-indigo-400 focus:ring focus:ring-indigo-400 border focus:ring-opacity-50 w-1/3 py-2 px-4 mt-1 text-blue-800">
				@forelse ($roles as $role)
					<option value="{{ $role->id }}" @if($account->role->role_name == $role->role_name) selected @endif>{{ $role->role_name }}</option>
				@empty
				@endforelse
			</select>
		</div>

		<div class="flex items-stretch justify-end mt-4 gap-1">
			<x-button type="button" onclick="history.back()" class="bg-orange-500">
				Cancel
			</x-button>
			<x-button class="bg-orange-500">
				{{ __('Save') }}
			</x-button>
		</div>
	</form>
@endsection
