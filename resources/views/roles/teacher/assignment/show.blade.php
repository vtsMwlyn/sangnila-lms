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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">List of Assignments in {{ $course->course_name }}</h1>

			<div class="mt-5 mb-5">
				<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white"
					href="{{ route('teacher.assignment.upload', $course->id) }}">
					Upload New Assignment
				</a>
			</div>

			@if(session()->has("successUploadAssignment"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successUploadAssignment") }}</p>
				</div>
			@elseif(session()->has("successEditAssignment"))
				<div class="w-full bg-green-500 px-5 py-3 mb-5 rounded-lg">
					<p class="text-green-900">{{ session("successEditAssignment") }}</p>
				</div>
			@elseif(session()->has("successDeleteAssignment"))
				<div class="w-full bg-yellow-300 px-5 py-3 mb-5 rounded-lg">
					<p class="text-yellow-600" >{{ session("successDeleteAssignment") }}</p>
				</div>
			@endif

			<div class="overflow-x-auto rounded-md">
				<table class="min-w-full bg-white">
					<thead>
						<tr>
							<th class="border px-3">Title</th>
							<th class="border px-3">Description</th>
							<th class="border px-3">Deadline</th>
							<th class="border px-3">Download link</th>
							{{-- <th class="border px-3">Assigned to</th> --}}
							<th class="border px-3">Actions</th>
						</tr>
					</thead>
					<tbody>
						@if (count($assignments))
							@foreach($assignments as $asg)
								<tr>
									<td class="border px-3"><a class="text-blue-700 font-bold" href="{{ route("teacher.assignment.check", $asg->id) }}">{{ $asg->title }}</a></td>
									<td class="border px-3">{{ $asg->desc }}</td>
									<td class="border px-3">{{ $asg->deadline_date }}<br>{{ $asg->deadline_time }}</td>
									<td class="border px-3 text-blue-600"><a href="{{ $asg->link }}">{{ $asg->link }}</a></td>
									{{-- <td class="border px-3">{{ $asg->assigned_to->full_name }}</td> --}}
									{{-- <td class="border px-3">
										<ul>
											@for ($j = $i; $assignments[$j]->title == $assignments[$j + 1]->title; $j++)
												<li>{{ $assignments[$j]->assigned_to->full_name }}</li>
											@endfor
										</ul>
									</td> --}}
									<td class="border px-3">
										<div class="flex gap-1">
											<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
												href="{{ route("teacher.assignment.edit", $asg->id) }}">
												Edit
											</a>
											<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
												href="{{ route("teacher.assignment.delete", $asg->id) }}">
												Delete
											</a>
										</div>
									</td>
								</tr>
							@endforeach
						@else
							<tr ><td colspan="5" class="border px-3 text-center">- No assignments yet -</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>
