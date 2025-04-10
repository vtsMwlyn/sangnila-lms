@props(["msg" => "Are you sure you want to cancel?", "showCancelMsg" => "true"])

<x-button type="button"
	{{ $attributes->merge(['class' => 'bg-slate-600 hover:cursor-pointer cancel-btn']) }}
	style="background: rgba(156, 163, 175, 1);">
	{{ $slot }}
</x-button>

