<?php


class RevSliderIntegration_MetaPost extends RevSliderIntegration_Meta
{
    private const POST_META = 'revslderintegration_slider_id';
    private const POST_QUERY = 'revsliderintegration-post-sliderselector';

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

    public function getElements(): array
    {
        return get_post_types(array('public' => true));
    }

    public function getDefaultElements(): array
    {
        return array('post', 'page');
    }

    public function getCustomizeTitle(): string
    {
        return __('Post types with Slider');
    }

    public function postSelector($post, $metabox)
    {
        $slider = new RevSlider();
        ?>

        <label for="revsliderintegration-post-sliderselector"><?= __('Header Slider', 'RevSliderThemeIntegration') ?></label>
        <select id="revsliderintegration-post-sliderselector" name="<?= self::POST_QUERY ?>">
            <option value=""><?= __('Default', 'RevSliderThemeIntegration') ?></option>
            <?php foreach ($slider->get_sliders() as $s): ?>
                <option value="<?= $s->get_id() ?>" <?= (($s->get_id() == get_post_meta($post->ID, self::POST_META, true)) ? 'selected' : '') ?>><?= $s->get_title() ?></option>
            <?php endforeach; ?>
        </select>
    <?php }

    public function adminInit()
    {
        $options = get_option('revsliderintegration_options');

        if (isset($options[$this->getManagedType()])) {
            add_meta_box('revsliderintegration_post_selector', 'RevSlider Integration', [$this, 'postSelector'], $options[$this->getManagedType()], 'side');
        }
    }

    function savePost(int $post_ID, WP_Post $post, bool $update)
    {
        if (in_array($post->post_type, $this->getSelectedElements())) {
            if (isset($_POST[self::POST_QUERY])) {
                update_post_meta($post_ID, self::POST_META, htmlspecialchars($_POST[self::POST_QUERY]));
            }
        }
    }


    public function init()
    {
        add_action('admin_init', [$this, 'adminInit']);
        add_action('save_post', [$this, 'savePost'], 10, 3);
    }
}