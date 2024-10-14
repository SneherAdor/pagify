<!-- asidebar -->
<aside class="pb-aside pagify-pbsettings">
    <div class="pb-tabs-wrapper">
        <nav>
            <div class="nav nav-tabs pb-tabs-btn">
                <button class="pb-tab-btn active" id="elements-btn" data-bs-toggle="tab" type="button"
                    data-bs-target="#blocks-tab" aria-selected="true"><i
                        class="icon-grid"></i>{{ __('pagify::pagify.elements') }}</button>
                <button id="settings-btn" class="pb-tab-btn" data-bs-toggle="tab" type="button"
                    data-bs-target="#section-settings-tab" aria-selected="false"><i
                        class="icon-settings"></i>{{ __('pagify::pagify.settings') }}</button>
                <button class="pb-tab-btn" id="advanced-btn" data-bs-toggle="tab" type="button"
                    data-bs-target="#advanced-settings-tab" aria-selected="false"><i
                        class="icon-sliders"></i>{{ __('pagify::pagify.advanced') }}</button>
            </div>
        </nav>
        <div class="tab-content pb-tabs-holder">
            <div class="pb-blocks-tab tab-pane fade show active" id="blocks-tab">
                <div id="pb-advanceaccordion" class="pb-collapse-wrapper">
                    @foreach ($componentTabs as $tab => $elements)
                        <div class="accordion pb-advanceaccordion" id="pb-advanceaccordion">
                            <div class="op-accordion-item">
                                <div class="pb-advancedtitle" data-bs-toggle="collapse"
                                    data-bs-target="#{{ \Illuminate\Support\Str::slug($tab) }}-accord"
                                    aria-expanded="false">
                                    <h2>{{ $tab }}</h2>
                                </div>
                                <div id="{{ \Illuminate\Support\Str::slug($tab) }}-accord"
                                    class="accordion-collapse collapse" data-bs-parent="#pb-advanceaccordion">
                                    <ul class="pb-elementcontent-wrapper"
                                        id="draggable-{{ \Illuminate\Support\Str::slug($tab) }}">
                                        @if (!empty($elements))
                                            @foreach ($elements as $element)
                                                @php
                                                    $item = $components[$element];
                                                @endphp
                                                <li class="draggable pb-placeholder-dragonly"
                                                    data-section="{{ $item['settings']['id'] }}"
                                                    id="{{ $item['settings']['id'] }}">
                                                    <a href="javascript:void(0)" class="pb-elementcontent">
                                                        {!! $item['settings']['image'] ?: $item['settings']['icon'] ?? '<i class="icon-slash"></i>' !!}
                                                        <span>{{ $item['settings']['name'] ?? __('pagify::pagify.no_name') }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="pb-sec-settings-tab tab-pane fade " id="section-settings-tab">
                <div class="pb-collapse-wrapper">
                    <form method="post" id="current-section-form">
                        <input type="hidden" name="section_id" id="current-section-id" />
                        <div id="section-settings-wrapper">
                            <div>
                                <span class="at-empty-settings">{{ __('pagify::pagify.select_any_element') }}</span>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="pb-adv-setting-tab tab-pane fade" id="advanced-settings-tab">
                <div class="pb-collapse-wrapper">
                    <form id="current-advanced-settings-form">
                        <input type="hidden" name="grid_id" id="current-grid-id" />
                        <div id="advanced-settings-wrapper">
                            <span class="at-empty-settings">{{ __('pagify::pagify.select_any_element') }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-btn-wrapper">
        <a href="{{ config('pagify.builder.exist_url') ?? '#' }}" class="pb-gobackbtn"><i title="{{ __('pagify::pagify.go_back') }}"
                class="icon-arrow-left"></i></a>
        <a target="_blank" href="{{ previewUrl($page->slug) . '?preview=yes' }}"> <i class="icon-monitor"></i>
            {{ __('pagify::pagify.preview') }}</a>
        <button class="pb-btn savePageData">{{ __('pagify::pagify.save') }}<i class="icon-loader"></i></button>
    </div>
</aside>
<!-- asidebar -->
