@extends("layouts.main-student")

@section("title")
	<h1>{{ $material->title }}</h1>
@endsection

@section("content")
	<iframe src="{{ $preview_link }}" frameborder="1" width="100%" style="min-height: 70vh"></iframe>
@endsection
