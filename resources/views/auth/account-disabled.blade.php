<x-section-container>
	<x-page-title>Notice</x-page-title>
	<div class="bg-blue-900 rounded-2xl p-5">
		{{-- Confirmation Text --}}
		<div class="mb-4 p-5 text-white">
			<h1 class="text-xl font-semibold">
				Sorry, your account is disabled!
			</h1>
			<p class="mt-5 py-3 px-6 border rounded-lg bg-red-800"><span class="font-bold"><i class="bi bi-exclamation-circle"></i> Reason: </span>{{ Auth::user()->disable_reason }}</p>
			<h3 class="italic  mt-8">Your account is currently disabled by our admin and is unable to access Sangnila LMS. Please contact our admin to discuss the problem and re-enable your account.</h3>
		</div>
	</div>
</x-section-container>
