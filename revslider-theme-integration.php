<?php

require 'RevSliderThemeMeta.php';
require 'RevSliderThemeMetaTaxonomy.php';
require 'RevSliderThemeMetaPost.php';

class RevSliderThemeIntegration
{

    private static ?RevSliderThemeIntegration $instance = null;
    private static array $metas;

    /**
     * RevSliderThemeIntegration constructor.
     */
    private function __construct()
    {
        add_action('init', [$this, 'init']);
        RevSliderThemeIntegration::$metas = [new RevSliderThemeMetaTaxonomy(), new RevSliderThemeMetaPost()];
    }

    public function init()
    {
        add_action('customize_register', [$this, 'setupCustomize']);
    }

    public function setupCustomize(WP_Customize_Manager $wp_customize)
    {
        $wp_customize->add_section('revsliderintegration_options', array('title' => __('RevSlider Integration')));

        $wp_customize->add_setting('revsliderintegration_options[default_slider]',
            array('capability' => 'edit_theme_options', 'type' => 'option'));

        $slider = new RevSlider();

        $slider_list = array('' => '-');
        foreach ($slider->get_sliders() as $s) {
            $slider_list[$s->get_id()] = $s->get_title();
        }

        $wp_customize->add_control('slider_select_box', array(
            'settings' => 'revsliderintegration_options[default_slider]',
            'label' => __('Default Post Slider'),
            'section' => 'revsliderintegration_options',
            'type' => 'select',
            'choices' => $slider_list
        ));
    }

    public function getDefaultSlider(): ?RevSlider
    {
        $option = get_option('revsliderintegration_options');

        if(!empty($option) && isset($option['default_slider']))
        {
            $id = $option['default_slider'];
            $slider = new RevSlider();
            $list = $slider->get_sliders();

            foreach ($list as $s) {
                if ($s->get_id() === $id) {
                    return $s;
                }
            }
        }

        return null;
    }

    public function getSlider($contentType = '', $id = '')
    {
        foreach (RevSliderThemeIntegration::$metas as $meta) {
            if ($contentType == '') {
                if ($meta->contextIsManageable())
                    $contentType = $meta->getManagedType();
            }

            if ($meta->typeIsManageable($contentType)) {
                return $meta->getSlider($id);
            }
        }

        return $this->getDefaultSlider();
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