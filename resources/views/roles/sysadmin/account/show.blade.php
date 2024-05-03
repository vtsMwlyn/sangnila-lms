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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<title>Sangnila Academy | LMS</title>

</head>

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">
		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h2 class="text-2xl font-semibold text-blue-900 mb-4">Account's Detail</h2>

			<div class="flex">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300" href="{{ route("sysadmin.account.acc_edit", $user->id) }}">Edit</a>
				<form action="{{ route("sysadmin.account.acc_disable", $user->id) }}" method="post">
					@csrf
					<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
						Disable
					</button>
				</form>
				<form action="{{ route("sysadmin.account.acc_delete", $user->id) }}" method="post">
					@csrf
					<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
						Delete
					</button>
				</form>
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

		</div>
	</div>
</body>

</html>
