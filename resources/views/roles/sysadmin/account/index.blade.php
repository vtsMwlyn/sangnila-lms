<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>Sangnila Academy | LMS</title>
</head>

<body class="bg-cover h-screen flex flex-col items-center" style="background-image: url({{ asset('img/background.png') }});">

    <x-navbar.sysadmin></x-navbar.sysadmin>
    <!-- Content Section -->
    <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">

        <h2 class="text-2xl font-semibold text-blue-900 mb-4">Accounts</h2>
        <div class="mb-5">
            <a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route('sysadmin.account.create') }}">Create new
                Account</a>
        </div>

		@if(session()->has("successCreateNewAccount"))
			<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
				<p class="text-green-900">{{ session("successCreateNewAccount") }}</p>
			</div>
		@elseif(session()->has("successUpdateAccountData"))
			<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
				<p class="text-green-900">{{ session("successUpdateAccountData") }}</p>
			</div>
		@elseif(session()->has("successDeleteAccount"))
			<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
				<p class="text-yellow-600" >{{ session("successDeleteAccount") }}</p>
			</div>
		@elseif(session()->has("successEnableAccount"))
			<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
				<p class="text-green-900">{{ session("successEnableAccount") }}</p>
			</div>
		@elseif(session()->has("successDisableAccount"))
			<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
				<p class="text-yellow-600" >{{ session("successDisableAccount") }}</p>
			</div>
		@endif

        @if ($accounts->isNotEmpty())
			<div class="overflow-x-auto rounded-md">
				<table class="min-w-full bg-white border-collapse">
					<thead>
						<tr>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Full Name</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Email</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Role</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Status</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Actions</td>
						</tr>
					</thead>
					<tbody>
						@foreach ($accounts as $account)
							@if($account->id == auth()->user()->id)
								@continue
							@endif

							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->full_name }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->email }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->role->role_name }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->status }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
									<div class="flex w-full gap-1">
										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("sysadmin.account.show", $account->id) }}"
										>
											View
										</a>

										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("sysadmin.account.acc_edit", $account->id) }}">
											Edit
										</a>

										<form action="{{ route("sysadmin.account.acc_disable", $account->id) }}" method="post">
											@csrf
											<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
												Disable
											</button>
										</form>

										<form action="{{ route("sysadmin.account.acc_delete", $account->id) }}" method="post">
											@csrf
											<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
												href="{{ route("sysadmin.account.acc_delete", $account->id) }}">
												Delete
											</button>
										</form>
									</div>

								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
        @else
        	<div class="text-blue-900">N/A</div>
        @endif

		<h2 class="text-2xl font-semibold text-blue-900 mt-5 mb-4">Disabled Accounts</h2>
		@if ($disabled->isNotEmpty())
			<div class="overflow-x-auto rounded-md">
				<table class="min-w-full bg-white border-collapse">
					<thead>
						<tr>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Full Name</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Email</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Role</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Status</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Actions</td>
						</tr>
					</thead>
					<tbody>
						@foreach ($disabled as $account)
							@if($account->id == auth()->user()->id)
								@continue
							@endif

							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->full_name }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->email }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->role->role_name }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">{{ $account->status }}</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
									<div class="flex w-full gap-1">
										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("sysadmin.account.show", $account->id) }}"
										>
											View
										</a>

										<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("sysadmin.account.acc_edit", $account->id) }}">
											Edit
										</a>

										<form action="{{ route("sysadmin.account.acc_enable", $account->id) }}" method="post">
											@csrf
											<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
												Enable
											</button>
										</form>

										<form action="{{ route("sysadmin.account.acc_delete", $account->id) }}" method="post">
											@csrf
											<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
												href="{{ route("sysadmin.account.acc_delete", $account->id) }}">
												Delete
											</button>
										</form>
									</div>

								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
        @else
        	<div class="text-blue-900">N/A</div>
        @endif
    </div>

</body>

</html>
