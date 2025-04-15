@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section('popup')
	{{-- Delete assignment --}}
	<x-confirmation method="delete" popup_title="Delete Assignment" id="delete-assignment-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> the Assignment <span class="font-bold text-light-blue" id="del-a-name"></span> from this course?
	</x-confirmation>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.assignment.index') }}"></x-back-button>
		<x-page-title>{{ $course->course_name }} - {{ ucwords($course->level) }}</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Assignments List</h1>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<div class="mt-6">
			<x-anchor-button
				href="{{ route('teacher.assignment.upload', $course->id) }}">
				<i class="bi bi-plus-lg"></i> Upload New Assignment
			</x-anchor-button>
		</div>

		<div class="w-full bg-slate-400 mt-6" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto hidden xl:block">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Title & Deadline</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Assigned to</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400" style="max-width: 350px;">Description & Link</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($assignments as $asg)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-3 px-4 w-1/4">
								<span class="font-semibold">{{ $asg->title }}</span>
								<br><br>
								{{ Carbon\Carbon::parse($asg->deadline_date)->format('d M Y') }}
								<br>
								{{ Carbon\Carbon::parse($asg->deadline_time)->format("H:i") }} GMT+7
							</td>
							<td class="py-3 px-4 w-1/4">
								<div class="flex w-full flex-col gap-3 overflow-y-auto" style="max-height: 100px;">
									@foreach ($asg->student_assignments as $sasg)
										<div class="flex items-center gap-3">
											@if($sasg->student->details->profpic)
												<img src="{{ Storage::url("app/public/" . $sasg->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
											@else
												<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
											@endif
											{{ $sasg->student->full_name }}
										</div>
									@endforeach
								</div>
							</td>
							<td class="py-3 px-4" style="max-width: 350px; overflow-wrap: break-word;">
								@if(strlen($asg->desc) > 60)
									<div class="">{!! nl2br(substr($asg->desc, 0, 60)) !!}... <button type="button" class="show-more-button text-blue font-semibold text-xs">[Show More]</button></div>
									<div class="hidden">{!! nl2br($asg->desc) !!} <button type="button" class="show-less-button text-blue font-semibold text-xs">[Show Less]</button></div>
								@else
									{!! nl2br($asg->desc) !!}
								@endif
								<a href="{{ $asg->link }}" class="text-blue-600 hover:underline font-bold">{{ $asg->link }}</a>
							</td>
							<td class="py-3 px-4">
								<div class="flex gap-1">
									<a href="{{ route('teacher.assignment.check', $asg->id) }}" title="Check this assignment's submission">
										<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
									<a href="{{ route('teacher.assignment.edit', $asg->id) }}" title="Edit this assignment">
										<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
									<button type="button" data-route="{{ route('teacher.assignment.destroy', $asg->id) }}" title="Delete this assignment" class="delete-assignment-btn">
										<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="4" class="bg-white p-5 font-semibold text-center">- No assignments yet -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- For smaller screen --}}
        <div class="w-full flex flex-col gap-4 xl:hidden items-stretch mt-4">
            @forelse ($assignments as $asg)
                <div class="bg-white rounded-xl p-4 flex flex-col gap-3 dropdown-container">
                    <button class="flex flex-col items-start dropdown-toggler w-full">
                        <div class="flex w-full justify-between items-center mb-2">
                            <strong class="text-base text-start">{{ $asg->title }}</strong>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </button>
                    <div class="flex flex-col w-full dropdown-menu" style="display: none;">
						<strong>Information</strong>

						<div class="mt-3">
							<i>Deadline</i><br>{{ Carbon\Carbon::parse($asg->deadline_date)->format('d M Y') }}, {{ Carbon\Carbon::parse($asg->deadline_time)->format("H:i") }} GMT+7
						</div>

						<div class="mt-5">
							<i>Description</i><br>{!! nl2br($asg->desc) !!}
						</div>

						<div class="mt-5">
							<i>Link</i><br><a href="{{ $asg->link }}" class="text-blue-600 hover:underline font-bold">{{ $asg->link }}</a>
						</div>

						<div class="mt-5">
							<i>Assigned to</i>
							@foreach ($asg->student_assignments as $sasg)
								<div class="flex items-center gap-3 my-3">
									@if($sasg->student->details->profpic)
										<img src="{{ Storage::url("app/public/" . $sasg->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy">
									@else
										<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
									@endif
									{{ $sasg->student->full_name }}
								</div>
							@endforeach
						</div>

                        <strong class="mt-5">Actions</strong>
                        <div class="flex gap-3 items-start my-3">
							<a href="{{ route('teacher.assignment.check', $asg->id) }}">
								<img src="{{ asset('img/view.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
							</a>
							<a href="{{ route('teacher.assignment.edit', $asg->id) }}">
								<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
							</a>
							<button type="button" data-route="{{ route('teacher.assignment.destroy', $asg->id) }}" title="Delete this assignment" class="delete-assignment-btn">
								<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
							</button>
                        </div>
                    </div>
                </div>
            @empty
                - N/A -
            @endforelse
        </div>
	</x-section-container>

	<script>
		$(document).ready(() => {
			$(".show-more-button").click(function(){
				$(this).closest("div").next().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});

			$(".show-less-button").click(function(){
				$(this).closest("div").prev().removeClass("hidden");
				$(this).closest("div").addClass("hidden");
			});

			$('.delete-assignment-btn').on('click', function(){
				$('#delete-assignment-popup').find('form').attr('action', $(this).data('route'));
				$('#delete-assignment-popup').parent().show();
			});
		});
	</script>
@endsection
