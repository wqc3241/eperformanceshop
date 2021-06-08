<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$short_args = array(
	'default'      => esc_html__( 'Default shorting', 'famiau' ),
	'year_desc'    => esc_html__( 'Year: newest first', 'famiau' ),
	'year_asc'     => esc_html__( 'Year: oldest first', 'famiau' ),
	'price_asc'    => esc_html__( 'Price: lower first', 'famiau' ),
	'price_desc'   => esc_html__( 'Price: highest first', 'famiau' ),
	'mileage_desc' => esc_html__( 'Mileage: lowest first', 'famiau' ),
	'mileage_asc'  => esc_html__( 'Mileage: highest first', 'famiau' ),
);

$selected = isset( $_REQUEST['order'] ) ? trim( $_REQUEST['order'] ) : 'default';

$content_layout = isset( $_REQUEST['layout'] ) ? $_REQUEST['layout'] : 'list';
if ( $content_layout != 'grid' ) {
	$content_layout = '';
}

// Force grid on mobile
if ( wp_is_mobile() ) {
	$content_layout = 'grid';
}

$listings_page_id = famiau_get_page( 'automotive' );
$uri              = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '#';
if ( $listings_page_id && get_post( $listings_page_id ) ) {
	$uri = get_permalink( $listings_page_id );
}

$grid_layout_url = add_query_arg( 'layout', 'grid', $uri );
$list_layout_url = $uri;

?>

<div class="famiau-listing-shorting-wrap">
    <div class="famiau-sort-by-options">
        <span><?php esc_html_e( 'Sort by:', 'famiau' ); ?></span>
        <div class="famiau-select-sorting-wrap">
            <select class="famiau-select famiau-select-sorting">
				<?php foreach ( $short_args as $short_key => $short_val ) { ?>
                    <option <?php selected( true, $selected == $short_key ); ?>
                            value="<?php echo esc_attr( $short_key ); ?>"><?php echo esc_html( $short_val ); ?></option>
				<?php } ?>
            </select>
        </div>
    </div>
    <div class="famiau-view-by">
        <a href="<?php echo esc_url( $grid_layout_url ); ?>"
           class="layout-grid layout-type <?php echo $content_layout == 'grid' ? 'active' : ''; ?>"
           data-layout="grid">
            <span class="modeview-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
        <a href="<?php echo esc_url( $list_layout_url ); ?>"
           class="layout-list layout-type <?php echo $content_layout != 'grid' ? 'active' : ''; ?>"
           data-layout="list">
            <span class="modeview-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
    </div>
</div>
