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
		<x-page-title>Edit Reimburse</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

        <form action="{{ route('teacher.lecturer-invoice-reimburse.reimburse.update', $reimburse->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="content" value="reimburse">

            <div class="flex gap-5">
                <div class="mt-3 w-full md:w-1/2">
                    <x-label for="date" class="mb-1">Reimburse Date<span class="text-red">*</span></x-label>
                    <x-input type="date" name="date" id="date" class="w-full date-input" value="{{ Carbon\Carbon::parse(old('date', $reimburse->date))->format('Y-m-d') }}"/>
                </div>
                <div class="mt-3 w-full md:w-1/2">
                    <x-label for="amount" class="mb-1">Amount<span class="text-red">*</span></x-label>
                    <x-input type="number" name="amount" id="amount" class="w-full" value="{{ old('amount', $reimburse->amount) }}" placeholder="Enter the amount of reimburse"/>
                </div>
            </div>

            <div class="flex gap-5">
                <div class="mt-3 w-full md:w-1/2">
                    <x-label for="image" class="mb-1">Photo Evidence<span class="text-red">*</span></x-label>
                    <x-input type="file" name="image" id="image" class="w-full" accept="image/*"/>
                </div>
                <div class="mt-3 w-full md:w-1/2">
                    <x-label for="need" class="mb-1">Need<span class="text-red">*</span></x-label>
                    <x-input type="text" name="need" id="need" class="w-full" placeholder="(ex: Biaya transportasi ke GPS)" value="{{ old('need', $reimburse->need) }}"/>
                </div>
            </div>

            <div class="mt-4" id="img-preview-area">
                <p id="img-preview-label" class="mb-1">Image Preview</p>
                <img id="img-preview" src="{{ Storage::url("app/public/" . $reimburse->evidence_path) }}" class="w-1/2">
            </div>

            <div class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
                <x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
                <x-button class=" w-1/2 md:w-1/6">Save Data</x-button>
            </div>
        </form>
	</x-section-container>

    <script>
        $(document).ready(() => {
            $("#image").on("change", function(){
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFEvent){
                    $("#img-preview").attr("src", oFEvent.target.result);
                }

                $("#img-preview-area").show();
            });
        });
    </script>
@endsection