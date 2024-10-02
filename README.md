# Pagify - Dynamic Page Builder

## Creating a Custom Block

The `Block` class in `Millat\Pagify` provides the foundation for creating customizable content blocks in the page builder. To define your own block, extend the `Block` abstract class and implement your own settings and fields.

---

### Steps to Define a Custom Block

#### 1. Extend the Block Class

Create a new class that extends the `Millat\Pagify\Block` class. This new class represents the block you want to define (e.g., a banner block, FAQ block, etc.).

Here’s an example of a basic `AboutUs`:

```php
<?php

namespace Millat\Pagify\Blocks;

use Millat\Pagify\Block;

class AboutUs extends Block
{
    protected $view = 'blocks.about-us';

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

```

#### 2. Define Block Settings

You can customize block settings such as ID, name, icon, and view by overriding the properties in the parent `Block` class. These settings help the page builder recognize and render the block.

**Block Settings:**

- **Block ID**: A unique identifier for the block.
- **Block Name**: The block name displayed in the builder.
- **Block Icon**: HTML string representing the block’s icon (e.g., Font Awesome icon).
- **Block View**: The view template for rendering the block in the front-end.

```php
protected $id = 'about-us';
protected $name = 'About Us';
protected $icon = '<i class="icon-text"></i>';
protected $view = 'blocks.about-us';
```

#### 3. Adding Custom Fields

You can define custom fields by creating public methods that return an array of field attributes. These fields allow users to customize block content. Each field method should start with the prefix `add` (e.g., `addContentField`, `addFontSizeField`). The `Block` class will automatically collect these fields into the block’s settings.

**Example - Custom Field:**

```php
public function addHeading(): array
{
    return [
		'id' => 'small-heading',
		'type' => 'text',
		'value' => 'Before we dive into your career, tell me a little.',
		'class' => '',
		'label_title' => __('Small Heading'),
		'placeholder' => __('Small Heading'),
	];
}
```

> **Field Types**: The page builder supports multiple field types (e.g., text, textarea, select), which we’ll discuss later in the documentation.

---

#### 4. View or blade file of `AboutUs` class
The blade or view file will in `blocks.about-us` view path.

```php
<div class="card" style="width: 18rem;">
    @php
        $image = pagify('image');
    @endphp
    @if (!empty($image))
        <img src="{{ $image[0]['thumbnail'] }}" alt="{{ __('Image') }}" />
    @else
        <img src="{{ asset(defaultImage('products')) }}" alt="{{ __('Image') }}" />
    @endif
    <div class="card-body">
        <h5 class="card-title">{{ pagify('small-heading') }}</h5>
        <p class="card-text">{{ pagify('description') }}</p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
    </div>
</div>

```


#### 5. Registering the Block

Once your custom block is created, register it within the application to make it available in the page builder. This is done by merging your custom block into the existing blocks configuration.

Here’s an example of how to register the `AboutUs`:

```php
// Define custom blocks to be registered
$customBlocks = [
    'text-block' => \Millat\Pagify\Blocks\AboutUsBlock::class,
];

// Merge custom blocks with the existing blocks
$blocks = Config::get('blocks');
unset($blocks['about-us']); // Optional: Remove an existing block if needed
Config::set('blocks', array_merge($blocks, $customBlocks));
```

This process ensures that the system recognizes your new block and makes it available within the page builder interface.

---

### Field Types


## Text field

```php
return [
      'id'            => 'textbox',
      'type'          => 'text',
      'value'         => '',
      'class'         => '',
      'label_title'   => __('Textbox field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
      'placeholder'   => __('Textbox placeholder'),
      'hint'     => [
        'content' => __('Hint content about this field!'),
      ],
  ];
```

## Number field

```php
return [
      'id'            => 'number',
      'type'          => 'number',
      'value'         => '',
      'class'         => '',
      'label_title'   => __('Number field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
      'placeholder'   => __('Default number placeholder'),
  ];
```

## Password field

```php
return [
      'id'            => 'password',
      'type'          => 'password',
      'value'         => '',
      'class'         => '',
      'label_title'   => __('Password'),
      'label_desc'    => __('This is the label desc you can use here'),
      'placeholder'   => __('*****'),
  ];
```

## Textarea field

```php
return [
      'id'            => 'textarea',
      'type'          => 'textarea',
      'class'         => '',
      'value'         => '',
      'label_title'   => __('Textarea field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
  ];
```

## Texteditor field

```php
return [
      'id'            => 'texteditor',
      'type'          => 'editor',
      'class'         => '',
      'label_title'   => __('Text editor'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the editor description text you can use here'),
  ];
```

## Radio field

```php
return [
      'id'            => 'gender',
      'type'          => 'radio',
      'class'         => '',
      'label_title'   => __('Radio button field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
      'options'       => [
        'male'  => __('Male'),
        'femle' => __('Female'),
      ],
      'default'       => 'male',  
  ];
```

