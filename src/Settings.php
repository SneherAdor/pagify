<?php

namespace Millat\Pagify;

use Millat\Pagify\Traits\JsonFormatter;

class Settings 
{
    use JsonFormatter;

    public function getSectionSetting($params, $fields) 
    {
        $html = '';

        if (!is_array($fields) || empty($fields)) {
            return $html;
        }

        $tab_key = !empty($params['tab_key']) ? $params['tab_key'] :  '';
        $html = '<ul class="op-themeform__wrap">';
        foreach ($fields as $field) {
            $field['tab_key']       = $tab_key;
            if (empty($params['repeater_id'])) {
                $id = !empty($field['id']) ? $field['id'] : '';
                $db_value = self::get($tab_key . '.' . $id);
                $field['db_value']   = $db_value;
                if (!empty($db_value)) {
                    $field['value']   = $db_value;
                }
            } else {
                $field['repeater_id']   = !empty($params['repeater_id']) ? $params['repeater_id'] :  '';
                $field['index']         = !empty($params['repeater_id']) ? $params['index'] :  '';
            }

            $html .= self::getField($field);
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Generate and return the HTML for a form field based on its type.
     *
     * @param array $field The field configuration array containing type and other attributes.
     * @return string The rendered HTML for the field.
     */
    public function getField(array $field): string
    {
        if (empty($field['type'])) {
            return '';
        }

        $viewMapping = [
            'text'        => 'pagify::components.options.input',
            'password'    => 'pagify::components.options.input',
            'number'      => 'pagify::components.options.input',
            'editor'      => 'pagify::components.options.input',
            'file'        => 'pagify::components.options.file',
            'textarea'    => 'pagify::components.options.textarea',
            'timepicker'  => 'pagify::components.options.timepicker',
            'datepicker'  => 'pagify::components.options.datepicker',
            'colorpicker' => 'pagify::components.options.colorpicker',
            'info'        => 'pagify::components.options.info',
            'radio'       => 'pagify::components.options.radio',
            'checkbox'    => 'pagify::components.options.checkbox',
            'switch'      => 'pagify::components.options.switch',
            'select'      => 'pagify::components.options.select',
            'range'       => 'pagify::components.options.range_slider',
            'repeater'    => !empty($field['multi']) && $field['multi']
                                ? 'pagify::components.options.multiple-repeater'
                                : 'pagify::components.options.single-repeater',
        ];

        $view = $viewMapping[$field['type']] ?? null;

        return $view ? view($view, $field)->render() : '';
    }
}
