<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Page Builder') . ' | ' . $page->name }}</title>
    <link rel="icon" href="{{ asset('public/uploads/settings/favicon.png') }}" type="image/png" />
    <link rel="stylesheet" href="{{ asset( config('pagify.assets_path') . '/css/all.min.css') }}">
</head>

<body>
    @yield('builder-content')
    <script>
        var domain = '{{ config('pagify.domain_url') }}';
    </script>
    
    {!! config('pagify.extra_js') !!}
    
    <script src="{{ asset( config('pagify.assets_path') . '/js/all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.pb-addsection-info a i.icon-plus', function(e) {
                e.stopPropagation();
                $('#elements-btn').trigger('click');
            })

            // Icon Picker
            $(document).on('focus', '.icon_picker', function() {
                $(this).iconpicker({
                    animation: true
                });
                $(this).next('.iconpicker-popover').addClass('show');
            });
        })
    </script>
    
    @stack(config('pagify.site_script_var'))

    @stack('builder-js')
    @yield('builder-templates')
</body>

</html>