## Switch field

```php

// Single switch field
return [
      'id'            => 'switch',
      'type'          => 'switch',
      'class'         => '',
      'label_title'   => __('Single switch field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_title'   => __('Single switch title'), 
      'field_desc'    => __('This is the field description you can use here'), 
      'value'       => '1',  
  ];

// Multiple switch field
return [
      'id'            => 'multiswitch',
      'type'          => 'switch',
      'class'         => '',
      'label_title'   => __('Multiple switch field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'), 
      'options'       => [
        '1' => __('Switch opt 1'),
        '2' => __('Switch opt 2'),
        '3' => __('Switch opt 3'),
        '4' => __('Switch opt 4'),
        '5' => __('Switch opt 5'),
        '6' => __('Switch opt 6'),
      ],
      'default'       => [2,6,4],  
  ];
```

## Checkbox field

```php

// Single checkbox field
return [
      'id'                    => 'checkbox',
      'type'                  => 'checkbox',
      'class'                 => '',
      'label_title'           => __('Single checkbox field'),
      'label_desc'            => __('This is the label description you can use here'),
      'field_desc'            => __('This is the field description you can use here'),
      'field_title'           => __('Checkbox single'), 
      'value'                 => '1', 
  ];

// Multiple checkbox field

return [
      'id'            => 'multicheckbox_id',
      'type'          => 'checkbox',
      'class'         => '',
      'label_title'   => __('Multiple checkbox field'),
      'label_desc'    => __('This is the label description you can use here'),
      'options'       => [
        '1' => __('Option 1'),
        '2' => __('Option 2'),
        '3' => __('Option 3'),
        '4' => __('Option 4'),
        '5' => __('Option 5'),
        '6' => __('Option 6'),
      ],
      'default'       => [1,6],  
  ];
```

## Select field

```php

// Single select field
return [
      'id'            => 'select',
      'type'          => 'select',
      'class'         => '',
      'label_title'   => __('Single select field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
      'options'       => [
        'alabama'   => 'Alabama',
        'wyoming'   => 'Wyoming',
        'choice'    => 'Choice',
        'usa'       => 'USA',
        'france'    => 'France',
        'japan'     => 'Japan',
        'itly'      => 'Itly',
        'uae'       => 'UAE',
        'uk'        => 'United kindom',
        'germany'   => 'Germany',
      ],
      'default'       => 'uk',  
      'placeholder'   => __('Select from the list'),  
  ];

// Multi select field

return [
      'id'            => 'multiselect',
      'type'          => 'select',
      'class'         => '',
      'multi'         => true,
      'label_title'   => __('Multi Select field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
      'options'       => [
        'usa'       => 'USA',
        'france'    => 'France',
        'japan'     => 'Japan',
        'itly'      => 'Itly',
        'uae'       => 'UAE',
        'alabama'   => 'Alabama',
        'wyoming'   => 'Wyoming',
        'choice'    => 'Choice',
        'uk'        => 'United kindom',
        'germany'   => 'Germany',
      ],
      'default'       => ['uk'],  
      'placeholder'   => __('Select from the list'),   
  ];
```

## File uploader field

```php
// Single file uploader field

return [
        'id'            => 'file_uploader',
        'type'          => 'file',
        'class'         => '',
        'label_title'   => __('Single file uploader field'),
        'label_desc'    => __('This is the file description you can use here'),
        'field_desc'    => __('This is the file uploader description text you can use here'),
        'max_size'   => 4,                  // size in MB
        'ext'    =>[
            'jpg',
            'png',
            'pdf',
            'doc',
            'xlsx',
            'ppt',
        ], 
    ];

// Multiple file uploader field
return [                                             
      'id'            => 'multifile_uploader',
      'type'          => 'file',
      'class'         => '',
      'label_title'   => __('Multi file uploader field'),
      'label_desc'    => __('This is the file description you can use here'),
      'field_desc'    => __('This is the file uploader description text you can use here'),
      'multi'       => true,
      'max_size'   => 4,                  // size in MB
      'ext'    =>[
          'jpg',
          'png',
          'pdf',
          'doc',
          'xlsx',
          'ppt',
      ], 
  ];

```

## Color picker field

```php
return [
      'id'            => 'color_picker',
      'type'          => 'color',
      'class'         => '',
      'label_title'   => __('Color picker field'),
      'label_desc'    => __('This is the label description you can use here'),
      'field_desc'    => __('This is the field description you can use here'),
  ];
```

## Date and date range picker field

