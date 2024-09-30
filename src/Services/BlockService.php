<?php

namespace Millat\Pagify\Services;

class BlockService
{
    /**
     * Retrieve all blocks or a specific block.
     *
     * @param string|null $blockId
     * @return array
     */
    public static function get(?string $blockId = null): array
    {
        $blocks = config('blocks');
        $blockList = [];

        // If a specific block is requested
        if ($blockId) {
            return self::getBlockSettings($blocks, $blockId);
        }

        // Retrieve settings for all blocks
        foreach ($blocks as $key => $block) {
            if (class_exists($block)) {
                $blockList[$key] = (new $block)->getSettings();
            }
        }

        return $blockList;
    }

    /**
     * Retrieve default value for a specific field in a block.
     * 
     * @param string $blockId
     * @param string $key
     * 
     * @return string
     */
    public static function getDefaultValues(?string $blockId, string $key): string
    {
        $currentSettings = self::get($blockId);

        // If block has fields, search for the field with the provided key
        foreach ($currentSettings['fields'] ?? [] as $item) {
            if ($item['id'] === $key) {
                return $item['value'] ?? '';
            }
        }

        return '';
    }

    /**
     * Get block settings if it exists and the class is valid.
     *
     * @param array $blocks
     * @param string $blockId
     * @return array
     */
    protected static function getBlockSettings(array $blocks, string $blockId): array
    {
        // Ensure block exists and class is valid
        if (isset($blocks[$blockId]) && class_exists($blocks[$blockId])) {
            return (new $blocks[$blockId])->getSettings();
        }

        return [];
    }
}
