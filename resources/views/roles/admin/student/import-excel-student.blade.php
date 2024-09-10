@extends("layouts.main-admin")

@section("title")
	<h1>Import Student Data from Excel</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>Import Excel</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5 mb-8">Import Student Data from Excel</x-page-title>

		@if(session()->has("failImportExcelStudent"))
			<x-badge-danger badge_text="{{ session('failImportExcelStudent') }}" class="mb-8"></x-badge-danger>
		@endif

		<p class="text-blue-950 font-semibold">Please make sure your excel file has <span class="font-extrabold">column position</span> like shown in this image below:</p>
		<img src="{{ asset('img/import_excel_student_guide.png') }}" alt="Excel import guide" class="w-full mt-4">

		<p class="text-blue-950 font-semibold mt-8">Please also note that the date format should be in <span class="font-extrabold">yyyy-mm-dd</span> format. Then the student_level value should be one of these:</p>
		<ul class="text-blue-950 font-semibold list-disc list-inside mt-4">
			<li class="font-extrabold">Elementary School</li>
			<li class="font-extrabold">Junior High School</li>
			<li class="font-extrabold">Senior High School</li>
			<li class="font-extrabold">College</li>
			<li class="font-extrabold">Professional</li>
		</ul>

		<p class="text-blue-950 font-semibold mt-8">Only <span class="font-extrabold">email, full name, and gender</span> column that should be filled with values.</p>

		<form action="{{ route('admin.student.import-excel.store') }}" method="POST" enctype="multipart/form-data" class="mt-8">
			@csrf

			<div>
				<x-label for="file">{{ __('Your Excel File') }}</x-label>
				<x-input type="file" name="file" id="file" class="bg-white w-full block mt-1"/>
			</div>

			<div class="flex gap-5  mt-8">
				<x-button class="bg-orange-500"><i class="bi bi-upload"></i> Import Data</x-button>
				<x-anchor-button href="{{ route('admin.student.import-excel.download') }}" class="bg-orange-500"><i class="bi bi-download"></i> Download Template</x-anchor-button>
			</div>
		</form>
	</x-section-container>
@endsection
