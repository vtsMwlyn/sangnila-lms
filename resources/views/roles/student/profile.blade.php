@extends("layouts.main-student")

@section("title")
	<h1>Profile</h1>
@endsection

@section("content")
	@include("auth.profile")

	<form action="#" class="mt-10 w-full flex justify-center">
		<x-button class="bg-yellow-500 transition hover:scale-110"><i class="bi bi-cash-coin"></i> Ceritanya bayar buat 8 pertemuan selanjutnya</x-button>
	</form>
@endsection
