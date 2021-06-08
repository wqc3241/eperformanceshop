<?php
/**
 * Name: Product style 09
 * Slug: content-product-style-09
 **/

$args = isset( $args ) ? $args : null;

?>
<?php
remove_action( 'woocommerce_shop_loop_item_title', 'azirspares_add_categories_product', 20 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'azirspares_woocommerce_group_flash', 10 );
?>
    <div class="product-inner">
        <div class="product-thumb">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked azirspares_woocommerce_group_flash - 10
			 * @hooked azirspares_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title', $args );
			?>
        </div>
        <div class="product-info">
			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked azirspares_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
        </div>
    </div>
<?php
add_action( 'woocommerce_shop_loop_item_title', 'azirspares_add_categories_product', 20 );
add_action( 'woocommerce_before_shop_loop_item_title', 'azirspares_woocommerce_group_flash', 10 );