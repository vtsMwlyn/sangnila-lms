@extends("layouts.main-admin")

@section("title")
	<h1>All Announcement</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Announcements</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("All Announcements") }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(session()->has("successUploadAnnouncement"))
			<x-badge-success badge_text="{{ session('successUploadAnnouncement') }}">
			</x-badge-success>
		@elseif(session()->has("successEditAnnouncement"))
			<x-badge-success badge_text="{{ session('successEditAnnouncement') }}">
			</x-badge-success>
		@elseif(session()->has("successDeleteAnnouncement"))
			<x-badge-warning badge_text="{{ session('successDeleteAnnouncement') }}">
			</x-badge-warning>
		@endif

		<div class="mb-4">
			<x-anchor-button class="bg-orange-500 mt-8" href="{{ route('admin.announcement.create') }}"><i class="bi bi-plus-lg"></i> Add New Announcement</x-anchor-button>
		</div>

		@foreach($announcements as $announcement)
			<div class="p-5 my-2 rounded-xl" style="background: @if($loop->iteration % 2 == 1) white @else linear-gradient(to right, rgba(190, 226, 219, 0.49) 0%, rgba(104, 124, 120, 0) 100%) @endif;">
				<div class="flex w-full items-center justify-between">
					<div class="">
						<p class="text-blue-900 font-bold text-lg">
							<span>{{ $announcement->title }} </span>

							@php
								if(now() >= $announcement->announce_from && now() <= $announcement->announce_until){
									echo '<span class="text-green-700 font-bold italic">(Live)</span>';
								} else if(now() < $announcement->announce_from){
									echo '<span class="text-yellow-700 font-bold italic">(To be announced)</span>';
								} else if(now() > $announcement->announce_until){
									echo '<span class="text-red-600 font-bold italic">(Expired)</span>';
								}
							@endphp
						</p>
						<p class="mt-2">
							Announced to
							@php
								$sent_to = json_decode($announcement->sent_to, true);
								$roles = App\Models\Role::all();

								$roleNames = $roles->filter(function ($role, $i) use ($sent_to) {
									return isset($sent_to[$i]) && $sent_to[$i] == "on";
								})->map(function ($role) {
									return $role->role_name . 's';
								})->toArray();

								$lastItem = array_pop($roleNames);
								$formattedString = implode(', ', $roleNames);

								echo $formattedString . (empty($formattedString) ? '' : ', and ') . $lastItem;
							@endphp
						</p>
						<p class="mt-2">Period: <span class="font-bold">{{ $announcement->announce_from }}</span> until <span class="font-bold">{{ $announcement->announce_until }}</span></p>
					</div>
					<div class="flex gap-2">
						<x-anchor-button class="bg-orange-500" href="{{ route('admin.announcement.edit', $announcement->id) }}">
							<i class="bi bi-pencil-square"></i>
						</x-anchor-button>
						<x-anchor-button class="bg-orange-500" href="{{ route('admin.announcement.delete', $announcement->id) }}">
							<i class="bi bi-trash3"></i>
						</x-anchor-button>
						<x-button type="button" class="bg-orange-500 toggleBtn">
							<i class="bi bi-eye"></i>
						</x-button>
					</div>
				</div>

				<div class="overflow-x-auto contentTable pt-8" style="display: none;">
					@if($announcement->image_path)
						<div class="flex justify-center w-full mb-8">
							<img src="{{ Storage::url("app/public/" . $announcement->image_path) }}" alt="announcement_img" class="w-3/4">
						</div>
					@endif
					<div class="announcementContent">
						{!! $announcement->content !!}
					</div>
				</div>
			</div>
		@endforeach

	</x-section-container>

	<script>
		$(document).ready(() => {
			const allToggleBtn = $(".toggleBtn");
			const allContentTable = $(".contentTable");

			allToggleBtn.each(function(index, element) {
				$(element).click(() => {
					allContentTable.eq(index).slideToggle();
				});
			});

			$(".announcementContent a").each((index, anchor) => {
				$(anchor).attr("target", "blank");
			});
		});
	</script>
@endsection
