<?php

abstract class RevSliderThemeMeta
{
    public abstract function getSliderId();

    public function getSlider(): ?RevSlider
    {
        $revslider = new RevSlider();
        $expected_id = $this->getSliderId();

        if ($expected_id != -1) {
            foreach ($revslider->get_sliders() as $slider) {
                if ($slider->get_id() === $expected_id) {
                    return $slider;
                }
            }
        }

        return null;
    }

    public function displaySlider()
    {
        $slider = $this->getSlider();

        echo do_shortcode('[rev_slider alias="' . $slider->get_alias() . '"][/rev_slider]');
    }


}