@extends(config('pagify.site_layout'), ['page' => $page, 'edit' => false])

@section(config('pagify.site_section'))
    @php
        config()->set('pagify.page_id', $page->id);
    @endphp
    {{ view('pagify::components.page-components', ['page' => $page]) }}
@endsection



