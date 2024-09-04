@extends("layouts.main-guest")

@section("title")
	<h1>Our Courses</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Our Courses</x-page-title>
		@if ($courses->isNotEmpty())
			{{-- <div class="overflow-x-auto rounded-md">
				<table class="w-full table-auto">
					<thead>
						<tr>
							<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Course Name</th>
							<th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Description</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($courses as $course)
							<tr>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
									<a href="{{ route('guest.show', ['course_id' => $course->id]) }}"
										class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
										{{ $course->course_name }}
									</a>
								</td>
								<td class="bg-blue-100 border-b border-blue-300 px-4 py-2">
									{{ $course->course_description }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div> --}}
			<div class="w-full flex flex-wrap justify-center my-10 gap-16">
				@foreach ($courses as $course)
					<!-- Course card  -->
					<a href="{{ route('guest.show', ['course_id' => $course->id]) }}" class="w-full md:w-1/3">
						<div class="flex flex-col justify-center items-center gap-5 border-2 border-white rounded-xl text-white px-8  hover:scale-105 transition duration-300 ease-in-out" style="background: linear-gradient(to bottom, rgba(40, 55, 133, 0.53) 25%, rgba(235, 126, 37, 0.58)); min-height: 400px; cursor: url('{{ asset('img/cursor2.cur') }}'), pointer;">
							<h1 class="text-3xl font-bold">{{ $course->course_name }}</h1>
							<div class="overflow-y-auto" style="max-height: 200px;">
								<p class="text-xl mt-5 font-semibolf">{{ $course->course_description }}</p>
							</div>
						</div>
					</a>
				@endforeach
			</div>
		@else
			<div class="text-blue-900">N/A</div>
		@endif
	</x-section-container>
@endsection
