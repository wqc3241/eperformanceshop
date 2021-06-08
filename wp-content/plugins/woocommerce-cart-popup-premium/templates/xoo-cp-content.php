<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
	return; 	
}

global $xoo_cp_gl_qtyen_value,$xoo_cp_ad_rl_en_value,$xoo_cp_ad_rl_enm_value,$xoo_cp_ad_rl_tl_value,$xoo_cp_gl_splk_value,$xoo_cp_gl_fullcart_value;

$cart_contents = WC()->cart->get_cart();
$show_full_cart = $xoo_cp_gl_fullcart_value ? true : false;

if(!$show_full_cart){
	if(isset($cart_contents[$cart_item_key])){
		foreach ($cart_contents as $key => $value) {
			if($key != $cart_item_key) unset($cart_contents[$key]);
		}
	}
	else{
		return false;
	}
	
}

$cart_data = $cart_contents;


?>

<?php if(empty($cart_data)): ?>

	<span class="xoo-cp-empty-cart-notice"><?php _e('Your cart is empty','added-to-cart-popup-woocommerce'); ?></span>

	<a class="xcp-btn xoo-cp-sn-btn" href="<?php echo $xoo_cp_gl_splk_value  ? $xoo_cp_gl_splk_value : get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php _e('Shop Now','added-to-cart-popup-woocommerce'); ?></a>


<?php else: ?>

	<table class="xoo-cp-cart">
		<?php if($show_full_cart): ?>
			<tr class="xoo-cp-ths">
				<th class="xcp-rhead"><?php _e('Remove','added-to-cart-popup-woocommerce'); ?></th>
				<th><?php _e('Image','added-to-cart-popup-woocommerce'); ?></th>
				<th><?php _e('Title','added-to-cart-popup-woocommerce'); ?></th>
				<th class="xcp-phead"><?php _e('Price','added-to-cart-popup-woocommerce'); ?></th>
				<th><?php _e('Quantity','added-to-cart-popup-woocommerce'); ?></th>
				<th><?php _e('Total','added-to-cart-popup-woocommerce'); ?></th>
			</tr>
		<?php endif; ?>

	<?php

	foreach ( $cart_data as $cart_item_key => $cart_item ) {
			if(function_exists('wc_pb_is_bundled_cart_item')){
				if(wc_pb_is_bundled_cart_item($cart_item)) continue;
			}
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );


			
			$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

			
			$product_name =  apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
			
									

			$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

			$product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );

			//Variation
			$attributes = wc_get_formatted_variation($_product);
			// Meta data
			$attributes .=  WC()->cart->get_item_data( $cart_item );


			//Quantity input
			$max_value = apply_filters( 'woocommerce_quantity_input_max', $_product->get_max_purchase_quantity(), $_product );
			$min_value = apply_filters( 'woocommerce_quantity_input_min', $_product->get_min_purchase_quantity(), $_product );
			$step      = apply_filters( 'woocommerce_quantity_input_step', 1, $_product );
			$pattern   = apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' );

	?>

		<tr class="xoo-cp-pdetails" data-xoo_cp_key="<?php echo $cart_item_key; ?>">
			<td class="xoo-cp-remove"><span class="xoo-cp-icon-close xoo-cp-remove-pd"></span></td>
			<td class="xoo-cp-pimg"><?php echo $thumbnail; ?></td>
			<td class="xoo-cp-ptitle"><a href="<?php echo $product_permalink; ?>"><?php echo $product_name; ?></a>

				<?php if($attributes): ?>
					<div class="xoo-cp-variations"><?php echo $attributes; ?></div>
				<?php endif; ?>

			</td>
			<td class="xoo-cp-pprice"><?php echo $product_price; ?></td>
			<td class="xoo-cp-pqty">

				<?php if ( $_product->is_sold_individually() || !$xoo_cp_gl_qtyen_value ): ?>
					<span><?php echo $cart_item['quantity']; ?></span>				
				<?php else: ?>
					<div class="xoo-cp-qtybox">
					<span class="xcp-minus xcp-chng">-</span>
					<input type="number" class="xoo-cp-qty" max="<?php esc_attr_e( 0 < $max_value ? $max_value : '' ); ?>" min="<?php esc_attr_e($min_value); ?>" step="<?php echo esc_attr_e($step); ?>" value="<?php echo $cart_item['quantity']; ?>" pattern="<?php esc_attr_e( $pattern ); ?>">
					<span class="xcp-plus xcp-chng">+</span></div>
				<?php endif; ?>

			</td>
			<td class="xoo-cp-ptotal"><?php echo $product_subtotal; ?></td>
		</tr>
	<?php }; ?>

	</table>

	<div class="xoo-cp-table-bottom">
		
		<?php if($show_full_cart): ?>
			<a class="xoo-cp-empct"><span class="xoo-cp-icon-close"></span><?php _e('Empty Cart','added-to-cart-popup-woocommerce'); ?></a>
		<?php endif; ?>

		<div class="xoo-cp-cart-total">
			<span class="xcp-totxt"><?php _e('Total','added-to-cart-popup-woocommerce');?> : </span><span class="xcp-ctotal"><?php echo wc_price(WC()->cart->subtotal); ?></span></div>
	</div>

	<a class="xoo-cp-btn-vc xcp-btn" href="<?php echo wc_get_cart_url(); ?>"><?php _e('View Cart','added-to-cart-popup-woocommerce'); ?></a>
	<a class="xoo-cp-btn-ch xcp-btn" href="<?php echo wc_get_checkout_url(); ?>"><?php _e('Checkout','added-to-cart-popup-woocommerce'); ?></a>
	<a class="xoo-cp-close xcp-btn" href="<?php echo apply_filters('xoo_cp_continue_shopping_url','#'); ?>"><?php _e('Continue Shopping','added-to-cart-popup-woocommerce'); ?></a>


<?php endif; ?>
