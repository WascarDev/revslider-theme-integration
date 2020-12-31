<?php

require 'RevSliderThemeMeta.php';
require 'RevSliderThemeMetaTaxonomy.php';

class RevSliderThemeIntegration
{

    private static ?RevSliderThemeIntegration $instance = null;
    private static $metas = ['taxonomy' => '', 'post' => ''];

    /**
     * RevSliderThemeIntegration constructor.
     */
    private function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {

    }

    public function getConfig()
    {
        return get_option('revslider_theme_integration');
    }

    public function getContentMeta($content_type = "", $args = null)
    {
        if($content_type === '') {
            if(is_single()) {
                $content_type = 'post';
            }
        }

        switch ($content_type) {
            case 'post':
                return new RevSliderThemeMetaPost($args);
            case 'taxonomy':
                return new RevSliderThemeMetaTaxonomy($args);
            default:
                return null;
        }

    }

    public static function getInstance(): RevSliderThemeIntegration
    {
        if (self::$instance == null) {
            self::$instance = new RevSliderThemeIntegration();
        }

        return self::$instance;
    }
}

$revslider_theme_integration_plugin = RevSliderThemeIntegration::getInstance();