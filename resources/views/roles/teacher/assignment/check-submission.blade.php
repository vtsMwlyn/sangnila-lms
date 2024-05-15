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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Students Submissions in assignment "{{ $assignment->title }}"</h1>
			<div class="overflow-x-auto">
				<table class="min-w-full bg-white border mt-3" style="border-radius: 0;">
					<thead>
						<tr>
							<th class="border px-3">Submission time</th>
							<th class="border px-3">Student</th>
							<th class="border px-3">Submission title</th>
							<th class="border px-3">Submission status</th>
							<th class="border px-3">Submission link</th>
						</tr>
					</thead>
					<tbody>
						@if (!empty($submissions))
							@foreach ($submissions as $submission)
								<tr @if($submission->status == "Late") class="bg-red-400" @endif>
									<td class="border px-3">{{ $submission->created_at }}</td>
									<td class="border px-3">{{ $submission->uploaded_by->full_name }}</td>
									<td class="border px-3">{{ $submission->title }}</td>
									<td class="border px-3">{{ $submission->status }}</td>
									<td class="border px-3"><a class="text-blue-600" href="{{ $submission->link }}">{{ $submission->link }}</a></td>
								</tr>
							@endforeach
						@else
							<tr><td class="border text-center px-3" colspan="4">- No submissions yet from students -</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>
