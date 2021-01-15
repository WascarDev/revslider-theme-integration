<?php
/**
 * Plugin Name: RevSlider Theme Integration
 */

require 'includes/RevSliderIntegration_Meta.php';
require 'includes/RevSliderIntegration_MetaPost.php';
require 'includes/RevSliderIntegration_MetaTaxonomy.php';

class RevSliderThemeIntegration
{

    private static ?RevSliderThemeIntegration $instance = null;
    private array $metas;

    /**
     * RevSliderThemeIntegration constructor.
     */
    private function __construct()
    {
        add_action('init', [$this, 'init']);
        $this->metas = [new RevSliderIntegration_MetaTaxonomy(), new RevSliderIntegration_MetaPost()];
    }

    public function init()
    {
        add_action('customize_register', [$this, 'setupCustomize']);

        foreach ($this->metas as $meta) {
            $meta->init();
        }
    }

    public function setupCustomize(WP_Customize_Manager $wp_customize)
    {
        require_once('includes/RevSliderIntegration_Control_Checkbox_Multiple.php');

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

        foreach ($this->metas as $meta) {
            $settings_id = 'revsliderintegration_options[' . $meta->getManagedType() . ']';

            $wp_customize->add_setting($settings_id, array('default' => $meta->getDefaultElements(), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => array($this, 'sanitizeContentTypeSelector')));
            $elements = $meta->getElements();
            $wp_customize->add_control(
                new RevSliderIntegration_Control_Checkbox_Multiple(
                    $wp_customize,
                    'slider_managed_' . $meta->getManagedType() . '_control',
                    array(
                        'settings' => $settings_id,
                        'section' => 'revsliderintegration_options',
                        'label' => $meta->getCustomizeTitle(),
                        'choices' => $elements
                    )
                )
            );
        }
    }

    public function sanitizeContentTypeSelector( $values ) {
        $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

        return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
    }

    public function getDefaultSlider(): ?RevSlider
    {
        $option = get_option('revsliderintegration_options');

        if (!empty($option) && isset($option['default_slider'])) {
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
        foreach ($this->metas as $meta) {
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