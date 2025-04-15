@extends("layouts.main-teacher")

@section("title")
    <h1>Invoice & Reimburse</h1>
@endsection

{{-- @section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Batch Assign</span>
@endsection --}}

@section("content")
	<x-section-container>
		<x-page-title>New Lecturer Invoice</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

        <form action="{{ route('teacher.lecturer-invoice-reimburse.invoice.store') }}" method="post" class="my-4">
            @csrf

            <div id="form-area">
                {{-- Invoice data --}}
                <h1 class="font-bold text-lg text-blue">Invoice Data</h1>
                <div class="flex gap-5">
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Invoice Date<span class="text-red">*</span></x-label>
                        <x-input type="date" name="date" id="date" class="w-full date-input" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"/>
                    </div>
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Lecturer Name<span class="text-red">*</span></x-label>
                        <x-input type="text" name="_teacher_name" id="_teacher_name" class="w-full" disabled value="{{ Auth::user()->full_name }}"/>
                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                    </div>
                </div>

                <div class="flex gap-5">
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Invoice Number<span class="text-red">*</span></x-label>
                        <x-input type="text" name="number" id="number" class="w-full" placeholder="(ex: 001, 002, etc)"/>
                    </div>
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Invoice Payment Info<span class="text-red">*</span></x-label>
                        <x-input type="text" name="bank_data" id="bank_data" class="w-full" placeholder="(ex: BCA 123456789 a/n Someone)"/>
                    </div>
                </div>

                {{-- Invoice Items --}}
                <h1 class="font-bold text-lg text-blue mt-8">Invoice Items</h1>
                <p class="italic">- The system will generate invoice and its content based on your submitted attendance data -</p>
            </div>

            <div class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
                <x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
                <x-button class=" w-1/2 md:w-1/6">Submit Data</x-button>
            </div>
        </form>
	</x-section-container>
@endsection