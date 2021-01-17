<?php


class RevSliderIntegration_MetaTaxonomy extends RevSliderIntegration_Meta
{
    private const TERM_META = 'revslderintegration_slider_id';
    private const POST_QUERY = 'revsliderintegration-taxonomy-sliderselector';

    /**
     * RevSliderThemeMetaTaxonomy constructor.
     */
    public function __construct()
    {
        parent::__construct('taxonomy');
    }

    public function getSliderId($contentId = ""): string
    {
        if ($contentId == "") {
            $contentId = get_queried_object()->term_id;
        }

        return get_term_meta($contentId, self::TERM_META, true);
    }

    public function contextIsManageable(): bool
    {
        return is_tax() || is_tag() || is_category();
    }

    public function getElements(): array
    {
        return get_taxonomies(array('public' => true));
    }

    public function getDefaultElements(): array
    {
        return array('category');
    }

    public function getCustomizeTitle(): string
    {
        return __('Taxonomy types with Slider');
    }

    public function formFieldCreate($taxonomy)
    {
        $slider = new RevSlider(); ?>

        <div class="form-field term-group">
            <label for="revsliderintegration-taxonomy-sliderselector"><?= __('Header Slider', 'RevSliderThemeIntegration') ?></label>
            <select id="revsliderintegration-taxonomy-sliderselector" name="<?= self::POST_QUERY ?>">
                <option value=""><?= __('Default', 'RevSliderThemeIntegration') ?></option>
                <?php foreach ($slider->get_sliders() as $s): ?>
                    <option value="<?= $s->get_id() ?>"><?= $s->get_title() ?></option>
                <?php endforeach; ?>
            </select>
        </div>

    <?php }

    public function formFieldEdit($term, $taxonomy)
    {
        $slider = new RevSlider(); ?>

        <tr class="form-field">
            <th><label
                        for="revsliderintegration-taxonomy-sliderselector"><?= __('Header Slider', 'RevSliderThemeIntegration') ?></label>
            </th>
            <td><select id="revsliderintegration-taxonomy-sliderselector" name="<?= self::POST_QUERY ?>">
                    <option value=""><?= __('Default', 'RevSliderThemeIntegration') ?></option>
                    <?php foreach ($slider->get_sliders() as $s): ?>
                        <option value="<?= $s->get_id() ?>" <?= ($s->get_id() == get_term_meta($term->ID, self::TERM_META, true)) ? 'selected' : '' ?>><?= $s->get_title() ?></option>
                    <?php endforeach; ?>
                </select></td>
        </tr>

    <?php }

    public function adminInit()
    {
        foreach ($this->getSelectedElements() as $el) {
            add_action($el . '_add_form_fields', [$this, 'formFieldCreate']);
            add_action($el . '_edit_form_fields', [$this, 'formFieldEdit'], 1, 2);
        }
    }

    public function saveMeta($term_id, $tt_id)
    {
        if (isset($_POST[self::POST_QUERY])) {
            update_term_meta($term_id, self::TERM_META, $_POST[self::POST_QUERY]);
        }
    }

    public function init()
    {
        add_action('admin_init', [$this, 'adminInit']);

        foreach ($this->getSelectedElements() as $el) {
            add_action('created_' . $el, [$this, 'saveMeta']);
            add_action('edited_' . $el, [$this, 'saveMeta'], 10, 2);
        }
    }
}