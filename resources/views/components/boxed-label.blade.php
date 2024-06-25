@props(['value'])

<label {{ $attributes->merge(['class' => 'w-1/4 block font-bold text-blue-900 bg-white py-3 px-5 rounded-xl border-2 border-blue-900']) }}>
	{{ $value ?? $slot }}
</label>

