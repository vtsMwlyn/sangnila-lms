@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Account's Details") }}</x-page-title>

	@if(session()->has("successUpdateAccountData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateAccountData") }}</p>
		</div>
	@endif

	<div class="flex items-stretch gap-1 my-2">
		<x-anchor-button class="bg-orange-500" href="{{ route('sysadmin.account.acc_edit', $user->id) }}"><i class="bi bi-pencil-square"></i> Edit</x-anchor-button>
		@if($user->status == "enabled")
			<form action="{{ route("sysadmin.account.acc_disable", $user->id) }}" method="post">
				@csrf
				<x-button type="submit" class="bg-orange-500">
					<i class="bi bi-ban"></i> Disable
				</x-button>
			</form>
		@elseif($user->status == "disabled")
			<form action="{{ route("sysadmin.account.acc_enable", $user->id) }}" method="post">
				@csrf
				<x-button type="submit" class="bg-orange-500">
					<i class="bi bi-check-circle"></i> Enable
				</x-button>
			</form>
		@endif
		<x-anchor-button class="bg-orange-500" href="{{ route('sysadmin.account.acc_delete', $user->id) }}">
			<i class="bi bi-trash3"></i> Delete
		</x-anchor-button>
	</div>

	<div class="p-5 bg-indigo-200 rounded-3xl overflow-x-auto">
		<table class="w-full" style="border-collapse: separate; border-spacing: 15px 10px;">
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold">Full name</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">{{ $user->full_name }}</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold">Email</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">{{ $user->email }}</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold">Role</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">{{ $user->role->role_name }}</td>
			</tr>
			<tr>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2 font-bold">Account Status</td>
				<td class="bg-white border-2 border-blue-800 rounded-xl text-blue-800 px-5 py-2">{{ $user->status }}</td>
			</tr>
		</table>
	</div>
@endsection
