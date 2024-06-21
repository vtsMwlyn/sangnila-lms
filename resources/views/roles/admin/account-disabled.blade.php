@extends("layouts.main-admin")

@section("title")
	<h1>Account Disabled</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">Notice</h1>
	<div class="bg-white rounded-2xl p-5 border-blue-300 border-2">
		<!-- Confirmation Text -->
		<div class="mb-4">
			<h1 class="text-xl font-semibold text-blue-900">
				Sorry, your account is disabled!
			</h1>
			<p class="mt-5 text-white py-3 px-6 border rounded-lg bg-red-800"><span class="font-bold"><i class="bi bi-exclamation-circle"></i> Reason: </span>{{ Auth::user()->disable_reason }}</p>
			<h3 class="italic text-gray-500 mt-8">Your account is currently disabled by our admin and is unable to access Sangnila LMS. Please contact our admin to discuss the problem and re-enable your account.</h3>
		</div>
	</div>
@endsection
