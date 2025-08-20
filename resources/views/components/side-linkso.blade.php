@props(['href', 'current' => false, 'ariaCurrent' => false])

@php
    // $classes = $current ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-100 hover:text-gray-900';
    if($current) {
        $classes = 'bg-red-100 text-red-900 border-b border-l border-red-300';
        $ariaCurrent = 'page';
    } else {
        $classes = 'hover:bg-red-100 hover:text-red-700 transition duration-100 ease-in';
    }
@endphp

<li>
        <a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center p-2 text-red-600 rounded-lg ' . $classes, 'aria-current' => $ariaCurrent]) }}>
        {{ $slot }}
        </a>
</li>