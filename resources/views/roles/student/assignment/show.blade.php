@extends("layouts.main-student")

@section("title")
	<h1>Assignment</h1>
@endsection

@section("popup")
	<x-popup class="w-1/2 h-1/2 flex items-center justify-center font-bold" id="submission-history">
	</x-popup>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
		<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8" alt="back"></button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(session()->has("successSubmitAssignment"))
			<x-badge-success badge_text="{{ session('successSubmitAssignment') }}" class="mb-4"></x-badge-success>
		@elseif(session()->has("successEditSubmission"))
			<x-badge-success badge_text="{{ session('successEditSubmission') }}"></x-badge-success>
		@elseif(session()->has("maximumSubmission"))
			<x-badge-danger badge_text="{{ session('maximumSubmission') }}"></x-badge-danger>
		@endif

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Title</th>
					<th class="py-3 px-4 border-b-2 border-slate-400">Due Date</th>
					<th class="py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="py-3 px-4 border-b-2 border-slate-400">Action</th>
					<th class="py-3 px-4 border-b-2 border-slate-400">History</th>
				</thead>
				<tbody>
					@forelse ($assignments as $index => $asg)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4">{{ $asg->title }}</td>
							<td class="py-2 px-4">
								<div class="flex justify-center">
									{{ $asg->deadline_date }},<br>{{ $asg->deadline_time }}
								</div>
							</td>
							<td class="py-2 px-4">
								<div class="flex flex-col w-full items-center">
									@if(count($submissions_per_assignment[$index]) > 0)
										<span class="font-bold text-light-blue">Submitted</span>
									@else
										<span class="font-bold text-red">Not Yet</span>
										<span class="font-bold text-red">Submitted</span>
									@endif
								</div>
							</td>
							<td class="py-2 px-4">
								<div class="flex justify-center gap-2 w-full">
									<a href="{{ $asg->link }}" target="blank">
										<img src="{{ asset('img/download.svg') }}" alt="download-icon" class="w-8 h-8 hover:scale-110">
									</a>
									<a href="{{ route('student.assignment.submit', [$course->id, $asg->id]) }}">
										<img src="{{ asset('img/attach.svg') }}" alt="submit-icon" class="w-8 h-8 hover:scale-110">
									</a>
								</div>
							</td>
							<td class="py-2 px-4">
								<div class="flex justify-center gap-2 w-full">
									{{-- <a href="{{ route("student.assignment.detail", [$course->id, Auth::user()->id ,$asg->id]) }}" id="submission-history" class="popup-trigger">
										<img src="{{ asset('img/history.svg') }}" alt="history-icon" class="w-8 h-8 hover:scale-110">
									</a> --}}
									<button type="button" class="submissionhistory-popuptrigger" id="{{ $loop->iteration }}">
										<img src="{{ asset('img/history.svg') }}" alt="history-icon" class="w-8 h-8 hover:scale-110">
									</button>
								</div>
							</td>
						</tr>
					@empty
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- @forelse ($assignments as $index => $asg)
			<div class="rounded-md w-full mt-5 my-5 p-5 border" style="background: rgba(256, 256, 256, 0.4)">
				<h3 class="text-xl font-semibold text-blue-950">{{ $asg->title }}</h3>

				@if($submissions_per_assignment[$index] > 0)
					<div class="flex items-center gap-2">
						<p class="mt-2 mb-2">Submitted</p>
						<i class="bi bi-patch-check-fill text-2xl text-green-800"></i>
					</div>
					<a class="text-blue-800 hover:underline font-bold" href="{{ route("student.assignment.detail", [$course->id, Auth::user()->id ,$asg->id]) }}">Submission history and feedback</a>
				@endif

				<p class="mt-3 italic ">Assignment Description:</p>
				<p class="mt-1 ">{{ $asg->desc }}</p>

				<p class="mt-3 text-blue-950 ">Please submit before <span class="font-bold">{{ $asg->deadline_date }} {{ $asg->deadline_time }}</span></p>

				@if($submissions_per_assignment[$index] < 10)
					<p class="font-bold  text-blue-950">New submissions allowed: {{ 10 - $submissions_per_assignment[$index] }} time(s)</p>
				@else
					<p class="font-bold  text-red-500">Number of new submissions reached its limit!</p>
				@endif

				<div class="flex items-center gap-3 mt-5 mb-3">
					<x-anchor-button class="bg-orange-500"
						href="{{ $asg->link }}">
						Download
					</x-anchor-button>
					@if($asg->submissions && $asg->submissions->where("student_id", Auth::user()->id)->count())
						<x-anchor-button class="bg-orange-500"
							href="{{ route('student.assignment.submit', [$course->id, $asg->id]) }}">
							New submission
						</x-anchor-button>
					@else
						<x-anchor-button class="bg-orange-500"
							href="{{ route('student.assignment.submit', [$course->id, $asg->id]) }}">
							Upload
						</x-anchor-button>
					@endif
				</div>
			</div>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No assignments given yet -</div>
		@endforelse --}}
	</div>

	<script>
		$(document).ready(() => {
			const all_submission_data = @json($submissions_per_assignment);

			$(".submissionhistory-popuptrigger").click(function(){
				$("#submission-history").text(JSON.stringify(all_submission_data[parseInt($(this).attr("id")) - 1]));
				$("#submission-history").parent().show();
			});
		});

	</script>
@endsection
