@props(['disabled' => false])

@php
    $classList = 'rounded-xl shadow-sm border-2 font-semibold text-blue-800 py-3 px-5';
    if($errors->has($attributes->get('name'))) {
        $classList .= ' border-red-700 focus:border-red-700 focus:ring focus:ring-red-500 focus:ring-opacity-50';
    } else {
		$classList .= " border-blue-900 focus:border-blue-700 focus:ring focus:ring-blue-700 focus:ring-opacity-50";
	}
@endphp

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classList]) !!}>
	{{ $slot }}
</select>

@if($errors->has($attributes->get('name')))
    <p class="text-red-800 font-bold mt-2"><i class="bi bi-exclamation-circle"></i> {{ $errors->first($attributes->get('name')) }}</p>
@endif
