<?php

namespace Millat\Pagify;

class Sanitize
{
    /**
     * Recursively sanitize an array by applying the text field sanitization.
     *
     * @param array $arr The array to sanitize.
     * @return array The sanitized array.
     */
    public static function array(array &$arr): array
    {
        foreach ($arr as $key => &$el) {
            if (is_array($el)) {
                // Recursively sanitize nested arrays
                self::array($el);
            } else {
                // Sanitize individual elements
                $el = self::textField($el, true);
            }
        }

        return $arr;
    }

    /**
     * Sanitize a string input for text fields by stripping unwanted tags and optionally removing line breaks.
     *
     * @param mixed $input The input to sanitize.
     * @param bool $removeLineBreak Whether to remove line breaks from the input.
     * @return string The sanitized string.
     */
    public static function textField($input, bool $removeLineBreak = false): string
    {
        // If input is an object or array, return an empty string
        if (is_object($input) || is_array($input)) {
            return '';
        }

        // Strip unwanted tags and handle line breaks
        return self::stripTags($input, $removeLineBreak);
    }

    /**
     * Strip HTML tags from a string, keeping certain allowed tags, and optionally remove line breaks.
     *
     * @param string $input The input string to sanitize.
     * @param bool $removeLineBreak Whether to remove line breaks from the input.
     * @return string The sanitized string with allowed tags.
     */
    public static function stripTags(string|null $input, bool $removeLineBreak = false): string
    {
        // Remove <script> and <style> content
        $input = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $input);

        // Strip unwanted tags, but allow specific HTML tags
        $input = strip_tags($input, '<h1><h2><h3><h4><h5><h6><div><b><strong><i><em><a><ul><ol><li><p><br><span><figure><sup><sub><table><tr><th><td><tbody><iframe><form><capture><label><fieldset><section>');

        // Optionally remove line breaks
        if ($removeLineBreak) {
            $input = preg_replace('/[\r\n\t ]+/', ' ', $input);
        }

        // Return the trimmed result
        return trim($input);
    }
}