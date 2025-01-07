@extends("layouts.main-admin")

@section("title")
	<h1>Normalize</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Normalize Student Status</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form method="POST" action="{{ route('admin.student.normalize.proceed', $student->id) }}" class="bg-blue-800 rounded-2xl py-5 px-10 mt-8" id="foomu">
			@csrf
			<!-- Confirmation Text -->
			<div class="mb-6">
				<h1 class="text-xl font-semibold text-white">
					<p class="font-semibold">You're about to <span class="text-yellow-500 font-bold">normalize this student from "imported student" status</span>. Please choose which courses where the attendance data has been entered completely so that students in that course have their status as "imported students" removed. <span class="text-red-500 font-bold">Be mindful that this action is cannot be undone</span>.</p>
				</h1>
			</div>

			<div class="@error("checkbox_values") border border-red-500 px-3 rounded-xl @enderror">
				@foreach ($imported as $imp)
					<div class="flex items-center gap-3 bg-white w-full px-5 py-3 my-5 rounded-xl">
						<input type="checkbox" id="checkbox{{ $loop->iteration }}" class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300">
						<label for="checkbox{{ $loop->iteration }}">{{ $imp->course->course_name }}</label>
					</div>
				@endforeach
			</div>
			@error("checkbox_values")
				<p class="text-red-500">Please select at least one course.</p>
			@enderror

			<!-- Yes/No Buttons -->
			<div class="flex gap-3 justify-center w-full mt-10">
				<x-button type="submit" class=" w-full md:w-1/6">Confirm</x-button>
				<x-button type="button" onclick="history.back()" class=" w-full md:w-1/6">No</x-button>
			</div>
		</form>

		<script>
			let isSubmitting = false;

			$("#foomu").on("submit", function(e) {
				if (isSubmitting) {
					return true;
				}

				e.preventDefault();

				$('input[type="checkbox"]').each(function(index) {
					let status = $(this).is(":checked") ? "on" : "off";

					$("#foomu").append($("<input>").attr({
						"type": "hidden",
						"name": "checkbox_values[" + index + "]",
						"value": status
					}));
				});

				isSubmitting = true;
				$(this).submit();
			});
		</script>
	</x-section-container>
@endsection
