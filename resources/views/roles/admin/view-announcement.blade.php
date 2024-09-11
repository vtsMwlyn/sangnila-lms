@extends("layouts.main-admin")

@section("title")
	<h1>Announcement</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.announcement.index') }}" class="font-bold text-yellow-500">Announcement</a>
	> <span>{{ $announcement->title }}</span>
@endsection

@section("content")
	@include("roles.admin.announcement.show")
@endsection