```php
// Single date picker field
return [
        'id'            => 'datepicker',
        'type'          => 'datepicker',
        'value'         => '',
        'class'         => '',
        'label_title'   => __('Date picker field'),
        'label_desc'    => __('This is the label description you can use here'),
        'field_desc'    => __('This is the field description you can use here'),
        'placeholder'   => __('Select date'),
        'format'        => 'Y-m-d',
    ];

// Date with time picker field
return [                                         //date with time picker
          'id'            => 'datepicker_time',
          'type'          => 'datepicker',
          'value'         => '',
          'class'         => '',
          'label_title'   => __('Date time field'),
          'label_desc'    => __('This is the label description you can use here'),
          'field_desc'    => __('This is the field description you can use here'),
          'placeholder'   => __('Select date time'),
          'format'        => 'Y-m-d H:i:s',
          'time'    =>[
            'enable'    => true,
            'time_24hr' => true,
          ]  
    ];

// Date range picker field
return [
          'id'            => 'date_range',
          'type'          => 'datepicker',
          'value'         => '',
          'class'         => '',
          'label_title'   => __('Date range picker field'),
          'label_desc'    => __('This is the label description you can use here'),
          'field_desc'    => __('This is the field description you can use here'),
          'placeholder'   => __('Select date range'),
          'format'        => 'Y-m-d',
          'mode'          => 'range',
    ];
// Specifid date picker
  return [  
          'id'            => 'date_specific',
          'type'          => 'datepicker',
          'value'         => '',
          'class'         => '',
          'label_title'   => __('Specific date picker field'),
          'label_desc'    => __('This is the label description you can use here'),
          'field_desc'    => __('This is the field description you can use here'),
          'placeholder'   => __('Select specific dates'),
          'format'        => 'Y-m-d',
          'mode'          => 'multiple',
    ];
// Time picker
  return [ 
        'id'            => 'time',
        'type'          => 'timepicker',
        'value'         => '',
        'class'         => '',
        'label_title'   => __('Time picker field'),
        'label_desc'    => __('This is the label description you can use here'),
        'field_desc'    => __('This is the field description you can use here'),
        'placeholder'   => __('Select time'),
        'time_24hr'     => false,
  ];
```

## Repeator field

```php
// repeator with single field
  return [ 
          'id'                => 'single_repeator',
          'type'              => 'repeater',
          'label_title'       => __('Single field repeator'),
          'label_desc'        => __('This is the repeator description you can use here'),
          'field'             => [
              'id'            => 'textbox_id',
              'type'          => 'text',
              'value'         => '',
              'class'         => '',
              'label_title'   => __('Textbox repeator'),
              'label_desc'    => __('This is the label description you can use here'),
              'field_desc'    => __('This is the field description you can use here'),
              'placeholder'   => __('Textbox placeholder'),
          ]
  ];

// repeator with multiple fields
  return [                                                           // repeator with multiple fields
          'id'                => 'multi_repeator',
          'type'              => 'repeater',
          'label_title'       => __('Multiple fields repeator'),
          'label_desc'        => __('This is the label description you can use here'),
          'repeater_title'    => __('Repeater title'),
          'multi'       => true,
          'fields'       =>[
              [
                  'id'            => 'repeator_textbox_id',
                  'type'          => 'text',
                  'value'         => '',
                  'class'         => '',
                  'label_title'   => __('Textbox in repeator'),
                  'label_desc'    => __('This is the label description you can use here'),
                  'field_desc'    => __('This is the field description you can use here'),
                  'placeholder'   => __('Textbox placeholder'),
              ],
              [
                  'id'            => 'repeator_textarea_id',
                  'type'          => 'textarea',
                  'class'         => '',
                  'value'         => '',
                  'label_title'   => __('Textarea'),
                  'label_desc'    => __('This is the label description you can use here'),
                  'field_desc'    => __('This is the field description you can use here'),
              ],
              [
                  'id'            => 'repeator_file_uploader',
                  'type'          => 'file',
                  'class'         => '',
                  'label_title'   => __('File uploader'),
                  'label_desc'    => __('This is the file description you can use here'),
                  'field_desc'    => __('This is the file uploader description text you can use here'),
                  'multi'       => true,
                  'max_size'   => 4,                  // size in MB
                  'ext'    =>[
                      'jpg',
                      'png',
                      'pdf',
                      'doc',
                      'xlsx',
                      'ppt',
                  ], 
              ]
     ];
```

## Range selector field

```php
return [
        'id'            => 'ranger_selector_id',
        'type'          => 'range',
        'label_title'   => __('range selector field'),
        'options'       =>[
          'min'     => 1
          'max'     => 500
          'format'  => 'number'           // by default it shows decimal
        ]
    ];
```

## Info seprator

```php
return [
          'id'            => 'info_seprator_id',
          'type'          => 'info',
          'label_title'   => __('Section separator style'),
          'label_desc'    => __('This is the info seprator description'), 
    ];
```