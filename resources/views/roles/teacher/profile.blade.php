@extends("layouts.main-teacher")

@section("title")
	<h1>Profile</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Profile</span>
@endsection

@section("content")
	@include("auth.profile")
@endsection
