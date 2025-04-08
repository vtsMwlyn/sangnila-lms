@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Students</h1>
@endsection

@section("content")
    <x-section-container>
        <x-page-title>My Invoices</x-page-title>
        <div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

        @if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

        <div class="mt-4">
            <x-anchor-button href="{{ route('teacher.lecturer-invoice.create') }}"><i class="bi bi-plus-lg"></i> New Invoice</x-anchor-button>
        </div>

        <div class="w-full overflow-auto hidden xl:block mt-3" style="height: 60vh;">
            <table class="w-full">
                <thead>
                    <th class="text-start py-3 px-4 border-b-2 border-slate-400">Invoice Number</th>
                    <th class="text-start py-3 px-4 border-b-2 border-slate-400">Invoice Date</th>
                    <th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
                </thead>
                <tbody>
                    @forelse(Auth::user()->lecturer_invoices as $invoice)
                        <tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
                            <td class="py-3 px-4">Invoice {{ $invoice->number }}</td>
                            <td class="py-3 px-4">{{ Carbon\Carbon::parse($invoice->date)->format('D, d M Y') }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('teacher.lecturer-invoice.download', $invoice->id) }}">
                                    <img src="{{ asset('img/download.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white">
                            <td class="py-3 px-4 text-center" colspan="3">- No Invoices Data Yet In This Course -</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-section-container>

@endsection