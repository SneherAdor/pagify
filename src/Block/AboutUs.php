<?php

namespace Millat\Pagify\Block;

use Millat\Pagify\Block;

class AboutUs extends Block
{
    protected $view = 'site.blocks.about-us';

    public function addHeading()
    {
        return [
            'id'            => 'small-heading',
            'type'          => 'text',
            'value'         => 'Before we dive in to the your career, tell me a little.',
            'class'         => '',
            'label_title'   => __('Small heading'),
            'placeholder'   => __('Small heading'),
        ];
    }

    public function addDescription()
    {
        return [
            'id'            => 'description',
            'type'          => 'textarea',
            'value'         => 'We are a team of passionate people whose goal is to improve everyone\'s life through disruptive products. We build great products to solve your business problems.',
            'class'         => '',
            'label_title'   => __('Description'),
            'placeholder'   => __('Description'),
        ];
    }

    public function addImage()
    {
        return [
            'id'            => 'image',
            'type'          => 'file',
            'field_desc' => __('only .jpg,.png allowed and max size is 3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('Image')
        ];
    }
}
