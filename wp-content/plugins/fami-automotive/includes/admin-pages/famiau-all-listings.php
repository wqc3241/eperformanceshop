<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$filter_args = array(
	'user_filter' => true,
	'listing_status_filter' => true,
);

?>

<div class="wrap">
    <h1><?php esc_html_e( 'All Listings', 'famiau' ); ?></h1>

    <div class="famiau-admin-page-content-wrap famiau-wrap">
        <div class="famiau-filters-wrap">
			<?php famiau_mega_filter_html( $filter_args ); ?>
        </div>
        <div class="famiau-results-wrap">
			<?php echo famiau_admin_listing_query_results(); ?>
        </div>
    </div>

</div>
