@props(['disabled' => false])

@php
    $classList = 'rounded-xl shadow-sm border-2 font-semibold text-blue-800 py-3 px-5';
    if($errors->has($attributes->get('name'))) {
        $classList .= ' border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50';
    } else {
		$classList .= " border-blue-900 focus:border-blue-700 focus:ring focus:ring-blue-700 focus:ring-opacity-50";
	}
@endphp

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classList]) !!}>
	{{ $slot }}
</select>

@error($attributes->get('name'))
    <p class="text-red-500 mt-2">{{ $message }}</p>
@enderror
