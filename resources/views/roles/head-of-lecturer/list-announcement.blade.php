@extends("layouts.main-head-of-lecturer")

@section("title")
	<h1>Announcement</h1>
@endsection


@section("content")
	@include("roles.admin.announcement.list")
@endsection
