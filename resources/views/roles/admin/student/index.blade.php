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
			<h1 class="text-3xl font-semibold text-blue-900 mb-4">Students</h1>

			@if ($students->isNotEmpty())
				<div class="overflow-x-auto rounded-md">
					<table class="min-w-full bg-white border-collapse ">
						<thead>
							<tr>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Student Name</th>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Email</th>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4" >Enrolled Courses and Progress</th>
								<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2 sm:w-1/4">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($students as $student)
								@if($student->status == "disabled")
									@continue
								@endif
								<tr>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										<a href="{{ route('admin.student.show', ['student_id' => $student->id]) }}"
											class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
											{{ $student->full_name }}
										</a>
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										{{ $student->email }}
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-6 py-2 sm:w-1/4">
										@if($student->enrolled_courses->count())
											<ul>
												@foreach ($student->enrolled_courses as $course)
													<li class="flex justify-between">
														<span>{{ $course->course_name }}</span>
														{{-- <span>[Progress: 0/0]</span> --}}
														<span>
															@php
																$all_progress_in_current_course = [];
																foreach($student->progress as $pgr){
																	if($pgr->course_id == $course->id){
																		array_push($all_progress_in_current_course, $pgr);
																	}
																}

																$count = 0;
																foreach($all_progress_in_current_course as $curr_pgr){
																	if($curr_pgr->status == "unlocked"){
																		$count++;
																	}
																}

																echo "[Progress: " . $count . "/" . count($all_progress_in_current_course) . "]";
															@endphp
														</span>
													</li>
												@endforeach
											</ul>
										@else
											<p class="text-center">- No courses assigned yet -</p>
										@endif
									</td>
									<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 sm:w-1/4">
										<div class="flex w-full justify-center gap-1">
											<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
											href="{{ route("admin.student.show", $student->id) }}">
												View
											</a>
											<a class="px-5 py-2 bg-indigo-400 rounded-lg text-white hover:bg-gray-700 transition duration-300"
												href="{{ route("admin.student.edit", $student->id) }}">
												Edit
											</a>
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
	</div>

</body>

</html>
