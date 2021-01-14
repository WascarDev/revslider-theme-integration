<?php


class RevSliderThemeMetaPost extends RevSliderThemeMeta
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
}