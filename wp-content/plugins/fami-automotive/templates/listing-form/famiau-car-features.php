<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $famiau;
$all_car_features = famiau_car_features();

?>
<?php if (!empty($all_car_features)) { ?>
    <?php foreach ($all_car_features as $car_features) { ?>
        <div class="famiau-feature-row clearfix">
            <?php
            $meta_key = $car_features['meta_key'];
            $option_key = $car_features['option_key'];
            ?>
            <input data-meta_key="<?php echo esc_attr($meta_key); ?>" data-value_type="array" type="hidden"
                   class="famiau-field famiau-field-hidden famiau-hidden"
                   name="<?php echo esc_attr($meta_key); ?>" id="<?php echo esc_attr($meta_key); ?>" value=""/>
            <?php if (isset($famiau[$car_features['option_key']])) { ?>
                <?php
                $items = $famiau[$car_features['option_key']];
                ?>
                <?php if (!empty($items)) { ?>
                    <div class="famiau-feature-col-first">
                        <h6><?php echo esc_html($car_features['tab_name']); ?></h6>
                    </div>
                    <div class="famiau-feature-col-sencond">
                        <?php foreach ($items as $item) { ?>
                            <label class="famiau-secondary-lb"><input
                                        data-meta_key="<?php echo esc_attr($meta_key); ?>"
                                        type="checkbox"
                                        class="famiau-field-for-hidden"
                                        value="<?php echo esc_attr($item); ?>">
                                <span><?php echo esc_attr($item); ?></span>
                            </label>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>