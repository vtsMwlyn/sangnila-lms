@extends("layouts.main-guest")

@section("title")
	<h1>Lecturer Biography</h1>
@endsection

@section("content")
	<x-section-container>
		<button type="button" onclick="history.back();"><img src="{{ asset('img/back-button.svg') }}" class="h-8 w-8 hover:scale-110" alt="back"></button>
		<x-page-title>{{ $teacher->details->gender == 1 ? 'Mr.' : 'Ms.' }} {{ $teacher->full_name }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

        <div class="flex w-full gap-4">
            <div class="mt-4 w-1/5 px-2">
                @if($teacher->details->profpic)
                    <img src="{{ Storage::url("app/public/" . $teacher->details->profpic) }}" class="w-full h-[250px] mt-2 mb-4 border-4 border-slate-400" alt="profpic" style="object-fit: cover; object-position: center;" loading="lazy" oncontextmenu="return false;">
                @else
                    <img src="{{ asset('img/tempblankprofpic.png') }}" class="w-full h-[250px] mt-2 mb-4 border-4 border-slate-400" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;" loading="lazy">
                @endif
            </div>

            <div class="mt-6 mb-4 w-4/5 px-2" style="text-align: justify;">
                @if($teacher->details->biography)
                    {!! nl2br($teacher->details->biography) !!}
                @else
                    Currently the admin has not uploaded the biography yet.
                @endif
            </div>
        </div>
	</x-section-container>
@endsection
