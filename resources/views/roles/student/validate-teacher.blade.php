@extends("layouts.main-teacher")

@section("title")
	<h1>Lecturer Attendance</h1>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.attendance.show', $course->id) }}"></x-back-button>
		<h1 class="text-dark-blue text-3xl font-extrabold mt-3">{{ $course->course_name }}</h1>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Validate Teacher's Attendance</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("failedValidating"))
			<x-badge-danger badge_text="{{ session('failedValidating') }}"></x-badge-danger>
		@endif

		<form action="{{ route('student.mycourse.validate-teacher.store', $course->id) }}" method="post" enctype="multipart/form-data">
			@csrf

			<input type="hidden" name="teacher" value="{{ $teacher->id }}">

			<div class="w-full flex flex-col mt-2 items-start">
				<div class="w-full flex">
					<div class="flex flex-col w-1/2 pr-2">
						{{-- <div style="aspect-ratio: 19 / 6;">
							<video id="video" class="w-full h-full" style="object-fit: cover;" autoplay playsinline></video>
						</div> --}}
						<x-label :value="__('Photo Evidence')" />
						<video id="video" class="w-full" autoplay playsinline></video>
					</div>
					<div class="flex flex-col w-1/2 pl-2">
						<x-label :value="__('Preview')" id="preview-label" style="display: none;" />
						<canvas id="canvas" class="w-full"></canvas>
					</div>
				</div>
				<x-button type="button" class="mt-3" id="capture"><i class="bi bi-camera"></i> Capture Photo</x-button>

				<input type="file" name="image" id="image" style="display: none;">
			</div>

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$(document).ready(() => {
			// Get references to the video and canvas elements
			const video = document.getElementById('video');
			const canvas = document.getElementById('canvas');
			const captureButton = document.getElementById('capture');
			const hiddenFileInput = document.getElementById('image');

			// Access the user's camera
			navigator.mediaDevices.getUserMedia({ video: true })
				.then(stream => {
					video.srcObject = stream;
				})
				.catch(error => {
					console.error('Error accessing the camera:', error);
				});

			// Capture the photo when the button is clicked
			captureButton.addEventListener('click', () => {
				const context = canvas.getContext('2d');
				// Set canvas dimensions to match the video
				canvas.width = video.videoWidth;
				canvas.height = video.videoHeight;
				// Draw the current video frame onto the canvas
				context.drawImage(video, 0, 0, canvas.width, canvas.height);

				$('#preview-label').show();

				// Convert the canvas content to a Blob
				canvas.toBlob(blob => {
					// Create a File object from the Blob
					const file = new File([blob], 'photo.png', { type: 'image/png' });

					// Create a DataTransfer object to set the file on the hidden input
					const dataTransfer = new DataTransfer();
					dataTransfer.items.add(file);

					hiddenFileInput.files = dataTransfer.files;
					hiddenFileInput.dispatchEvent(new Event('change'));
				}, 'image/png');
			});
		});

	</script>
@endsection
