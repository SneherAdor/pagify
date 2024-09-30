<li class="form-group-wrap op-textcontent">
    @if (!empty($label_title))
        <div class="form-group-half">
            <div class="op-textcontent">
                <h6>{!! $label_title !!}
                </h6>
                @if (!empty($label_desc))
                    <em>{!! $label_desc !!}</em>
                @endif
            </div>
        </div>
    @endif

    <div class="form-group-half">
        <div class="op-add-slot" data-id="{{ $id ?? '' }}">
            @if (!empty($value) && is_array($value))
                @foreach ($value as $i => $single)
                    @if ($field['type'] == 'switch' || $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'file')
                        <div class="op-box-feild">
                    @endif

                    <div class="op-reapfeild op-single-repetitor">
                        @if (!empty($field) && is_array($field))
                            @php
                                $field['repeater_type'] = 'single';
                                $field['repeater_id'] = $id;
                                $field['index'] = $i;
                                if ($field['id'] == key($single)) {
                                    $field['value'] = $single[key($single)];
                                }
                                if (!empty($repeater_id)) {
                                    $field['parent_rep'] = "$repeater_id" . '[' . $index . ']';
                                }
                            @endphp
                            {!! getField($field) !!}
                        @endif
                        @if (!isset($edit) || !empty($edit))
                            <a class="op-trashfeild op-trash-single-rep" href="javascript:;"
                                data-repeater_id="{{ $id ?? '' }}"><i class="icon-trash-2"></i>
                                @if ($field['type'] == 'switch' || $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'file')
                                    <span>{{ __('pagify::pagify.remove') }}</span>
                                @endif
                            </a>
                        @endif
                    </div>

                    @if ($field['type'] == 'switch' || $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'file')
        </div>
        @endif
        @endforeach
    @else
        @if ($field['type'] == 'switch' || $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'file')
            <div class="op-box-feild">
        @endif
        <div class="op-reapfeild op-single-repetitor">
            @if (!empty($field) && is_array($field))
                @php
                    $field['repeater_type'] = 'single';
                    $field['repeater_id'] = $id;
                    $field['index'] = rand(1, 999) . time();
                    if (!empty($repeater_id)) {
                        $field['parent_rep'] = "$repeater_id" . '[' . $index . ']';
                    }
                @endphp
                {!! getField($field) !!}
            @endif
            @if (!isset($edit) || !empty($edit))
                <a class="op-trashfeild op-trash-single-rep" href="javascript:;"
                    data-repeater_id="{{ $id ?? '' }}"><i class="icon-trash-2"></i>
                    @if ($field['type'] == 'switch' || $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'file')
                        <span>{{ __('pagify::pagify.remove') }}</span>
                    @endif
                </a>
            @endif
        </div>
        @if ($field['type'] == 'switch' || $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'file')
    </div>
    @endif
    @endif
    @if (!isset($edit) || !empty($edit))
        <div class="op-add-dwonload more-single-rep" data-repeater="{{ $id ?? '' }}">
            <a class="op-btn-two" href="javascript:;"><i
                    class="fa fa-plus"></i>{{ __('pagify::pagify.add_more') }}</a>
        </div>
    @endif
    </div>
    </div>
</li>
