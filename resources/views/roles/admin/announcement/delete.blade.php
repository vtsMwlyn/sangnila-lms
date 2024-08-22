@extends("layouts.main-admin")

@section("title")
	<h1>{{ $announcement->title }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route("admin.announcement.index") }}" class="font-bold text-yellow-500">Announcements</a>
	> <span>{{ $announcement->title }}</span>
	> <span>Delete</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Delete Announcement") }}</x-page-title>

		<div class="mt-10">
			<form method="POST" action="{{ route('admin.announcement.destroy', $announcement->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10">
				@csrf
				<!-- Confirmation Text -->
				<div class="mb-6">
					<h1 class="text-xl font-semibold text-white">
						Are you sure you want to permanently delete announcement <span class="text-yellow-500 font-bold">{{ $announcement->title }}</span>? Deleted announcement <span class="font-bold text-red-500">won't appear</span> again in Sangnila LMS.
					</h1>
				</div>

				<!-- Yes/No Buttons -->
				<div class="flex gap-3 justify-center w-full">
					<x-button type="submit" class="bg-orange-500 w-full md:w-1/12">Yes</x-button>
					<x-button type="button" onclick="history.back()" class="bg-slate-600 w-full md:w-1/12">No</x-button>
				</div>
			</form>
		</div>
	</x-section-container>
@endsection
