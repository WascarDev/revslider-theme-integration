<?php


class RevSliderIntegration_Control_Checkbox_Multiple extends WP_Customize_Control
{
    public $type = 'checkbox-multiple';

    public function enqueue()
    {
        wp_enqueue_script('revslider-customize-controls',plugin_dir_url(dirname(__FILE__)) . 'checkbox-multiple-control.js', array('jquery'));
    }

    public function render_content()
    {

        if (empty($this->choices))
            return; ?>

        <?php if (!empty($this->label)) : ?>
        <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
    <?php endif; ?>

        <?php if (!empty($this->description)) : ?>
        <span class="description customize-control-description"><?php echo $this->description; ?></span>
    <?php endif; ?>

        <?php $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value(); ?>

        <ul>
            <?php foreach ($this->choices as $value => $label) : ?>

                <li>
                    <label>
                        <input type="checkbox"
                               value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?> />
                        <?php echo esc_html($label); ?>
                    </label>
                </li>

            <?php endforeach; ?>
        </ul>

        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>"/>
    <?php }

}