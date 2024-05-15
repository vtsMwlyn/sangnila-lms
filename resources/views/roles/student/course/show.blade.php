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
		<x-navbar.student></x-navbar.student>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
			<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>

			<h2 class="text-xl font-semibold mb-2">Course Materials:</h2>
			<div class="h-fit mb-5">

			</div>
			<div class="mb-6">
				<table class="min-w-full table-fixed border-collapse">
					<thead>
						<tr class="border-b border-solid border-blue-900">
							<th class="px-4 py-2 border border-solid border-blue-900 bg-blue-200">Course Topic</th>
							<th class="px-4 py-2 border border-solid border-blue-900 bg-blue-200">Material Name</th>
							<th class="px-4 py-2 border border-solid border-blue-900 bg-blue-200">Status</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($materialProgresses as $progress)
							<tr class="hover:bg-gray-100 border-b border-solid border-blue-900">
								<td class="px-4 py-2 border border-solid border-blue-900 {{ $progress->status === 'unlocked' ? 'text-white bg-green-400' : 'text-gray-500' }}">
									{{ $progress->material->course_topic->title }}
								</td>
								<td class="px-4 py-2 border border-solid border-blue-900 {{ $progress->status === 'unlocked' ? 'text-white bg-green-400' : 'text-gray-500' }}">
									@if ($progress->status === 'unlocked')
										<a href="{{ $progress->material->link }}" class="text-white hover:underline font-bold">
											{{ $progress->material->title }}
										</a>
									@else
										<span class="text-gray-500">
											{{ $progress->material->title }}
										</span>
									@endif
								</td>
								<td class="px-4 py-2 border border-solid border-blue-900 {{ $progress->status === 'unlocked' ? 'text-white bg-green-400' : 'text-gray-500' }}">
									<span class="">
										{{ ucfirst($progress->status) }}
									</span>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="2" class="px-4 py-2 text-center border-t border-solid border-blue-900">No materials found.</td>
							</tr>
						@endforelse
					</tbody>
				</table>

			</div>
		</div>
	</div>
</body>

</html>
