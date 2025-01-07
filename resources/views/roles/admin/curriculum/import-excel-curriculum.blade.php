@extends("layouts.main-admin")

@section("title")
	<h1>Import Curriculum Data from Excel</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <a href="{{ route('admin.course.show', $course->id) }}#curriculum-section" class="font-bold text-yellow-500">Curriculum</a>
	> <span>Import Excel</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>Import Curriculum Data from Excel</x-page-title>

		@if(session()->has("failImportExcelCurriculum"))
			<x-badge-danger badge_text="{{ session('failImportExcelCurriculum') }}" class="mb-8"></x-badge-danger>
		@endif

		<p class="text-blue-950 font-semibold">Please make sure your excel file has <span class="font-extrabold">column position</span> like shown in this image below:</p>
		<img src="{{ asset('img/import_excel_curriculum_guide.png') }}" alt="Excel import guide" class="w-full mt-4">

		<p class="text-blue-950 font-semibold mt-8">Make sure <span class="font-extrabold">all columns</span> should be filled with values.</p>

		<form action="{{ route('admin.course.curriculum.import-excel.store', $course->id) }}" method="POST" enctype="multipart/form-data" class="mt-8">
			@csrf

			<div>
				<x-label for="file">{{ __('Your Excel File') }}</x-label>
				<x-input type="file" name="file" id="file" class="bg-white w-full block mt-1"/>
			</div>

			<div class="flex gap-5  mt-8">
				<x-button ><i class="bi bi-upload"></i> Import Data</x-button>
				<x-anchor-button href="{{ route('admin.course.curriculum.import-excel.download', $course->id) }}" ><i class="bi bi-download"></i> Download Template</x-anchor-button>
			</div>
		</form>
	</x-section-container>
@endsection
