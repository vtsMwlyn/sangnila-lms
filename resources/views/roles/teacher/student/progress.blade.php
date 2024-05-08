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

		<x-navbar.teacher></x-navbar.teacher>
		<!-- Content Section -->
		<div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Progress</h1>
			<h1 class="text-xl font-semibold text-blue-900 mb-4">In Course: {{ $course->course_name }}</h1>

			<div class="overflow-x-auto rounded-md">
				<table class="min-w-full bg-white border-collapse ">
					<thead>
						<tr>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Topic</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Material Name</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Access</td>
							<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Actions</td>
						</tr>
					</thead>
					<tbody>
						@if ($newestprogress->isNotEmpty())
							@foreach ($newestprogress as $progress)
								<tr>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
										<a href="">
											{{ $progress->material->course_topic->title }}
										</a>
									</td>

									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
										<a href="">
											{{ $progress->material->title }}
										</a>
									</td>

									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
										{{ $progress->status }}
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
										<form method="POST" action="{{ route('teacher.student.update.progress', $progress->id) }}">
											@csrf
											@method('PATCH')
											<div class="inline-flex items-center">
												<input type="checkbox" name="access" id="material_progress_{{ $progress->id }}"
													class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 {{ $progress->status === 'unlocked' ? 'bg-blue-500' : 'bg-gray-300' }}"
													@if ($progress->status === 'unlocked') checked @endif>
												<x-button>Submit</x-button>
											</div>
										</form>
									</td>

								</tr>
							@endforeach
						@else
							<tr class="text-blue-900"><td colspan="3" class="text-center">N/A</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>
