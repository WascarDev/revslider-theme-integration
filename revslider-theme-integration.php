<?php

class RevSliderThemeIntegration {

    private static ?RevSliderThemeIntegration $instance = null;

    /**
     * RevSliderThemeIntegration constructor.
     */
    private function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init() {

    }

    public function getConfig() {
        $r = get_option('revslider_theme_integration');

        if($r) {
            $r = ['taxonomies' => [], 'posts' => [], 'defaultSlider' => ''];
        }

        return $r;
    }

    /**
     * Check if a taxonomy is managed by the plugin
     *
     * @param $taxonomy : id of taxonomy
     * @return bool : true if the taxonomy is managed by the plugin
     */
    public function hasTaxonomyManaged($taxonomy): bool {
        return in_array($taxonomy, self::getConfig()['taxonomies']);
    }

    public function getTaxonomySlider($taxonomy, $slug) {

    }

    public static function getInstance(): RevSliderThemeIntegration
    {
        if(self::$instance == null) {
            self::$instance = new RevSliderThemeIntegration();
        }

        return self::$instance;
    }
}

$revslider_theme_integration_plugin = RevSliderThemeIntegration::getInstance();