<?php


class RevSliderThemeMetaPost extends RevSliderThemeMeta
{
    private WP_Post $post;

    /**
     * RevSliderThemeMetaPost constructor.
     * @param $args
     */
    public function __construct($args = null)
    {
        $this->post = new WP_Post();
    }

    public function getSliderId()
    {
        // TODO: Implement getSliderId() method.
    }
}