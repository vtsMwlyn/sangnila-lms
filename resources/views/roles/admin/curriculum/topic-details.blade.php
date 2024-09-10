@extends("layouts.main-admin")

@section("title")
	<h1>{{ $course->course_name }}</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <span>{{ $curriculum_topic->title }}</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">
			<a href="{{ route('admin.course.show', $course->id) }}">{{ $course->course_name }}</a>
		</x-page-title>
		<h1 class="text-2xl font-semibold text-blue-900 mb-8 text-center">{{ $curriculum_topic->title }}</h1>

		@if(session()->has("successEditCurriculumTopic"))
			<x-badge-success badge_text="{{ session('successEditCurriculumTopic') }}"></x-badge-success>
		@elseif(session()->has('successAddCurriculumMaterial'))
			<x-badge-success badge_text="{{ session('successAddCurriculumMaterial') }}"></x-badge-success>
		@elseif(session()->has('successEditCurriculumMaterial'))
			<x-badge-success badge_text="{{ session('successEditCurriculumMaterial') }}"></x-badge-success>
		@elseif(session()->has("successDeleteCurriculumMaterial"))
			<x-badge-warning badge_text="{{ session('successDeleteCurriculumMaterial') }}"></x-badge-warning>
		@endif

		<div class="flex gap-2 my-8">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.curriculum.topic.edit', [$course->id, $curriculum_topic->id]) }}"><i class="bi bi-pencil-square"></i> Edit Topic</x-anchor-button>
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.curriculum.topic.delete', [$course->id, $curriculum_topic->id]) }}"><i class="bi bi-trash3"></i> Delete Topic</x-anchor-button>
		</div>

		<h2 class="text-xl font-semibold mb-2 text-white">Material List:</h2>
		<div class="flex mt-4">
			<x-anchor-button class="bg-orange-500" href="{{ route('admin.course.curriculum.material.create', [$course->id, $curriculum_topic->id]) }}"><i class="bi bi-plus-lg"></i> Add New Material</x-anchor-button>
		</div>

		<div class="overflow-x-auto">
			<x-table>
				<x-slot name="head">
					<th class="template-heads rounded-l-xl">Material Title</th>
					<th class="template-heads">Material Description</th>
					<th class="template-heads">Link</th>
					<th class="template-heads rounded-r-xl">Actions</th>
				</x-slot>

				@forelse ($curriculum_topic->curriculum_materials as $material)
					<tr>
						<td class="template-bodies rounded-l-xl w-1/4">{{ $material->title }}</td>
						<td class="template-bodies w-1/3">
							<div class="h-full w-full overflow-y-auto" style="max-height: 5.5rem;">{{ $material->desc }}</div>
						</td>
						<td class="template-bodies w-1/3">
							<a class="font-bold text-blue-200 hover:underline hover:text-blue-400" href="{{ $material->link }}" target="blank">{{ $material->link }}</a>
						</td>
						<td class="template-bodies rounded-r-xl">
							<div class="flex gap-1 w-full justify-center items-stretch">
								<x-anchor-button class="bg-orange-500"
									href="{{ route('admin.course.curriculum.material.edit', [$course->id, $curriculum_topic->id, $material->id]) }}">
									<i class="bi bi-pencil-square"></i>
								</x-anchor-button>
								<x-anchor-button class="bg-orange-500"
									href="{{ route('admin.course.curriculum.material.delete', [$course->id, $curriculum_topic->id, $material->id]) }}">
									<i class="bi bi-trash3"></i>
								</x-anchor-button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="bg-white rounded-xl p-5 text-center font-semibold">- No materials added yet to this topic -</td>
					</tr>
				@endforelse
			</x-table>
		</div>
	</x-section-container>
@endsection
