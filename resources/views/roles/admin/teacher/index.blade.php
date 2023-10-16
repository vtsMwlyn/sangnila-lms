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

<body>
	<div class="bg-cover h-screen flex flex-col items-center"
		style="background-image: url({{ asset('img/background.png') }});">

		<x-navbar.admin></x-navbar.admin>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Teachers</h1>
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
						@forelse ($accounts as $account)
							<tr>
								<td class="border border-blue-400 px-4 py-2">
									<a href="{{ route('admin.teacher.show', ['teacher_id' => $account->id]) }}" class="text-blue-500 hover:text-blue-700 underline cursor-pointer">
										{{ $account->full_name }}
									</a>
								</td>


								<td class="border border-blue-400 px-4 py-2">{{ $account->role->role_name }}</td>
								<td class="border border-blue-400 px-4 py-2">{{ $account->email }}</td>
							</tr>
						@empty
							<tr>
								<td class="border border-blue-400 px-4 py-2" colspan="3">N/A</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

		</div>

</body>

</html>
