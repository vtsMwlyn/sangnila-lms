@extends("layouts.main-admin")

@section("title")
	<h1>All Announcement</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Announcements</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("All Announcements") }}</x-page-title>

		<x-anchor-button class="bg-orange-500 mt-8" href="{{ route('admin.announcement.create') }}"><i class="bi bi-plus-lg"></i> Add New Announcement</x-anchor-button>

		@foreach($announcements as $announcement)
			<div class="p-5 my-8 rounded-xl" style="background-color: rgba(255, 255, 255, 0.3)">
				<div class="flex w-full items-center justify-between">
					<div class="">
						<p class="text-blue-900 font-bold">{{ $announcement->title }}</p>
						<p>
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
					</div>
					<div class="flex gap-3">
						<x-anchor-button class="bg-orange-500" href="#">
							Edit Announcement
						</x-anchor-button>
						<x-anchor-button class="bg-orange-500" href="#">
							Unannounce
						</x-anchor-button>
						<x-button type="button" class="bg-orange-500 toggleBtn">Show Content</x-button>
					</div>
				</div>

				<div class="overflow-x-auto contentTable pt-8" style="display: none;">
					@if($announcement->image_path)
						<div class="flex justify-center w-full mb-8">
							<img src="{{ asset('storage/' . $announcement->image_path) }}" alt="announcement_img" class="w-3/4">
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
					allContentTable.eq(index).slideToggle(() => {
					if (allContentTable.eq(index).is(":visible")) {
						$(element).text("Hide Content");
					} else {
						$(element).text("Show Content");
					}
				});
				});
			});

			$(".announcementContent a").each((index, anchor) => {
				$(anchor).attr("target", "blank");
			});
		});
	</script>
@endsection
