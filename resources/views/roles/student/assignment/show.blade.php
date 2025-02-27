@extends("layouts.main-student")

@section("title")
	<h1>Assignment</h1>
@endsection

@section("popup")
	<x-popup popup_title="History" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="submission-history">
		<!-- Popup content -->
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">No</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Time</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Title</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Note</th>
					</thead>
					<tbody id="submission-history-tbody">
					</tbody>
				</table>
			</div>
		</div>
	</x-popup>

	<x-popup popup_title="Assignment Submission" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="submit-assignment">
		<!-- Popup content -->
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<p class="mt-3 font-semibold">Assignment Description:</p>
			<p class="mt-1" id="assignment-desc"></p>

			<p class="mt-3 text-blue">Please submit before <span class="font-bold" id="assignment-deadline"></span></p>

			<form action="#" method="post" class="mt-4" id="submit-form">
				@csrf
				<div class="flex flex-col">
					<label for="title">Submission Title<span class="text-red">*</span></label>
					<x-input id="title" class="w-full mt-1" type="text" name="title" style="border-width: 3px;" value="{{ old('title') }}" placeholder="Submission Title" autofocus />
				</div>

				<div class="flex flex-col mt-4">
					<label for="link">Your Work Link<span class="text-red">*</span></label>
					<x-input id="link" class="w-full mt-1" type="text" name="link" style="border-width: 3px;" value="{{ old('link') }}" placeholder="Your Work Link" autofocus />
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-1/6">Submit</x-button>
					{{-- <x-button class="w-full md:w-1/6">Cancel</x-button> --}}
				</div>

				<!-- Helper -->
				<input type="hidden" name="h-asg" id="h-asg">
				<input type="hidden" name="h-route" id="h-route">
				<input type="hidden" name="h-n" id="h-n">
			</form>
		</div>
	</x-popup>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col items-stretch sm:text-base text-sm" style="background: #FEFEFEB2;">
		<div class="flex w-full justify-between items-end">
			<div class="">
				<x-back-button href="{{ route('student.assignment.index') }}"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8" alt="back"></x-back-button>
				<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
			</div>
			<div class="flex gap-8">
				<div class="flex flex-col gap-1 text-xs text-gray-500">
					<div class="flex items-center gap-1">
						<img src="{{ asset('img/view.svg') }}" class="h-6 w-6" alt="icon">
						<p>: View Assignment</p>
					</div>
					<div class="flex items-center gap-1">
						<img src="{{ asset('img/download.svg') }}" class="h-6 w-6" alt="icon">
						<p>: Download Assignment</p>
					</div>
				</div>
				<div class="flex flex-col gap-1 text-xs text-gray-500">
					<div class="flex items-center gap-1">
						<img src="{{ asset('img/attach.svg') }}" class="h-6 w-6" alt="icon">
						<p>: Submit Answer</p>
					</div>
					<div class="flex items-center gap-1">
						<img src="{{ asset('img/history.svg') }}" class="h-6 w-6" alt="icon">
						<p>: History</p>
					</div>
				</div>
			</div>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(session()->has("successSubmitAssignment"))
			<x-badge-success badge_text="{{ session('successSubmitAssignment') }}" class="mb-4"></x-badge-success>
		@elseif(session()->has("successEditSubmission"))
			<x-badge-success badge_text="{{ session('successEditSubmission') }}" class="mb-4"></x-badge-success>
		{{-- @elseif(session()->has("maximumSubmission"))
			<x-badge-danger badge_text="{{ session('maximumSubmission') }}" class="mb-4" id="max-submission-badge"></x-badge-danger> --}}
		@endif

		<x-badge-danger badge_text="This assignment's maximum submission is reached." class="mb-4" id="max-submission-badge" style="display: none;"></x-badge-danger>

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
							<td class="py-2 px-4">
								<strong>{{ $asg->title }}</strong><br>
								@if(strlen($asg->desc) > 30)
									<div class="">{!! nl2br(substr($asg->desc, 0, 30)) !!}... <button type="button" class="show-more-button text-blue font-semibold text-xs">[Show More]</button></div>
									<div class="hidden">{!! nl2br($asg->desc) !!} <button type="button" class="show-less-button text-blue font-semibold text-xs">[Show Less]</button></div>
								@else
									{!! nl2br($asg->desc) !!}
								@endif
							</td>
							<td class="py-2 px-4">
								<div class="flex justify-center">
									{{ date("d M Y", strtotime($asg->deadline_date)) }},<br>{{ substr($asg->deadline_time, 0, 5) }} GMT+7
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
									<a href="{{ $asg->link }}" target="_blank">
										<img src="{{ asset('img/view.svg') }}" alt="view-icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
									<a href="{{ $asg->link }}" target="_blank">
										<img src="{{ asset('img/download.svg') }}" alt="download-icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
									<div class="relative">
										<button type="button" class="submitassignment-popuptrigger" data-route="{{ route('student.assignment.store', [$course->id, $asg->id]) }}"
											data-assignment="{{ $asg->toJSON() }}" data-submissions="{{ count($submissions_per_assignment[$index]) }}">
											<img src="{{ asset('img/attach.svg') }}" alt="history-icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
										@if(count($submissions_per_assignment[$index]) == 0)
											<div class="h-6 w-6 rounded-full bg-red absolute animate-bounce text-white flex items-center justify-center" style="top: -8px; right: -8px;">!</div>
										@endif
									</div>
								</div>
							</td>
							<td class="py-2 px-4">
								<div class="flex justify-center gap-2 w-full">
									<button type="button" class="submissionhistory-popuptrigger" id="{{ $loop->iteration }}">
										<img src="{{ asset('img/history.svg') }}" alt="history-icon" class="w-8 h-8 hover:scale-110">
									</button>
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-2 px-4 text-center" colspan="5">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

	<script>
		function initializeAssignmentSubmissionPopup(assignment, route, n){
			if(n == 10){
				$("#max-submission-badge").show();

				return;
			}

			// Retrieve and save selected data
			$("#h-asg").val(JSON.stringify(assignment));
			$("#h-route").val(route);
			$("#h-n").val(n);

			// Fill the popup with data
			$("#assignment-title").text(assignment.title);
			$("#assignment-desc").text(assignment.desc);
			$("#assignment-deadline").text(new Date(`${assignment.deadline_date} ${assignment.deadline_time}`).toLocaleString('en-GB', {
					day: '2-digit',
					month: 'short',
					year: 'numeric',
					hour: '2-digit',
					minute: '2-digit',
					hour12: false
				}));
			$("#submit-form").attr("action", route);

			// Display the popup
			$("#submit-assignment").parent().show();
		}

		$(document).ready(() => {
			const all_submission_data = @json($submissions_per_assignment);

			$('.submitassignment-popuptrigger').on('click', function() {
				// Retrieve and save selected data
				const n = $(this).data('submission');
				const route = $(this).data('route');
				const assignment = $(this).data('assignment');

				initializeAssignmentSubmissionPopup(assignment, route, n);
			});

			// Redisplay popup and fill with prev data
			@if ($errors->any())
				// Retrieve and re-save saved data
				const old_asg = JSON.parse(@json(old('h-asg')));
				const old_route = @json(old('h-route'));
				const old_n = @json(old('h-n'));

				initializeAssignmentSubmissionPopup(old_asg, old_route, old_n);
			@endif

			$(".submissionhistory-popuptrigger").click(function(){
				$("#submission-history-tbody").empty();

				let i = 0;
				for(let submission of all_submission_data[parseInt($(this).attr("id")) - 1]){
					const col1 = $("<td>").addClass("py-2 px-4 text-center").text(i + 1);
					const col2 = $("<td>").addClass("py-2 px-4").text(new Date(submission.created_at).toLocaleString('en-GB', {
						day: '2-digit',
						month: 'short',
						year: 'numeric',
						hour: '2-digit',
						minute: '2-digit',
						hour12: false
					}));
					const col3 = $("<td>").addClass("py-2 px-4").html(`<a href="${submission.link}" class="font-semibold hover:underline">${submission.title}</a>`);
					const col4 = $("<td>").addClass("py-2 px-4").text(submission.feedback);

					let rowBg;
					if(i % 2 == 0){
						rowBG = "rgb(237, 241, 247)";
					}
					else {
						rowBG = "white";
					}

					$("#submission-history-tbody").append(
						$("<tr>").css("background-color", rowBG).append(col1).append(col2).append(col3).append(col4)
					);
					i++;
				}

				$("#submission-history").parent().show();
			});

			$(".show-more-button").click(function(){
				$(this).closest("div").next().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});

			$(".show-less-button").click(function(){
				$(this).closest("div").prev().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});
		});
	</script>
@endsection
