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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Submissions History for Assignment "{{ $assignment->title }}"</h1>

			@if(session()->has("successModifFeedback"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successModifFeedback") }}</p>
				</div>
			@endif

			<div class="overflow-x-auto">
				<table class="min-w-full bg-white border mt-3" style="border-radius: 0;">
					<thead>
						<tr>
							<th class="border px-3">Submission time</th>
							<th class="border px-3">Submission title</th>
							<th class="border px-3">Submission link</th>
							<th class="border px-3">Submission status</th>
							<th class="border px-3">Feedback</th>
						</tr>
					</thead>
					<tbody>
						@if (!empty($history))
							@foreach ($history as $submission)
								<tr @if($submission->status == "Late") class="bg-red-400" @endif>
									<td class="border px-3">{{ $submission->created_at }}</td>
									<td class="border px-3">{{ $submission->title }}</td>
									<td class="border px-3"><a href="{{ $submission->link }}" class="font-bold text-blue-600">{{ $submission->link }}</a></td>
									<td class="border px-3">{{ $submission->status }}</td>
									<td class="border">
										<form action="{{ route("teacher.assignment.feedback", [$submission->id, $submission->uploaded_by->id]) }}" method="post" class="flex gap-1 justify-center items-center w-full">
											@csrf
											<!-- Feedback -->
											<div>
												<x-input id="feedback" style="min-width: 400px" type="text" name="feedback" placeholder="Your feedback"
													:value="old('feedback', $submission->feedback)" />
											</div>
											<x-button>
												{{ __('Save') }}
											</x-button>
										</form>
									</td>
								</tr>
							@endforeach
						@else
							<tr class="border px-3" colspan="5"><td class="text-center">- The student hasn't uploaded any submissions yet -</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>

