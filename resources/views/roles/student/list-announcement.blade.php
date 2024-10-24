@extends("layouts.main-student")

@section("title")
	<h1>Announcement</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Announcement</span>
@endsection

@section("content")
	@include("roles.admin.announcement.list")
@endsection
