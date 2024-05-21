@extends("layouts.main-teacher")

@section("content")
	<h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $student->full_name }}'s Progress</h1>
	<h1 class="text-xl font-semibold text-blue-900 mb-4">In Course: {{ $course->course_name }}</h1>

	<div class="overflow-x-auto rounded-md">
		<table class="min-w-full bg-white border-collapse ">
			<thead>
				<tr>
					<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Topic</td>
					<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Material Name</td>
					<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Access</td>
					<td class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Actions</td>
				</tr>
			</thead>
			<tbody>
				@if ($newestprogress->isNotEmpty())
					@foreach ($newestprogress as $progress)
						<tr>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
								<a href="">
									{{ $progress->material->course_topic->title }}
								</a>
							</td>

							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
								<a href="">
									{{ $progress->material->title }}
								</a>
							</td>

							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
								{{ $progress->status }}
							</td>
							<td class="bg-blue-100 border-b border-blue-300 px-4 py-2 ">
								<form method="POST" action="{{ route('teacher.student.update.progress', $progress->id) }}">
									@csrf
									@method('PATCH')
									<div class="inline-flex items-center">
										<input type="checkbox" name="access" id="material_progress_{{ $progress->id }}"
											class="mr-2 form-checkbox h-5 w-5 text-blue-500 border border-gray-300 {{ $progress->status === 'unlocked' ? 'bg-blue-500' : 'bg-gray-300' }}"
											@if ($progress->status === 'unlocked') checked @endif>
										<x-button>Submit</x-button>
									</div>
								</form>
							</td>

						</tr>
					@endforeach
				@else
					<tr class="text-blue-900"><td colspan="3" class="text-center">N/A</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
