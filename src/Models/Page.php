<?php

namespace Millat\Pagify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Millat\Pagify\Traits\JsonFormatter;
use Illuminate\Support\Facades\Cache;
use Millat\Pagify\Sanitize;
use Millat\Pagify\Services\FileUploadService;

class Page extends Model
{
    use HasFactory, JsonFormatter;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'name', 
        'description', 
        'slug', 
        'settings'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];

    public static function findById($pageId)
    {
        return Cache::rememberForever('pagify_page_data' . $pageId, function () use ($pageId) {
            return Page::find($pageId) ?? null;
        });
    }

    public static function settings($pageId)
    {
        return self::findById($pageId)->settings ?? [];
    }

    public static function sectionSettings($pageId, $sectionId, $key)
    {
        $db_value = '';
        $pageSettings = self::findById($pageId)->settings ?? [];
        $dbValue = [];

        if (!empty($pageSettings['section_data']) && array_key_exists($sectionId, $pageSettings['section_data'])) {
            $dbValue = $pageSettings['section_data'][$sectionId]['settings'][$key] ?? [];
            if (!empty($dbValue['is_array']) && $dbValue['is_array'] == '1') {
                foreach ($dbValue['value'] as $index => $value) {
                    
                    if (!is_array($value) && self::isJSON($value)){
                        $dbValue['value'][$index] = json_decode($value, true);
                    } elseif (!is_array($value) && !self::isJSON($value)) {
                        $dbValue['value'][$index] = $value;
                    } else {
                        $dbValue['value'][$index] = self::jsonDecodedArr($value);
                    }
                }
            }
        }

        if (!empty($dbValue['value'])) {
            $db_value = $dbValue['value'];
        }

        return $db_value;
    }

    public static function store(int $pageId, array $pageSettings = []): bool
    {
        if (!empty($pageSettings)) {
            $pageSettings = Sanitize::array($pageSettings);
        }

        $page = Page::find($pageId);

        if ($page) {
            FileUploadService::deletePageFiles($page->settings, $pageSettings);
            
            $page->update(['settings' => $pageSettings]);
            Cache::forget('pagify_page_data' . $pageId);

            return true;
        }

        // Return false if the page was not found
        return false;
    }
}
