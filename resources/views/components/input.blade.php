{{-- @props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}> --}}

@props(['disabled' => false])

@php
    $classList = 'rounded-2xl shadow-sm focus:outline-none py-2 px-4';
    if($errors->has($attributes->get('name'))) {
        $classList .= ' border-red focus:border-red-700 focus:ring-0';
    } else {
		$classList .= ' border-slate-400 focus:border-slate-600 focus:ring-0';
	}
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classList, 'style' => 'border-width: 3px;']) !!}>

@error($attributes->get('name'))
    <p class="text-red font-bold mt-2 error-messages"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
@enderror

{{-- focus:ring focus:ring-red-500 focus:ring-opacity-50 focus:ring focus:ring-slate-500 focus:ring-opacity-50 --}}
