@extends("layouts.main-teacher")

@section("title")
	<h1>Student Assignment</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
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

		<div class="w-full overflow-x-auto">
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
							<td class="py-2 px-4 w-1/5">
								<a class="text-blue-600 hover:underline font-bold" href="{{ route('teacher.assignment.check', $asg->id) }}">{{ $asg->title }}</a>
								<br><br>
								{{ Carbon\Carbon::parse($asg->deadline_date)->format('d M Y') }}
								<br>
								{{ Carbon\Carbon::parse($asg->deadline_time)->format("H:i") }} GMT+7
							</td>
							<td class="py-2 px-4 w-1/5">
								<div class="flex w-full flex-col gap-3 overflow-y-auto" style="max-height: 100px;">
									@foreach ($asg->student_assignments as $sasg)
										<div class="flex items-center gap-3">
											@if($sasg->student->details->profpic)
												<img src="{{ Storage::url("app/public/" . $sasg->student->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
											@else
												<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
											@endif
											{{ $sasg->student->full_name }}
										</div>
									@endforeach
								</div>
							</td>
							<td class="py-2 px-4" style="max-width: 350px; overflow-wrap: break-word;">
								@if(strlen($asg->desc) > 60)
									<div class="">{!! nl2br(substr($asg->desc, 0, 60)) !!}... <button type="button" class="show-more-button text-blue font-semibold text-xs">[Show More]</button></div>
									<div class="hidden">{!! nl2br($asg->desc) !!} <button type="button" class="show-less-button text-blue font-semibold text-xs">[Show Less]</button></div>
								@else
									{!! nl2br($asg->desc) !!}
								@endif
								<a href="{{ $asg->link }}" class="text-blue-600 hover:underline font-bold">{{ $asg->link }}</a>
							</td>
							<td class="py-2 px-4">
								<div class="flex gap-1">
									<a href="{{ route('teacher.assignment.edit', $asg->id) }}">
										<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
									<a href="{{ route('teacher.assignment.delete', $asg->id) }}">
										<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
									</a>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="5" class="bg-white rounded-xl p-5 font-semibold text-center">- No assignments yet -</td></tr>
					@endforelse
				</tbody>
			</table>
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
		});
	</script>
@endsection
