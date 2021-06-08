<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Azirspares Mailchimp
 *
 * Displays Mailchimp widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Azirspares/Widgets
 * @version  1.0.0
 * @extends  AZIRSPARES_Widget
 */
if (!class_exists('Azirspares_Mailchimp_Widget')) {
    class Azirspares_Mailchimp_Widget extends AZIRSPARES_Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $array_settings = apply_filters('azirspares_filter_settings_widget_mailchimp',
                array(
                    'title' => array(
                        'type' => 'text',
                        'title' => esc_html__('Title', 'azirspares-toolkit'),
                    ),
                    'description' => array(
                        'type' => 'text',
                        'title' => esc_html__('Description:', 'azirspares-toolkit'),
                        'default' => esc_html__('To stay up-to-date on our promotions, discounts, sales and more', 'azirspares-toolkit'),
                    ),
                    'placeholder' => array(
                        'type' => 'text',
                        'title' => esc_html__('Placeholder Text:', 'azirspares-toolkit'),
                        'default' => esc_html__('Enter your email address', 'azirspares-toolkit'),
                    ),
                )
            );
            $this->widget_cssclass = 'widget-azirspares-mailchimp';
            $this->widget_description = esc_html__('Display the customer Newsletter.', 'azirspares-toolkit');
            $this->widget_id = 'widget_azirspares_mailchimp';
            $this->widget_name = esc_html__('Azirspares: Newsletter', 'azirspares-toolkit');
            $this->settings = $array_settings;
            parent::__construct();
        }

        /**
         * Output widget.
         *
         * @see WP_Widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
            $this->widget_start($args, $instance);
            ob_start();
            ?>
            <div class="newsletter-form-wrap">
                <div class="desc"><?php echo esc_html($instance['description']); ?></div>
                <div class="form-newsletter">
                    <input class="email email-newsletter" type="email" name="email"
                           placeholder="<?php echo esc_attr($instance['placeholder']); ?>">
                    <a href="#" class="button btn-submit submit-newsletter">
                        <?php echo esc_html__('SUBSCRIBE', 'azirspares-toolkit') ?></span>
                    </a>
                </div>
            </div>
            <?php
            echo apply_filters('azirspares_filter_widget_newsletter', ob_get_clean(), $instance);
            $this->widget_end($args);
        }
    }
}
add_action('widgets_init', 'Azirspares_Mailchimp_Widget');
if (!function_exists('Azirspares_Mailchimp_Widget')) {
    function Azirspares_Mailchimp_Widget()
    {
        register_widget('Azirspares_Mailchimp_Widget');
    }
}