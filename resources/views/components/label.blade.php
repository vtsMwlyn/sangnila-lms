@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-md text-blue-950 font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
