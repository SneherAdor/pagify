<?php 

namespace Millat\Pagify\Traits;

trait JsonFormatter 
{
    /**
     * Decode the value of a setting.
     *
     * @param string $settingValue
     * @return mixed
     */
    private static function jsonDecodedArr(&$arr)
    {
        foreach ($arr as &$el) {

            if (is_array($el)) {
                self::jsonDecodedArr($el);
            } else {
                if (self::isJSON($el)) {
                    $el = json_decode($el, true);
                }
            }
        }

        return  $arr;
    }

    /**
     * Check if a string is a valid JSON.
     *
     * @param string $string
     * @return bool
     */
    private static function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    
    private static function decodeValue($settingValue) 
    {
        $value = @unserialize($settingValue);
        if ($value == 'b:0;' || $value !== false) {
            $temp = [];
            foreach ($value as $key => $data) {
                if (is_array($data)) {
                    $temp[$key] = self::jsonDecodedArr($data);
                } else {
                    if (self::isJSON($data)) {
                        $temp[$key] = json_decode($data, true);
                    } else {
                        $temp[$key] = $data;
                    }
                }
            }
            return $temp;
        } else {
            if (self::isJSON($settingValue)) {
                return (json_decode($settingValue, true));
            } else {
                return $settingValue;
            }
        }
    }
}
