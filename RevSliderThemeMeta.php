<?php

abstract class RevSliderThemeMeta
{
    private string $managed_type;

    /**
     * RevSliderThemeMeta constructor.
     * @param string $managed_type
     */
    protected function __construct(string $managed_type)
    {
        $this->managed_type = $managed_type;
    }

    public abstract function getSliderId(int $contentId = -1): int;

    public function getManagedType(): string {
        return $this->managed_type;
    }

    public abstract function contextIsManageable(): bool;

    public function typeIsManageable(string $type): bool
    {
        return $this->managed_type === $type;
    }

    public function getSlider(int $contentId = -1): ?RevSlider
    {
        $revslider = new RevSlider();
        $expected_id = $this->getSliderId($contentId);

        if ($expected_id != -1) {
            foreach ($revslider->get_sliders() as $slider) {
                if ($slider->get_id() === $expected_id) {
                    return $slider;
                }
            }
        }

        return null;
    }

    public function displaySlider(int $contentId = -1): void
    {
        $slider = $this->getSlider($contentId);

        echo do_shortcode('[rev_slider alias="' . $slider->get_alias() . '"][/rev_slider]');
    }


}