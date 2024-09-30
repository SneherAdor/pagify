<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (!empty($page->title))
        <title>{{ $page->title }} </title>
    @endif

    @if (!empty($page->description))
        <meta name="description" content="{{ $page->description }}" />
    @endif

    <link rel="stylesheet" href="{{ asset(config('pagify.assets_path') . '/pagify/css/bootstrap.min.css') }}">

    @stack(config('pagify.style_var'))

</head>

<body>

    <main class="mainbag">
        @yield(config('pagify.site_section'))
    </main>

    <script src="{{ asset(config('pagify.assets_path') . '/pagify/js/jquery.min.js') }}"></script>

    <script defer src="{{ asset(config('pagify.assets_path') . '/pagify/js/bootstrap.min.js') }}"></script>

    @stack(config('pagify.script_var'))
</body>

</html>
