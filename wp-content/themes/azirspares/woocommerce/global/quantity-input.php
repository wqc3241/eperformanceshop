<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     5.2.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php if ( $max_value && $min_value === $max_value ) {
    ?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty"
               name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>"/>
    </div>
    <?php
} else {
    ?>
    <div class="quantity">
        <span class="qty-label"><?php echo esc_html__( 'Quantiy:', 'azirspares' ) ?></span>
        <div class="control">
            <a class="btn-number qtyminus quantity-minus" href="#"><?php echo esc_html__( '-', 'azirspares' ) ?></a>
            <input type="text" data-step="<?php echo esc_attr( $step ); ?>"
                   id="<?php echo esc_attr( $input_id ); ?>"
                   min="<?php echo esc_attr( $min_value ); ?>"
                   max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
                   name="<?php echo esc_attr( $input_name ); ?>"
                   value="<?php echo esc_attr( $input_value ); ?>"
                   title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'azirspares' ) ?>"
                   class="input-qty input-text qty text" size="4"
                   pattern="<?php echo esc_attr( $pattern ); ?>"
                   inputmode="<?php echo esc_attr( $inputmode ); ?>"/>
            <a class="btn-number qtyplus quantity-plus" href="#"><?php echo esc_html__( '+', 'azirspares' ) ?></a>
        </div>
    </div>
    <?php
}