@props(["msg" => "Are you sure you want to cancel?"])

<x-button {{ $attributes->merge(['class' => 'bg-slate-600', 'type' => 'button', 'onclick' => 'if(confirm(' . json_encode($msg) . ')) history.back();']) }}>
    {{ $slot }}
</x-button>
