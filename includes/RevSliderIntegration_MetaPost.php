<?php


class RevSliderIntegration_MetaPost extends RevSliderIntegration_Meta
{
    public function __construct()
    {
        parent::__construct('post');
    }

    public function getSliderId(int $contentId = -1): int
    {
        if ($contentId == -1) {
            $contentId = get_the_ID();
        }

        return get_post_meta($contentId, 'revslderintegration_slider_id', true);
    }

    public function contextIsManageable(): bool
    {
        return is_single() || is_page();
    }

    public function getElements(): array
    {
        return get_post_types(array('public' => true));
    }

    public function getDefaultElements(): array
    {
        return array('post', 'page');
    }

    public function getCustomizeTitle(): string
    {
        return __('Post types with Slider');
    }

    public function init()
    {
        // TODO: Implement init() method.
    }
}