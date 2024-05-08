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

<body class="bg-cover h-screen flex flex-col items-center bg-gray-100">
	<x-navbar.teacher></x-navbar.teacher>
	<!-- Content Section -->
	<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
		<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $topic->course->course_name }}</h1>
		<h1 class="text-2xl font-semibold text-blue-900 mb-4">{{ $topic->title }}</h1>

		<div class="mt-5 mb-5">
			<form action="{{ route("teacher.topic.update", [$topic->course->id, $topic->id]) }}" method="post" class="border rounded-lg p-5">
				@csrf
				@method("patch")
				<!-- New Topic Title -->
				<div>
					<x-label for="title" :value="__('New Topic Title')" />
					<x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $topic->title)"
						autofocus />
				</div>
				<a href={{ route("teacher.topic.show", [$topic->course->id, $topic->id]) }} class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">CANCEL</a>
				<x-button class="mt-3">
					{{ __('SAVE') }}
				</x-button>
			</form>
		</div>

		<h2 class="text-xl font-semibold mb-2">Material List:</h2>
		<table>
			<thead>
				<th class="border px-3">Material Title</th>
				<th class="border px-3">Link</th>
				<th class="border px-3">Actions</th>
			</thead>
			<tbody>
				@forelse ($topic->course_materials as $material)
					<tr>
						<td class="border px-3">{{ $material->title }}</td>
						<td class="border px-3"><a href="{{ $material->link }}" class="text-blue-700">{{ $material->link }}</a></td>
						<td class="border px-3">
							<div class="flex gap-1">
								<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
									href="#">
									Edit Material
								</a>
								<form action="#" method="post">
									@csrf
									@method("delete")
									<button type="submit" class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300">
										Delete Material
									</button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="3" class="border px-3">- No materials added yet to this topic -</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</body>

</html>
