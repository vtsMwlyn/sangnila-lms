@props(['disabled' => false])

@php
    $classList = 'rounded-md shadow-sm border text-blue-800 py-2 px-3';
    if($errors->has($attributes->get('name'))) {
        $classList .= ' border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50';
    } else {
		$classList .= " border-blue-800 focus:border-indigo-400 focus:ring focus:ring-indigo-400 focus:ring-opacity-50";
	}
@endphp

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classList]) !!}>
	{{ $slot }}
</select>

@error($attributes->get('name'))
    <p class="text-red-500 mt-2">{{ $message }}</p>
@enderror
