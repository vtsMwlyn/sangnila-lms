@extends("layouts.main-teacher")

@section("title")
	<h1>Announcement</h1>
@endsection


@section("content")
	@include("roles.admin.announcement.show")
@endsection
