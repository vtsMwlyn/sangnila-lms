@props(["msg" => "Are you sure you want to cancel?"])

<x-button {{ $attributes->merge(['class' => 'bg-slate-600', 'type' => 'button', 'onclick' => 'if(confirm(' . json_encode($msg) . ')) history.back();']) }} style="background: rgba(156, 163, 175, 1);">
    {{ $slot }}
</x-button>
