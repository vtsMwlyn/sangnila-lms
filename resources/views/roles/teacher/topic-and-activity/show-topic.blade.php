@extends("layouts.main-teacher")

@section("title")
	<h1>Courses</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('teacher.mycourse.show', $topic->course->id) }}" class="font-bold text-yellow-500">{{ $topic->course->course_name }}</a>
	> <span>{{ $topic->title }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.mycourse.show', $topic->course->id) }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $topic->course->course_name }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Topic and Activities Details</h1>
		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		@if(session()->has("successUpdateTopic"))
			<x-badge-success badge_text="{{ session('successUpdateTopic') }}"></x-badge-success>
		@elseif(session()->has("successAddTopic"))
			<x-badge-success badge_text="{{ session('successAddTopic') }}"></x-badge-success>
		@elseif(session()->has("successEditTopic"))
			<x-badge-success badge_text="{{ session('successEditTopic') }}"></x-badge-success>
		@elseif(session()->has('successUploadActivity'))
			<x-badge-success badge_text="{{ session('successUploadActivity') }}"></x-badge-success>
		@elseif(session()->has('successEditActivity'))
			<x-badge-success badge_text="{{ session('successEditActivity') }}"></x-badge-success>
		@elseif(session()->has("successDeleteActivity"))
			<x-badge-warning badge_text="{{ session('successDeleteActivity') }}"></x-badge-warning>
		@endif

		<h2 class="mt-4 mb-2 font-extrabold text-xl text-dark-blue">Topic</h2>

		<form action="{{ route('teacher.mycourse.topic.update', [$topic->course->id, $topic->id]) }}" method="post" class="w-full flex flex-col gap-4">
			@method('patch')
			@csrf

			<div class="flex flex-col grow">
				<x-input id="title" class="w-full mt-1" type="text" name="title" placeholder="Topic title" :value="old('title', $topic->title)" />
			</div>

			<div class="flex gap-2 w-full justify-end">
				<x-button class="bg-orange-500" type="submit"><i class="bi bi-pencil-square"></i> Edit Topic Name</x-button>
				<x-anchor-button class="bg-orange-500" href="{{ route('teacher.mycourse.topic.delete', [$topic->course->id, $topic->id]) }}"><i class="bi bi-trash3"></i> Delete</x-anchor-button>
			</div>
		</form>


		<h2 class="my-4 font-extrabold text-xl text-dark-blue">List of Activities</h2>

		<div class="flex">
			<x-anchor-button class="bg-orange-500" href="{{ route('teacher.mycourse.activity.upload', $topic->id) }}"><i class="bi bi-plus-lg"></i> Add New Activity</x-anchor-button>
		</div>

		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Title</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Activity Description</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Outcomes</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Link</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
				</thead>
				<tbody>
					@forelse ($topic->activities as $activity)
						<tr class="@if($loop->index % 2 == 0) bg-white @endif">
							<td class="py-2 px-4 w-1/4">{{ $activity->title }}</td>
							<td class="py-2 px-4 w-1/2">
								@if(strlen($activity->desc) > 120)
									<div class="">{{ substr($activity->desc, 0, 120) }}... <button type="button" class="show-more-button text-blue font-semibold text-xs">[Show More]</button></div>
									<div class="hidden">{{ $activity->desc }} <button type="button" class="show-less-button text-blue font-semibold text-xs">[Show Less]</button></div>
								@else
									{{ $activity->desc }}
								@endif
							</td>
							<td class="py-2 px-4">
								@forelse ($activity->learning_outcomes as $leaout)
									LO{{ $leaout->number }}@if($activity->learning_outcomes->count() > 1 && $loop->index != $activity->learning_outcomes->count() - 1), @endif
								@empty
									N/A
								@endforelse
							</td>
							<td class="py-2 px-4" style="max-width: 18vw; word-wrap: break-word;">
								@if($activity->link)
									<a href="{{ $activity->link }}" target="blank" class="font-bold text-blue-600 hover:underline">{{ $activity->link }}</a>
								@else
									N/A
								@endif
							</td>

							<td class="py-2 px-4">
								<div class="flex gap-1">
									<x-anchor-button class="bg-orange-500"
										href="{{ route('teacher.mycourse.activity.edit', $activity->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>
									<x-anchor-button class="bg-orange-500"
										href="{{ route('teacher.mycourse.activity.remove', $activity->id) }}">
										<i class="bi bi-trash3"></i>
									</x-anchor-button>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="4" class="text-center p-5 bg-white rounded-xl w-full font-semibold">- No activities added yet to this course topic -</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</x-section-container>

	<script>
		$(".show-more-button").click(function(){
			$(this).closest("div").next().removeClass("hidden");
			$(this).closest("div").addClass("hidden");
		});

		$(".show-less-button").click(function(){
			$(this).closest("div").prev().removeClass("hidden");
			$(this).closest("div").addClass("hidden");
		});
	</script>
@endsection
