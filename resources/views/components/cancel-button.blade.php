@props(["msg" => "Are you sure you want to cancel?", "showCancelMsg" => "true", "redirect_to" => null])

@if($redirect_to == null)
	<x-button type="button"
		{{ $attributes->merge(['class' => 'bg-slate-600 hover:cursor-pointer cancel-btn', 'style' => 'background: rgba(156, 163, 175, 1);']) }}>
		{{ $slot }}
	</x-button>
@else
	<x-anchor-button href="{{ $redirect_to }}" {{ $attributes->merge(['class' => 'bg-slate-600 hover:cursor-pointer', 'style' => 'background: rgba(156, 163, 175, 1);']) }}>
		{{ $slot }}
	</x-anchor-button>
@endif

