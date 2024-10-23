@if (!empty($page->settings['grids']))
    @foreach ($page->settings['grids'] as $grid)
        @php $columns = \Millat\Pagify\Services\StyleService::getColumns($grid['grid']); @endphp
        <!-- Section start -->
        @php
            config()->set('pagify.current_grid_id', $grid['grid_id']);

            $css = \Millat\Pagify\Services\StyleService::getCss();

            if (!empty(\Millat\Pagify\Services\StyleService::getSectionBackground())) {
                $css = 'position:relative;' . $css;
            }

            $x_components = ['header', 'footer', 'sidebar'];
            $container = [];
            $non_container = [];
            
            foreach ($grid['data'] as $key => $components) {
                foreach ($components as $component) {
                    if (in_array($component['section_id'], $x_components)) {
                        $non_container = $grid['data'];
                    } else {
                        $container = $grid['data'];
                    }
                }
            }

        @endphp
        <section class="{{ getClasses() }}" {!! \Millat\Pagify\Services\StyleService::getCustomAttributes() !!} {!! !empty($css) ? 'style="' . $css . '"' : '' !!}>
            {!! \Millat\Pagify\Services\StyleService::getSectionBackground() !!}
            @if (!empty($container))
                <div {!! \Millat\Pagify\Services\StyleService::getContainerStyles() !!}>
                    <div class="row g-0">
                        @foreach ($container as $column => $components)
                            <div class="{{ $columns[$column] }}">
                                @foreach ($components as $component)
                                    @php 
                                        config()->set('pagify.current_section_id', $component['id']);
                                        $view = \Millat\Pagify\Services\BlockService::get($component['section_id'])['view'] ?? '';
                                        @endphp

                                    @if (view()->exists($view))
                                        {!! view($view)->render() !!}
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($non_container))
                @foreach ($non_container as $column => $components)
                    <div class="{{ $columns[$column] }}">
                        @foreach ($components as $component)
                            @php 
                                config()->set('pagify.current_section_id', $component['id']);
                                $view = \Millat\Pagify\Facades\PageSettings::getBlocks($component['section_id'])['view'];
                            @endphp

                            @if (view()->exists($view))
                                {!! view($view)->render() !!}
                            @endif
                        @endforeach
                    </div>
                @endforeach
            @endif
        </section>
    @endforeach
@endif
