@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	@if($account->role_id == 2)
		> <a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-yellow-500">{{ (($account->details->gender == 1)? "Mr. " : "Ms. ") . $account->full_name }}</a>
	@else
		> <a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-yellow-500">{{ $account->full_name }}</a>
	@endif
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ __("Edit Account's Data") }}</x-page-title>

		<form action="{{ route('admin.account.acc_edit.store', $account->id) }}" method="post">
			@csrf
			@method('PATCH')
			<!-- Account Name -->
			<div class="flex gap-3 items-stretch">
				<x-boxed-label for="full_name" :value="__('New Account Name')" />
				<x-input id="full_name" class="block w-full" type="text" name="full_name" :value="$account->full_name"
					autofocus />
			</div>

			<!-- Account Email -->
			<div class="mt-3 flex gap-3 items-stretch">
				<x-boxed-label for="email" :value="__('New Account Email')"/>
				<x-input id="email" class="block w-full" type="text" name="email" :value="$account->email"  />
			</div>

			<!-- Role Selection -->
			<div class="mt-4 flex gap-3 items-stretch">
				<x-boxed-label for="role_id" class="text-white" :value="__('Select New Role')" />
				<x-select name="role_id" id="role_id"
				class="w-full">
					@forelse ($roles as $role)
						<option value="{{ $role->id }}" @if($account->role->role_name == $role->role_name) selected @endif>{{ $role->role_name }}</option>
					@empty
					@endforelse
				</x-select>
			</div>

			<div class="flex items-stretch justify-center mt-20 mb-3 gap-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button msg="The changes will be discarded, are you sure want to cancel?" class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
