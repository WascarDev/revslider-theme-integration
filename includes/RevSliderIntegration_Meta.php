<?php

abstract class RevSliderIntegration_Meta
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

    public abstract function getSliderId($contentId = ""): string;

    public function getManagedType(): string
    {
        return $this->managed_type;
    }

    public abstract function contextIsManageable(): bool;

    public function typeIsManageable(string $type): bool
    {
        return $this->managed_type === $type;
    }

    public function getSelectedElements()
    {
        $options = get_option('revsliderintegration_options');

        if (isset($options[$this->getManagedType()])) {
            return $options[$this->getManagedType()];
        }

        return array();
    }

    public abstract function getElements(): array;

    public abstract function getDefaultElements(): array;

    public abstract function getCustomizeTitle(): string;

    public abstract function init();

    public function getSlider($contentId = ""): ?RevSliderSlider
    {
        $revslider = new RevSlider();
        $expected_id = $this->getSliderId($contentId);

        if ($expected_id != "") {
            foreach ($revslider->get_sliders() as $slider) {
                if ($slider->get_id() === $expected_id) {
                    return $slider;
                }
            }
        }

        return null;
    }


}