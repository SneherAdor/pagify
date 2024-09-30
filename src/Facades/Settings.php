<?php

namespace Millat\Pagify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getSectionSetting(array $params, array $fields) Generate and return HTML for the section's settings fields.
 * @method static string getField(array $field) Generate and return HTML for a specific field.
 *
 * @see \Millat\Pagify\Settings
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}
