@props(['value'])

<label {{ $attributes->merge(['class' => 'w-1/4 block font-bold text-blue bg-white py-2 px-4 rounded-xl border-2 border-blue flex items-center']) }}>
	{{ $value ?? $slot }}
</label>

