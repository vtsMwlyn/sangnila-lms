@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-md text-blue-800 font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
