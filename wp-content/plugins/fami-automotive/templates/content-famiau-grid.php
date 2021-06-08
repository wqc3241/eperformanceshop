<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$listing_info    = famiau_get_listing_info();
$item_wrap_class = 'col-ts-12 col-xs-6';
$item_class      = '';
$thumb_html      = '';
$content_html    = '';
$status_html     = '';
$title_html      = '';
$price_html      = '';
$some_info_html  = '';

$dealer_id = $post->post_author;
$dealer    = get_userdata( $dealer_id );

$has_sidebar = is_active_sidebar( 'famiau-sidebar' );
if ( $has_sidebar ) {
	$item_wrap_class .= ' col-bg-4 col-lg-4 col-md-4 col-sm-6';
} else {
	$item_wrap_class .= ' col-bg-3 col-lg-3 col-md-4 col-sm-6';
}

$thumb_w = 320;
$thumb_h = 320;
if ( wp_is_mobile() ) {
	$thumb_h = 220;
}

if ( has_post_thumbnail() ) {
	$item_class .= ' has-post-thumbnail';
	$thumb_img  = famiau_resize_image( get_post_thumbnail_id(), null, $thumb_w, $thumb_h, true, true, false );
	$thumb_html .= '<div class="famiau-thumb-wrap"><a href="' . esc_url( get_permalink() ) . '">' . famiau_img_output( $thumb_img, 'famiau-thumb' ) . '</a></div>';
}

$car_statuses = array(
	'new'            => esc_html__( 'New', 'famiau' ),
	'used'           => esc_html__( 'Used', 'famiau' ),
	'certified-used' => esc_html__( 'Certified Used', 'famiau' )
);

if ( $listing_info ) {
	if ( isset( $car_statuses[ $listing_info['_famiau_car_status'] ] ) ) {
		$status_html .= '<span class="famiau-car-status famiau-car-status-' . esc_attr( $listing_info['_famiau_car_status'] ) . '">' . esc_html( $car_statuses[ $listing_info['_famiau_car_status'] ] ) . '</span>';
	}
	
	$price_html .= famiau_get_price_html( $listing_info['_famiau_price'] );
	if ( $price_html != '' ) {
		$price_html = sprintf( '<div class="famiau-price-html">%s</div>', $price_html );
	}
	$title_html .= '<h3 class="famiau-title famiau-item-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
	
	$some_info_html .= '<span class="famiau-feature famiau-mileage">' . esc_html( $listing_info['_famiau_mileage'] ) . '</span>';
	$some_info_html .= '<span class="famiau-feature famiau-fuel_type">' . esc_html( $listing_info['_famiau_fuel_type'] ) . '</span>';
	$some_info_html .= '<span class="famiau-feature famiau-gearbox_type">' . famiau_gearbox_display_text( $listing_info['_famiau_gearbox_type'] ) . '</span>';
}

$content_html = '<div class="famiau-info-wrap"><div class="famiau-top-part">' . $status_html . $title_html . $price_html . '</div><div class="famiau-bottom-part">' . $some_info_html . '</div></div>';

?>

<div class="<?php echo esc_attr( $item_wrap_class ); ?>">
    <div class="famiau-item famiau-item-layout-grid <?php echo esc_attr( $item_class ); ?>">
		<?php echo wptexturize( $thumb_html ); ?>
        <div class="famiau-item-content">
			<?php echo wptexturize( $content_html ); ?>
        </div>
    </div>
</div>

