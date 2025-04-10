@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection


@section('popup')
	<x-popup popup_title="Student's Submission History" class="w-5/6 flex flex-col items-stretch justify-center overflow-y-auto" id="student-submission-popup">
		{{-- Popup content --}}
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<div class="w-full overflow-x-auto">
				<table class="w-full">
					<thead>
						<th class="py-3 px-4 border-b-2 border-slate-400">Time</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400 w-1/4">Title</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400 w-1/4">Link</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400 w-1/4">Feedback</th>
					</thead>
					<tbody id="student-submission-tbody">
					</tbody>
				</table>
			</div>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.assignment.show', $assignment->course->id) }}"></x-back-button>
		<x-page-title>{{ $assignment->title }}</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Students Submissions</h1>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<div class="w-full overflow-x-auto hidden xl:block">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Submission Time</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400 w-1/4">Student</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400 w-1/4">Submission title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400 w-1/4">Submission link</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Action</th>
				</thead>
				<tbody>
					@forelse ($latest_submissions as $submission)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-3 px-4">
								<div class="whitespace-nowrap">{{ $submission->created_at->format('d M Y') }}</div>
								<div class="whitespace-nowrap">{{ $submission->created_at->format('H:i') }} GMT+7</div>
							</td>
							<td class="py-3 px-4 w-1/4">
								<div class="flex items-center gap-3">
									@if($submission->student->details->profpic)
										<img src="{{ Storage::url("app/public/" . $submission->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
									@else
										<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
									@endif
									{{ $submission->student->full_name }}
								</div>
							</td>
							<td class="py-3 px-4 w-1/4">{{ $submission->title }}</td>
							<td class="py-3 px-4">
								<div class="font-bold whitespace-nowrap @if($submission->status == 'Late') text-red @else text-light-blue @endif">{{ $submission->status }}</div>
							</td>
							<td class="py-3 px-4 w-1/4">
								<a class="text-blue-600 hover:underline font-bold break-all" href="{{ $submission->link }}" target="_blank">{{ $submission->link }}</a>
							</td>
							<td class="py-3 px-4">
								<div class="flex w-full justify-center">
									<button type="button" class="student-submission-btn" data-submissions="{{ $assignment->submissions->where('student_id', $submission->student_id) }}" data-feedback_route="{{ route("teacher.assignment.feedback", [$submission->id, $submission->student->id]) }}"><img src="{{ asset('img/history.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td class="bg-white text-center font-semibold p-5" colspan="6">- No submissions yet -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- For smaller screen --}}
        <div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
            @forelse ($latest_submissions as $submission)
                <div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
                    <button class="flex flex-col items-start dropdown-toggler w-full">
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="text-base text-start">
								<div class="flex gap-2 items-center">
									@if($submission->student->details->profpic)
										<img src="{{ Storage::url("app/public/" . $submission->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
									@else
										<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
									@endif
									<div class="flex flex-col">
										<strong>{{ $submission->student->full_name }}</strong>
										<span>{{ $submission->created_at->format('d M Y') }}, {{ $submission->created_at->format('H:i') }} GMT+7</span>
									</div>
								</div>
							</div>
							
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </button>
                    <div class="flex flex-col w-full dropdown-menu" style="display: none;">
						{{ $submission->title }}
                        <a class="text-blue-600 hover:underline font-bold break-all mt-3" href="{{ $submission->link }}" target="_blank">{{ $submission->link }}</a>

                        <strong class="mt-5">Actions</strong>
                        <div class="flex gap-3 items-start my-3">
							<button type="button" class="student-submission-btn" data-submissions="{{ $assignment->submissions->where('student_id', $submission->student_id) }}" data-feedback_route="{{ route("teacher.assignment.feedback", [$submission->id, $submission->student->id]) }}"><img src="{{ asset('img/history.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110"></button>
                        </div>
                    </div>
                </div>
            @empty
                - N/A -
            @endforelse
        </div>
	</x-section-container>

	<script>
		function formatDate(isoString) {
			let date = new Date(isoString);

			// Define options for formatting
			let options = {
				weekday: 'short',  // "Tue"
				day: '2-digit',    // "13"
				month: 'short',    // "Jan"
				year: 'numeric',   // "2025"
				hour: '2-digit',   // "09"
				minute: '2-digit', // "36"
				timeZone: 'Asia/Jakarta', // GMT+7
			};

			return new Intl.DateTimeFormat('en-GB', options).format(date).replace(',', '') + ' GMT+7';
		}

		$(document).ready(() => {
			$('.student-submission-btn').on('click', function(){
				const submissions = $(this).data('submissions');
				$('#student-submission-tbody').html('');

				submissions.forEach((submission, i) => {
					$('#student-submission-tbody').append(
						$('<tr>').css('background-color', i % 2 === 0? 'rgb(237, 241, 247)' : 'white')
							.append(
								$('<td>').addClass('py-3 px-4').text(formatDate(submission.created_at))
							)
							.append(
								$('<td>').addClass('py-3 px-4 w-1/4').text(submission.title)
							)
							.append(
								$('<td>').addClass('py-3 px-4 w-1/4').html(`<a href="${submission.link}" class="font-bold text-blue-600 hover:underline">${submission.link}</a>`)
							)
							.append(
								$('<td>').addClass(`py-3 px-4 font-bold whitespace-nowrap ${submission.status == 'On Time'? 'text-light-blue' : 'text-red'}`).text(submission.status)
							)
							.append(
								$('<td>').addClass('py-3 px-4 w-1/4').append(
									$('<form>').attr('action', $(this).data('feedback_route')).attr('method', 'post').addClass('flex items-center gap-2 w-full')
										.append(
											$('<input>').attr('type', 'hidden').attr('name', '_token').val(document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
										)
										.append(
											$('<input>').addClass('border-slate-400 focus:border-slate-600 focus:ring-0 rounded-2xl shadow-sm focus:outline-none py-2 px-4 disabled:cursor-not-allowed').css('border-width', '3px').attr({'type': 'text', 'name': 'feedback', 'id': `feedback${i}`, 'placeholder': 'Enter your feedback', 'value': submission.feedback})
										)
										.append(
											$('<button>').attr('type', 'submit').html(`<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">`)
										)
								)
							)
					);
				});

				$('#student-submission-popup').parent().show();
			});
		});
	</script>
@endsection
