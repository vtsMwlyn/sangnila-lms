<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<title>Laravel</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.sysadmin></x-navbar.sysadmin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Accounts</h1>
			@if ($accounts->isNotEmpty())
				<div class="overflow-x-auto">
					<table class="min-w-full bg-white border-collapse border border-blue-400">
						<thead>
							<tr>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Full Name</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Role</th>
								<th class="bg-blue-300 border border-blue-400 px-4 py-2">Email</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($accounts as $account)
								<tr>
									<td class="border border-blue-400 px-4 py-2">{{ $account->full_name }}</td>
									<td class="border border-blue-400 px-4 py-2">{{ $account->role->role_name }}</td>
									<td class="border border-blue-400 px-4 py-2">{{ $account->email }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="text-blue-900">N/A</div>
			@endif
		</div>
	</div>

</body>

</html>
