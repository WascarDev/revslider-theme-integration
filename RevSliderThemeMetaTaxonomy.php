<?php


class RevSliderThemeMetaTaxonomy extends RevSliderThemeMeta
{
    /**
     * RevSliderThemeMetaTaxonomy constructor.
     */
    public function __construct() {
        parent::__construct('taxonomy');
    }

    public function getSliderId(int $contentId = -1): int
    {
        // TODO: Implement getSliderId() method.
    }

    public function contextIsManageable(): bool
    {
        // TODO: Implement contextIsManageable() method.
    }
}