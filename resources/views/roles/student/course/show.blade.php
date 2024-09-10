@extends("layouts.main-student")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course->course_name }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">{{ $course->course_name }}</x-page-title>
		<p class="text-blue-950 font-semibold text-center mb-8">{{ $course->course_description }}</p>

		<h2 class="text-xl text-white font-bold">Course Materials:</h2>

		<div class="mb-6 overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Course Topic</th>
					<th class="template-heads">Material Name</th>
					<th class="template-heads">Status</th>
					<th class="template-heads rounded-r-xl">Actions</th>
				</x-slot>

				@forelse ($materialProgresses as $progress)
					<tr>
						<td class="template-bodies rounded-l-xl" style="@if ($progress->status === 'locked') color: rgb(156 163 175); @endif">
							{{ $progress->material->topic->title }}
						</td>
						<td class="template-bodies">
							@if ($progress->status === 'unlocked')
								<a {{-- href="{{ $progress->material->link }}" --}}
									href="{{ route("student.mycourse.preview", $progress->material->id) }}" class="text-white hover:text-green-900 hover:underline font-bold">
									{{ $progress->material->title }}
								</a>
							@else
								<span class="text-gray-400 font-bold">
									{{ $progress->material->title }}
								</span>
							@endif
						</td>
						<td class="template-bodies">
							<span class="@if ($progress->status === 'unlocked') font-bold text-green-700 @else text-gray-400 @endif">
								{{ $progress->status }}
							</span>
						</td>
						<td class="template-bodies rounded-r-xl">
							@if ($progress->status === 'unlocked')
								<x-anchor-button class="bg-orange-500" href="{{ route('student.mycourse.preview', $progress->material->id) }}">View</x-anchor-button>
							@else
								<x-button class="bg-slate-800" type="button">View</x-button>
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="p-5 text-center rounded-xl bg-white font-semibold">- The teacher haven't uploaded any topics and materials yet -</td>
					</tr>
				@endforelse
			</x-table>
		</div>
	</x-section-container>
@endsection
