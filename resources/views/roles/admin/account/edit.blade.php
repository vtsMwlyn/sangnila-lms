@extends("layouts.main-admin")

@section("title")
	<h1>{{ $account->full_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	{{-- @if($account->role_id == 2)
		> <a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-yellow-500">{{ (($account->details->gender == 1)? "Mr. " : "Ms. ") . $account->full_name }}</a>
	@else
		> <a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-yellow-500">{{ $account->full_name }}</a>
	@endif --}}
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Edit Account's Data") }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route('admin.account.acc_edit.store', $account->id) }}" method="post" class="mt-3">
			@csrf
			@method('PATCH')

			<div class="flex w-full gap-5">
				<!-- Account Name -->
				<div class="flex w-1/2 flex-col">
					<x-label for="full_name" :value="__('New Account Name')" />
					<div class="flex flex-col w-full items-stretch">
						<x-input id="full_name" class="block w-full" type="text" name="full_name" :value="$account->full_name"
						autofocus />
					</div>
				</div>

				<!-- Account Email -->
				<div class="flex w-1/2 flex-col">
					<x-label for="email" :value="__('New Account Email')"/>
					<div class="flex flex-col w-full items-stretch">
						<x-input id="email" class="block w-full" type="text" name="email" :value="$account->email"  />
					</div>
				</div>
			</div>

			<div class="flex w-full gap-5 mt-3">
				<!-- Role Selection -->
				<div class="flex w-1/2 flex-col">
					<x-label for="role_id" :value="__('Change Role')" />
					<div class="flex flex-col w-full items-stretch">
						<x-select name="role_id" id="role_id"
						class="w-full">
							@forelse ($roles as $role)
								<option value="{{ $role->id }}" @if($account->role->role_name == $role->role_name) selected @endif>{{ $role->role_name }}</option>
							@empty
							@endforelse
						</x-select>
					</div>
				</div>

				<!-- Gender Selection -->
				<div class="flex w-1/2 flex-col">
					<x-label for="gender" :value="__('Change Gender')" />
					<div class="flex flex-col w-full items-stretch">
						<x-select name="gender" id="gender"
						class="w-full">
							<option value="1" @if($account->details->gender == 1) selected @endif>Male</option>
							<option value="2" @if($account->details->gender == 2) selected @endif>Female</option>
						</x-select>
					</div>
				</div>
			</div>

			<div class="flex items-stretch justify-center mt-20 mb-3 gap-3">
				<x-button class=" w-full md:w-1/5">
					{{ __('Save') }}
				</x-button>
				<x-cancel-button class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>
	</x-section-container>
@endsection
