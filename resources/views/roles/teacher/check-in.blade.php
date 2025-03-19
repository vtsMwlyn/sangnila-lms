@extends("layouts.main-teacher")

@section("title")
	<h1>Lecturer Attendance</h1>
@endsection

@section("content")
	<x-section-container>
		<x-back-button href="{{ route('teacher.attendance.show', $course->id) }}"></x-back-button>
		<x-page-title>{{ $course->course_name }}</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">Lecturer Check In</h1>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.attendance.check-in.store', $course->id) }}" method="post" enctype="multipart/form-data">
			@csrf

			{{-- Check In Time --}}
			<div class="w-full flex flex-col mt-4">
				<x-label for="_check_in_time">Check In Time<span class="text-red">*</span></x-label>
				<x-input id="_check_in_time" name="_check_in_time" class="w-full cursor-not-allowed" type="text" disabled/>
				<x-input id="check_in_time" name="check_in_time" type="hidden"/>
			</div>

			{{-- Evidence --}}
			<div class="w-full flex flex-col mt-4 items-start">
				<div class="w-full flex flex-col md:flex-row">
					<div class="flex flex-col w-full md:w-1/2 pr-0 md:pr-2">
						<x-label>Photo Evidence<span class="text-red">*</span></x-label>
						<video id="video" class="w-full" autoplay playsinline></video>
					</div>
					<div class="flex flex-col w-full md:w-1/2 pl-0 md:pl-2" id="preview-area" style="display: none;">
						<x-label :value="__('Preview')"/>
						<canvas id="canvas" class="w-full"></canvas>
					</div>
				</div>
				<x-button type="button" class="mt-3" id="capture"><i class="bi bi-camera"></i> Capture Photo</x-button>

				<input type="file" name="image" id="image" style="display: none;">
			</div>

			{{-- Description --}}
			<div class="w-full flex flex-col mt-4">
				<x-label for="description">Description<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="description" name="description" class="w-full" placeholder="Enter description">{{ old('description') }}</x-textarea>
			</div>

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-40 xl:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
		$(document).ready(() => {
			let serverTime = @json(\Carbon\Carbon::now()->toDateTimeString());
			let currentTime = new Date(serverTime);

			function updateTime() {
				currentTime.setSeconds(currentTime.getSeconds() + 1);

				let hours = currentTime.getHours();
				let minutes = currentTime.getMinutes();
				let seconds = currentTime.getSeconds();

				hours = (hours < 10) ? '0' + hours : hours;
				minutes = (minutes < 10) ? '0' + minutes : minutes;
				seconds = (seconds < 10) ? '0' + seconds : seconds;

				$('#_check_in_time').val(hours + ':' + minutes + ':' + seconds);
				$('#check_in_time').val(hours + ':' + minutes + ':' + seconds);
			}

			updateTime();
            setInterval(updateTime, 1000);

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

				$('#preview-area').show();

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
