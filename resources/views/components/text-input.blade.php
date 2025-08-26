@props(['disabled' => false, 'name'])

@php
    $hasError = $name && $errors->has($name);
@endphp

<input
    name="{{ $name }}"
    @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'rounded-md shadow-xs ' .
            ($hasError
                ? 'bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500'
                : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500')
    ]) }}
>