@extends("layouts.main-guest")

@section("title")
	<h1>Our Courses</h1>
@endsection

@section("content")
	<div class="flex flex-wrap gap-5 w-full">
		@forelse ($courses as $course)
			<a href="{{ route('guest.show', ['course_id' => $course->id]) }}" style="width: 32%;" class="course-card transition duration-300 hover:scale-105" data-course-name="{{ $course->course_name }}">
				<div class="rounded-3xl shadow-lg overflow-hidden relative">
					<div class="absolute h-full w-full course-bg" style="background-size: cover; background-position: center; filter: brightness(0.5) blur(2px);"></div>
					<div class="relative p-5 w-full h-full">
						<!-- Course information -->
						<p class="font-bold text-white">{{ $course->course_name }}</p>
						<div class="w-full bg-white mt-2 mb-3" style="height: 2px;"></div>
						<div class="overflow-y-auto text-white" style="height: 100px;">{{ $course->course_description }}</div>
						<div class="w-full flex flex-col text-white mt-4">
							<div class="flex gap-2">
								<i class="bi bi-person-fill"></i>
								<span>{{ App\Models\CourseStudent::where("course_id", $course->id)->count() }} Students Enrolled</span>
							</div>
							<div class="flex gap-2">
								<i class="bi bi-person-fill"></i>
								<span>{{ App\Models\CourseTeacher::where("course_id", $course->id)->count() }} Teachers Teaching</span>
							</div>
						</div>
					</div>
				</div>
			</a>
		@empty
			<div class="text-blue-900">N/A</div>
		@endforelse
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const accessKey = 'W_A5i7O9MjRE54Q4l9KA4onU-zZjNbNYowSd8UccBLY';

			// Loop through each card and fetch background image based on course name
			document.querySelectorAll('.course-card').forEach((card) => {
				const courseName = card.getAttribute('data-course-name');

				// Fetch image from Unsplash API for each course
				fetch(`https://api.unsplash.com/search/photos?query=${courseName}&client_id=${accessKey}`)
					.then(response => response.json())
					.then(data => {
						if (data.results && data.results.length > 0) {
							const imageUrl = data.results[0].urls.regular;
							// Set background image
							card.querySelector('.course-bg').style.backgroundImage = `url('${imageUrl}')`;
						} else {
							console.log('No images found for:', courseName);
						}
					})
					.catch(error => console.error('Error fetching image:', error));
			});
		});
	</script>

@endsection
