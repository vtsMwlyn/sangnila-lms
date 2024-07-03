@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Account's Details") }}</x-page-title>

		@if(session()->has("successUpdateAccountData"))
			<x-badge-success badge_text="{{ session('successUpdateAccountData') }}"></x-badge-success>
		@elseif(session()->has("successResetPassword"))
			<x-badge-success badge_text="{{ session('successResetPassword') }}"></x-badge-success>
		@endif

		<div class="flex justify-between items-center mt-10 mb-5">
			<div class="flex items-stretch gap-2">
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_edit', $user->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
				@if($user->status == "enabled")
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_disable.conf', $user->id) }}"><i class="bi bi-ban"></i> Disable</x-anchor-button>
				@elseif($user->status == "disabled")
					<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_enable.conf', $user->id) }}"><i class="bi bi-check-circle"></i> Enable</x-anchor-button>
				@endif
				<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_delete', $user->id) }}">
					<i class="bi bi-trash3"></i> Delete
				</x-anchor-button>
			</div>

			<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.reset-password', $user->id) }}">
				Reset Password
			</x-anchor-button>
		</div>

		<div class="overflow-x-auto">
			<x-horizontal-table>
				<tr>
					<td class="template-hheads w-1/3">Full Name</td>
					@if($user->role_id == 2)
						<td class="template-hbodies">{{ ($user->details->gender == 1)? "Mr." : "Ms." }} {{ $user->full_name }}</td>
					@else
						<td class="template-hbodies">{{ $user->full_name }}</td>
					@endif
				</tr>
				<tr>
					<td class="template-hheads w-1/3">Email</td>
					<td class="template-hbodies">{{ $user->email }}</td>
				</tr>
				<tr>
					<td class="template-hheads w-1/3">Role</td>
					<td class="template-hbodies">{{ $user->role->role_name }}</td>
				</tr>
				<tr>
					<td class="template-hheads w-1/3">Account Status</td>
					<td class="template-hbodies">{{ $user->status }}</td>
				</tr>
			</x-horizontal-table>
		</div>
	</x-section-container>
@endsection
