@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<h2 class="text-2xl font-semibold text-blue-900 mb-4">Account's Detail</h2>

	@if(session()->has("successUpdateAccountData"))
		<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
			<p class="text-green-900">{{ session("successUpdateAccountData") }}</p>
		</div>
	@endif

	<div class="flex gap-1">
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route("sysadmin.account.acc_edit", $user->id) }}">Edit</a>
		@if($user->status == "enabled")
			<form action="{{ route("sysadmin.account.acc_disable", $user->id) }}" method="post">
				@csrf
				<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Disable
				</button>
			</form>
		@elseif($user->status == "disabled")
			<form action="{{ route("sysadmin.account.acc_enable", $user->id) }}" method="post">
				@csrf
				<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
					Enable
				</button>
			</form>
		@endif
		<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
			href="{{ route("sysadmin.account.acc_delete", $user->id) }}">
			Delete
		</a>
	</div>

	<table class="mt-5 border">
		<tr>
			<td class="border px-5 font-bold">Full name</td>
			<td class="border px-5">{{ $user->full_name }}</td>
		</tr>
		<tr>
			<td class="border px-5 font-bold">Email</td>
			<td class="border px-5">{{ $user->email }}</td>
		</tr>
		<tr>
			<td class="border px-5 font-bold">Role</td>
			<td class="border px-5">{{ $user->role->role_name }}</td>
		</tr>
	</table>
@endsection
