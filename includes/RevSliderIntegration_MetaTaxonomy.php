<?php


class RevSliderIntegration_MetaTaxonomy extends RevSliderIntegration_Meta
{
    /**
     * RevSliderThemeMetaTaxonomy constructor.
     */
    public function __construct() {
        parent::__construct('taxonomy');
    }

    public function getSliderId(int $contentId = -1): int
    {
        if($contentId == -1) {
            $contentId = get_queried_object()->term_id;
        }

        return get_term_meta($contentId, 'revslderintegration_slider_id', true);
    }

    public function contextIsManageable(): bool
    {
        return is_tax() || is_tag() || is_category();
    }

    public function getElements(): array
    {
        return get_taxonomies();
    }

    public function getDefaultElements(): array
    {
        return array('category');
    }

    public function getCustomizeTitle(): string
    {
        return __('Taxonomy types with Slider');
    }

    public function init()
    {
        // TODO: Implement init() method.
    }
}