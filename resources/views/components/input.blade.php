{{-- @props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}> --}}

@props(['disabled' => false])

@php
    $classList = 'rounded-xl py-3 px-5 shadow-sm border-2 text-blue-800 font-semibold';
    if($errors->has($attributes->get('name'))) {
        $classList .= ' border-red-500 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50';
    } else {
		$classList .= " border-blue-800 focus:border-blue-900 focus:ring focus:ring-blue-700 focus:ring-opacity-50";
	}
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classList]) !!}>

@error($attributes->get('name'))
    <p class="text-red-500 mt-2">{{ $message }}</p>
@enderror
