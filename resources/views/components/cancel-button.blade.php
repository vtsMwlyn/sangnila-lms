@props(["msg" => "Are you sure you want to cancel?", "showCancelMsg" => "true"])

@if($showCancelMsg == "true")
	<x-anchor-button
		{{ $attributes->merge(['class' => 'bg-slate-600 hover:cursor-pointer']) }}
		onclick="if (confirm({{ json_encode($msg) }})) { if (this.hasAttribute('href')) { return true; } else { history.back(); return false; } } else { return false; }"
		style="background: rgba(156, 163, 175, 1);">
		{{ $slot }}
	</x-anchor-button>
@else
	<x-anchor-button
		{{ $attributes->merge(['class' => 'bg-slate-600 hover:cursor-pointer']) }}
		style="background: rgba(156, 163, 175, 1);">
		{{ $slot }}
	</x-anchor-button>
@endif
