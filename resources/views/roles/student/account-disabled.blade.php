@extends("layouts.main-student")

@section("title")
	<h1>Account Disabled</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Notice</h1>
	<div class="bg-white rounded-2xl p-5 border-blue-300 border-2">
		<!-- Confirmation Text -->
		<div class="mb-4">
			<h1 class="text-xl font-semibold text-blue-900">
				Sorry, your account is disabled and cannot be used to access Sangnila LMS.
			</h1>
			<h3 class="italic text-gray-500 mt-4">Please contact admin to enable your account.</h3>
		</div>
	</div>
@endsection
