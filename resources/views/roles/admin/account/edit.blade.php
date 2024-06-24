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
			<x-select name="role_id" id="role_id"
			class="mt-1 w-full md:w-1/3">
				@forelse ($roles as $role)
					<option value="{{ $role->id }}" @if($account->role->role_name == $role->role_name) selected @endif>{{ $role->role_name }}</option>
				@empty
				@endforelse
			</x-select>
		</div>

		<div class="flex items-stretch justify-center mt-4 gap-3">
			<x-button class="bg-orange-500 w-full md:w-1/5">
				{{ __('Save') }}
			</x-button>
			<x-button type="button" onclick="if(confirm('The changes will be discarded, are you sure want to cancel?')) history.back();" class="bg-orange-500 w-full md:w-1/5">
				Cancel
			</x-button>
		</div>
	</form>
@endsection
