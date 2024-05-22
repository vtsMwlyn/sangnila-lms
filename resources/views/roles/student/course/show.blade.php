@extends("layouts.main-student")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
	<p class="text-gray-700 mb-8">{{ $course->course_description }}</p>

	<h2 class="text-xl font-semibold mb-2">Course Materials:</h2>
	<div class="h-fit mb-5">

	</div>
	<div class="mb-6 overflow-x-auto">
		<table class="min-w-full table-fixed border-collapse">
			<thead>
				<tr class="border-b border-solid border-blue-900">
					<th class="px-4 py-2 border border-solid border-blue-900 bg-blue-200">Course Topic</th>
					<th class="px-4 py-2 border border-solid border-blue-900 bg-blue-200">Material Name</th>
					<th class="px-4 py-2 border border-solid border-blue-900 bg-blue-200">Status</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($materialProgresses as $progress)
					<tr class="hover:bg-gray-100 border-b border-solid border-blue-900">
						<td class="px-4 py-2 border border-solid border-blue-900 {{ $progress->status === 'unlocked' ? 'text-white bg-green-400' : 'text-gray-500' }}">
							{{ $progress->material->course_topic->title }}
						</td>
						<td class="px-4 py-2 border border-solid border-blue-900 {{ $progress->status === 'unlocked' ? 'text-white bg-green-400' : 'text-gray-500' }}">
							@if ($progress->status === 'unlocked')
								<a href="{{ $progress->material->link }}" class="text-white hover:underline font-bold">
									{{ $progress->material->title }}
								</a>
							@else
								<span class="text-gray-500">
									{{ $progress->material->title }}
								</span>
							@endif
						</td>
						<td class="px-4 py-2 border border-solid border-blue-900 {{ $progress->status === 'unlocked' ? 'text-white bg-green-400' : 'text-gray-500' }}">
							<span class="">
								{{ ucfirst($progress->status) }}
							</span>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="2" class="px-4 py-2 text-center border-t border-solid border-blue-900">No materials found.</td>
					</tr>
				@endforelse
			</tbody>
		</table>

	</div>
@endsection
