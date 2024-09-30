@php
    $name = '';
    if (!empty($repeater_id)) {
        if (!empty($parent_rep)) {
            $name = "$parent_rep" . '[' . $repeater_id . '][' . $index . '][' . $id . ']';
        } else {
            $name = "$repeater_id" . '[' . $index . '][' . $id . ']';
        }
    } else {
        $name = !empty($id) ? $id : '';
    }
@endphp
@if (!empty($repeater_type) && $repeater_type == 'single')
    <textarea data-id="{{ $id ?? '' }}" @if (!empty($parent_rep)) data-parent_rep="{{ $parent_rep }}" @endif
        class="op-input-field form-control {{ $class ?? '' }}" name="{{ $name }}"
        placeholder="{{ $placeholder ?? '' }}">{{ $value ?? '' }}</textarea>
@else
    <li class="form-group-wrap">
        @if (!empty($label_title))
            <div class="form-group-half">
                <div class="op-textcontent">
                    <h6>
                        {!! $label_title !!}
                    </h6>
                    @if (!empty($label_desc))
                        <em>{!! $label_desc !!}</em>
                    @endif
                </div>
            </div>
        @endif
        <div class="form-group-half">
            <div class="op-textcontent">
                <textarea data-id="{{ $id ?? '' }}" @if (!empty($parent_rep)) data-parent_rep="{{ $parent_rep }}" @endif
                    class="op-input-field form-control {{ $class ?? '' }}" name="{{ $name }}"
                    placeholder="{{ $placeholder ?? '' }}">{{ $value ?? '' }}</textarea>
                @if (!empty($field_desc))
                    <span>{!! $field_desc !!}</span>
                @endif
            </div>
        </div>
    </li>
@endif
