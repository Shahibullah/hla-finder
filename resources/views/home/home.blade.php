@php
    $theme = auth()->check()
        ? auth()->user()->theme
        : session('theme', 'light');
@endphp